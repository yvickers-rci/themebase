<?php
/****************************************
WORDPRESS CLEANUP
( parts borrowed from Bones http://themble.com/bones/ )
****************************************/

// we're firing all out initial functions at the start
add_action( 'after_setup_theme','themebase_cleanup', 15 );

function themebase_cleanup() {
	// launching operation cleanup
	add_action( 'init', 'themebase_head_cleanup' );
	// remove WP version from RSS
	add_filter( 'the_generator', 'themebase_rss_version' );
	// remove pesky injected css for recent comments widget
	add_filter( 'wp_head', 'themebase_remove_wp_widget_recent_comments_style', 1 );
	// clean up comment styles in the head
	add_action( 'wp_head', 'themebase_remove_recent_comments_style', 1 );
	// nice, neat, well-formed page titles
	add_filter( 'wp_title', 'themebase_wp_title', 10, 2 );

	// enqueue base scripts and styles
	add_action( 'wp_enqueue_scripts', 'themebase_scripts_and_styles', 999 );

	// launching this stuff after theme setup
	add_action( 'after_setup_theme','themebase_theme_support' );
	// adding sidebars to Wordpress (these are created in functions.php)
	add_action( 'widgets_init', 'themebase_register_sidebars' );
	// adding the themebase search form (created in functions.php)
	add_filter( 'get_search_form', 'themebase_wpsearch' );

	// cleaning up random code around images
	add_filter( 'the_content', 'themebase_filter_ptags_on_images' );
	// cleaning up excerpt
	add_filter( 'excerpt_more', 'themebase_excerpt_more' );
}


/****************************************
WP_HEAD CLEANUP
( parts borrowed from Bones http://themble.com/bones/ )
****************************************/

function themebase_head_cleanup() {
	// category feeds
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	// remove_action( 'wp_head', 'feed_links', 2 );
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
	// remove Wp version from scripts
	add_filter( 'script_loader_src', 'themebase_remove_wp_ver_css_js', 9999 );
}

// remove WP version from RSS
function themebase_rss_version() { return ''; }

// remove WP version from scripts
function themebase_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
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


// Page Titles ( borrowed from Twenty Twelve 1.0 )
function themebase_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the blog name.
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'themebase' ), max( $paged, $page ) );

	return $title;
}

/****************************************
ENQUEUE SCRIPTS AND STYLES
( borrowed from Twenty Twelve 1.0 )
*****************************************/

function themebase_scripts_and_styles() {
	$protocol = ( is_ssl() ) ? 'https:' : 'http:';

	// jQuery [header]
	if( !is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_enqueue_script( 'jquery', "$protocol//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js", array(), '1.8.0', false );
	}
	
	// Modernizr [header]
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/modernizr-2.6.1.min.js', array(), '2.6.1', false );
	
	// 'general' [footer]
	$general_js_dir = ( file_exists( STYLESHEETPATH . '/assets/js/general.js' ) ) ? get_stylesheet_directory_uri() : get_template_directory_uri();
	wp_enqueue_script( 'general', "$general_js_dir/assets/js/general.js", array(), '1', true );
	
	// RCI Slider 'fader' script [footer]
	$fader_js_dir = ( file_exists( STYLESHEETPATH . '/assets/js/fader.js' ) ) ? get_stylesheet_directory_uri() : get_template_directory_uri();
	wp_register_script( 'fader', "$fader_js_dir/assets/js/fader.js", array(), '1', true );

	// Stylesheet [header]
	wp_enqueue_style( 'themebase-style', get_stylesheet_uri() );
}


/****************************************
THEME SUPPORT
****************************************/

function themebase_theme_support() {
	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// default thumb size
	set_post_thumbnail_size( 125, 125, true );

	// rss feeds
	add_theme_support( 'automatic-feed-links' );

	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus
	register_nav_menus(
		array(
			'header' 	=> __( 'Main Menu', 'themebase' ),
			'footer' 	=> __( 'Footer Menu', 'themebase' ),
		)
	);
}

/****************************************
RANDOM CLEANUP ITEMS
( parts borrowed from Bones http://themble.com/bones/ )
****************************************/

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function themebase_filter_ptags_on_images( $content ){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying […] to a Read More link
function themebase_excerpt_more( $more ) {
	global $post;
	// edit here if you like
	return '...  <a href="'. get_permalink( $post->ID ) . '" title="Read '.get_the_title( $post->ID ).'">Read more &raquo;</a>';
}