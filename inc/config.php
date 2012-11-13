<?php
/****************************************
THEME SUPPORT
****************************************/
function themebase_theme_support() {
	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style( 'assets/css/editor-style.css' );

	// default thumb size
	set_post_thumbnail_size( 125, 125, true );

	// rss feeds
	add_theme_support( 'automatic-feed-links' );

	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus
	register_nav_menus(
		array(
			'header' 	=> __( 'Main Menu' ),
			'footer' 	=> __( 'Footer Menu' ),
		)
	);

	// Enable relative URLs
	add_theme_support( 'root-relative-urls' );

	// Enable Bootstrap's fixed navbar
	// add_theme_support( 'bootstrap-top-navbar' );
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
		wp_enqueue_script( 'jquery', "$protocol//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js", array(), '1.8.2', false );
	}
	
	// Modernizr [header]
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/assets/js/modernizr-2.6.2.min.js', array(), '2.6.2', false );
	
	// 'general' [footer]
	$general_js_dir = ( file_exists( STYLESHEETPATH . '/assets/js/general.js' ) ) ? get_stylesheet_directory_uri() : get_template_directory_uri();
	wp_enqueue_script( 'general', "$general_js_dir/assets/js/general.js", array(), '1', true );
	
	// RCI Slider 'fader' script [footer]
	if( YVFADER === true ) :
		wp_register_script( 'fader', get_template_directory_uri() . '/assets/js/fader.js', array(), '1', true );
		wp_enqueue_script( 'fader' );
	endif;

	// Stylesheet [header]
	wp_enqueue_style( 'style', get_stylesheet_uri(), false, null );

	wp_register_script( 'plugins', get_template_directory_uri() . '/assets/js/plugins.js', false, null, false );
	wp_enqueue_script( 'plugins' );
}


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
BOOTSTRAP
****************************************/
// Prep the bootstrap for use
function bootstrap_styles() {
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', false, null );
	wp_enqueue_style( 'bootstrap_responsive', get_template_directory_uri() . '/assets/css/bootstrap-responsive.css', array( 'bootstrap' ), null );
}
// Uncomment the following line to use the bootstrap stylesheets
// add_action( 'wp_enqueue_scripts', 'bootstrap_styles', 100 );