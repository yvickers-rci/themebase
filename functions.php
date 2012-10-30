<?php
/****************************************
INCLUDE NEEDED FILES
****************************************/
/*
 * 1. inc/thembase.php
 *	  - head cleanup
 *	  - enqueueing scripts & styles
 *	  - theme support functions
 *	  - custom menu output & fallbacks
 *	  - removing <p> from around images
 *	  - customizing the post excerpt
 */
require_once('inc/themebase.php'); // DO NOT REMOVE


/****************************************
EXTRA FRONTEND FUNCTIONS
****************************************/
// Dynamically updating copyright info in footer
require_once( 'functions/func-copyright.php' );

// Limit the amount of content is show in blurbs
require_once( 'functions/func-limit_post.php' );

// Breadcrumbs
require_once( 'functions/func-breadcrumbs.php' );

// Side Navigation
require_once( 'functions/func-side_nav.php' );

// Related Items - REQUIRES MAJOR ADMIN CUSTOMIZATION
// USE AT YOUR OWN RISK OF EXTRA DEVELOPMENT TIME
// require_once( 'functions/func-related_items.php' );


/****************************************
TYPEKIT SUPPORT
****************************************/
/*
 * Uncomment to use typekit
 *
function client_typekit_fonts() {
	$protocol = ( is_ssl() ) ? 'https:' : 'http:';
	$typekit_id = 'TYPEKIT_ID_HERE';

	// TypeKit  [header]
	echo "<script type=\"text/javascript\" src=\"$protocol//use.typekit.net/$typekit_id.js\"></script>"."\n";
	echo '<script type="text/javascript">try{Typekit.load();}catch(e){}</script>'."\n";
}
add_action( 'wp_head', 'client_typekit_fonts' );
*/


/****************************************
ADDITIONAL SCRIPTS AND STYLES
****************************************/
function yippee_more_scripts_and_styles() {
	$protocol = ( is_ssl() ) ? 'https:' : 'http:';

	// AddThis [footer]
	$addthis_account_id = 'ADDTHIS_ID_HERE'; /* @plugin rciadmin plugin dependent - ( get_option( 'rci_addthis' ) ) ? get_option( 'rci_addthis' ) : ''; */
	wp_enqueue_script( 'addthis', "$protocol//s7.addthis.com/js/300/addthis_widget.js#pubid=$addthis_account_id", array(), '1', true );

	// Flexslider script [footer]
	wp_register_script( 'flexslider', get_template_directory_uri() . "/assets/flexslider/jquery.flexslider-min.js", array(), '2.1', true );

	// Flexslider stylesheet [header]
	wp_register_style( 'flexslider', get_template_directory_uri() . "/assets/flexslider/flexslider.css", array(), '2.1', null );

	// Flexslider on the homepage only.
	// Change this to meet specific client needs.
	if( is_home() ) {
		wp_dequeue_style( 'themebase' );
		wp_enqueue_style( array( 'flexslider', 'themebase' ) );
		wp_enqueue_script( 'flexslider' );
	}
}
add_action( 'wp_enqueue_scripts', 'yippee_more_scripts_and_styles' );

/****************************************
THUMBNAIL SIZE OPTIONS
****************************************/
// Thumbnail sizes
add_image_size( 'thumb-800x600', 800, 600, true );
add_image_size( 'thumb-480x320', 480, 320, true );


/****************************************
DYANMIC / ACTIVE SIDEBARS
****************************************/
// Sidebars & Widgetizes Areas
function themebase_register_sidebars() {
    register_sidebar(array(
    	'id' 		 => 'sidebar',
    	'name' 		 => __( 'Sidebar' ),
    	'description' 	 => __( '' ),
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget'  => '</div>',
    	'before_title'  => '<h4 class="widgettitle">',
    	'after_title' 	 => '</h4>',
    ));
}


/****************************************
WOOCOMMERCE CART CHANGES
****************************************/
/*
 * Uncomment if using WooCommerce
 *
function themebase_change_cart_label() {
	global $menu;
	global $submenu;
	global $admin_menu_options;

	$menu["55.5"][0] = 'Store';
}
add_action( 'admin_menu', 'themebase_change_cart_label' );
*/

/****************************************
COMMENT LAYOUT 
( borrowed from Bones http://themble.com/bones/ )
****************************************/
function themebase_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<header class="comment-author vcard">
			    <?php 
			    /*
			        this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
			        echo get_avatar($comment,$size='32',$default='<path_to_url>' );
			    */ 
			    ?>
			    <!-- custom gravatar call -->
			    <?php
			    	// create variable
			    	$bgauthemail = get_comment_author_email();
			    ?>
			    <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5($bgauthemail); ?>?s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
			    <!-- end custom gravatar call -->
				<?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
				<time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time('F jS, Y'); ?> </a></time>
				<?php edit_comment_link(__('(Edit)', 'themebasetheme'),'  ','') ?>
			</header>
			<?php if ($comment->comment_approved == '0') : ?>
       			<div class="alert info">
          			<p><?php _e('Your comment is awaiting moderation.', 'themebasetheme') ?></p>
          		</div>
			<?php endif; ?>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
			</section>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</article>
    <!-- </li> is added by WordPress automatically -->
<?php
} // don't remove this bracket!


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