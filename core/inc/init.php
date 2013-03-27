<?php
/****************************************
WORDPRESS CLEANUP
( parts borrowed from Bones http://themble.com/bones/ )
****************************************/
// Banging on all cylinders here!
add_action( 'after_setup_theme','themebase_cleanup', 15 );

function themebase_cleanup() {
	global $themebase_settings;
	// launching operation cleanup
	add_action( 'init', 'themebase_head_cleanup' );
	// remove WP version from RSS
	add_filter( 'the_generator', '__return_false' );
	// remove pesky injected css for recent comments widget
	add_filter( 'wp_head', 'themebase_remove_wp_widget_recent_comments_style', 1 );
	// clean up comment styles in the head
	add_action( 'wp_head', 'themebase_remove_recent_comments_style', 1 );

	// enqueue base scripts and styles ( config.php )
	add_action( 'wp_enqueue_scripts', 'themebase_scripts_and_styles', 50 );

	// launching this stuff after theme setup ( config.php )
	add_action( 'after_setup_theme','themebase_theme_support' );
	// adding sidebars to WP ( config.php)
	add_action( 'widgets_init', 'themebase_register_sidebars' );
	// adding the themebase search form
	add_filter( 'get_search_form', 'themebase_wpsearch' );

	// cleaning up random code around images
	add_filter( 'the_content', 'themebase_filter_ptags_on_images' );
	// cleaning up excerpt
	add_filter( 'excerpt_more', 'themebase_excerpt_more' );
	
	// nice, neat, well-formed page titles
	if($themebase_settings['meta_title_filter']){
		add_filter( 'wp_title', 'themebase_wp_title', 10, 2 );
	}

	// add and remove body_class() classes ( config.php )
	add_filter( 'body_class', 'themebase_body_class', 10, 2 );
}


/****************************************
WP_HEAD CLEANUP
( parts borrowed from Bones http://themble.com/bones/ )
****************************************/
function themebase_head_cleanup() {
	// category feeds
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// index link
	remove_action( 'wp_head', 'index_rel_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
	// remove WP version from css
	add_filter( 'style_loader_src', 'themebase_remove_wp_ver_css_js', 9999 );
	// remove WP version from scripts
	add_filter( 'script_loader_src', 'themebase_remove_wp_ver_css_js', 9999 );
	// clean up output of stylesheet <link> tags
	add_filter( 'style_loader_tag', 'roots_clean_style_tag' );

	// rewrite canonical links if Yoast SEO is present
	if ( ! class_exists( 'WPSEO_Frontend' ) ) {
		remove_action( 'wp_head', 'rel_canonical' );
		add_action( 'wp_head', 'roots_rel_canonical' );
	}
}

function roots_rel_canonical() {
	global $wp_the_query;

	if ( ! is_singular() ) {
		return;
	}

	if ( ! $id = $wp_the_query->get_queried_object_id() ) {
		return;
	}

	$link = get_permalink( $id );
	echo "\t<link rel=\"canonical\" href=\"$link\">\n";
}

// remove WP version from scripts
function themebase_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

// clean up output of stylesheet <link> tags
// ( borrowed from Roots http://rootstheme.com/ )
function roots_clean_style_tag($input) {
	preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
	// Only display media if it's print
	$media = $matches[3][0] === 'print' ? ' media="print"' : '';
	return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}

// remove injected CSS for recent comments widget
function themebase_remove_wp_widget_recent_comments_style() {
	if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
		remove_filter('wp_head', 'wp_widget_recent_comments_style' );
	}
}

// remove injected CSS from recent comments widget
function themebase_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	}
}

// Page Titles
// ( borrowed from Twenty Twelve 1.0 )
function themebase_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'themebase' ), max( $paged, $page ) );

	return $title;
}

/****************************************
ROOT RELATIVE URLs
( borrowed from Roots http://rootstheme.com/ )

WordPress likes to use absolute URLs on everything - let's clean that up.
Inspired by http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/

You can enable/disable this feature in config.php:
current_theme_supports('root-relative-urls');

@author Scott Walkinshaw <scott.walkinshaw@gmail.com>
****************************************/
function roots_root_relative_url($input) {
	$output = preg_replace_callback(
		'!(https?://[^/|"]+)([^"]+)?!',
		create_function(
			'$matches',
			// If full URL is home_url("/") and this isn't a subdir install, return a slash for relative root
			'if (isset($matches[0]) && $matches[0] === home_url("/") && str_replace("http://", "", home_url("/", "http"))==$_SERVER["HTTP_HOST"]) { return "/";' .
			// If domain is equal to home_url("/"), then make URL relative
			'} elseif (isset($matches[0]) && strpos($matches[0], home_url("/")) !== false) { return $matches[2];' .
			// If domain is not equal to home_url("/"), do not make external link relative
			'} else { return $matches[0]; };'
		),
		$input
	);

  return $output;
}

/**
 * Terrible workaround to remove the duplicate subfolder in the src of <script> and <link> tags
 * Example: /subfolder/subfolder/css/style.css
 */
function roots_fix_duplicate_subfolder_urls($input) {
	$output = roots_root_relative_url($input);
	preg_match_all('!([^/]+)/([^/]+)!', $output, $matches);

	if (isset($matches[1][0]) && isset($matches[2][0])) {
		if ($matches[1][0] === $matches[2][0]) {
			$output = substr($output, strlen($matches[1][0]) + 1);
		}
	}

	return $output;
}

function roots_enable_root_relative_urls() {
	return !(is_admin() && in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) && current_theme_supports('root-relative-urls');
}

if (roots_enable_root_relative_urls()) {
	$root_rel_filters = array(
		'bloginfo_url',
		'theme_root_uri',
		'stylesheet_directory_uri',
		'template_directory_uri',
		'plugins_url',
		'the_permalink',
		'wp_list_pages',
		'wp_list_categories',
		'wp_nav_menu',
		'the_content_more_link',
		'the_tags',
		'get_pagenum_link',
		'get_comment_link',
		'month_link',
		'day_link',
		'year_link',
		'tag_link',
		'the_author_posts_link'
	);

	add_filters($root_rel_filters, 'roots_root_relative_url');

	add_filter('script_loader_src', 'roots_fix_duplicate_subfolder_urls');
	add_filter('style_loader_src', 'roots_fix_duplicate_subfolder_urls');
}

/****************************************
RANDOM CLEANUP ITEMS
( parts borrowed from Bones http://themble.com/bones/ )
****************************************/
// remove the p from around imgs
// (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function themebase_filter_ptags_on_images( $content ){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying [...] to a Read More link
function themebase_excerpt_more( $more ) {
	global $post;
	// edit here if you like
	return '...  <a href="'. get_permalink( $post->ID ) . '" title="Read '.get_the_title( $post->ID ).'">Read more &raquo;</a>';
}

// Remove self closing tags
// ( borrowed from Roots http://rootstheme.com/ )
function roots_remove_self_closing_tags($input) {
	return str_replace(' />', '>', $input);
}
add_filter( 'get_avatar', 		'roots_remove_self_closing_tags' ); // <img />
add_filter( 'comment_id_fields',   'roots_remove_self_closing_tags' ); // <input />
add_filter( 'post_thumbnail_html', 'roots_remove_self_closing_tags' ); // <img />


/****************************************
SEARCH FORM LAYOUT 
( borrowed from Bones http://themble.com/bones/ )
****************************************/
// Search Form
function themebase_wpsearch($form) {
	$form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
	<label class="screen-reader-text" for="s">' . __('Search for:', 'themebasetheme') . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.esc_attr__('Search the Site...','themebasetheme').'" />
	<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
	</form>';
	return $form;
}