<?php
/****************************************
THEME SUPPORT
****************************************/
function themebase_theme_support() {
	// remove admin menu bar
	add_filter( 'show_admin_bar', '__return_false' );
	
	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style( 'assets/css/editor-style.css' );

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
}


/****************************************
THUMBNAIL SIZE OPTIONS
****************************************/
$image_sizes = tb_get_setting('image_sizes');
foreach($image_sizes as $size){
	call_user_func_array('add_image_size',$size);
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
		$jquery_version = ( JQUERY_VERSION ) ? JQUERY_VERSION : '1.8';
		$jquery_location = ( LOCAL_JQUERY === true ) ? get_template_directory_uri() . "/assets/js/jquery-$jquery_version.min.js" : "$protocol//ajax.googleapis.com/ajax/libs/jquery/$jquery_version/jquery.min.js";
		wp_enqueue_script( 'jquery', $jquery_location, array(), '1.8', false );
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

	wp_register_script( 'plugins', get_template_directory_uri() . '/assets/js/plugins.js', false, null, true );
	wp_enqueue_script( 'plugins' );

	if( ADDTHIS_ID || get_option( 'rci_addthis' ) ) :

		// AddThis [footer]
		$addthis_account_id = ( get_option( 'rci_addthis' ) ) ? get_option( 'rci_addthis' ) : ADDTHIS_ID;
		wp_enqueue_script( 'addthis', "$protocol//s7.addthis.com/js/300/addthis_widget.js#pubid=$addthis_account_id", array(), '1', true );

	endif;

	if( FLEXSLIDER === true ) :

		// Flexslider script [footer]
		wp_register_script( 'flexslider', get_template_directory_uri() . "/assets/flexslider/jquery.flexslider-min.js", array(), '2.1', true );
	
		// Flexslider stylesheet [header]
		wp_register_style( 'flexslider', get_template_directory_uri() . "/assets/flexslider/flexslider.css", array(), '2.1', null );
	
		// Flexslider on the homepage only.
		// Change this to meet specific client needs.
		if( is_home() ) {
			wp_enqueue_style( 'flexslider' );
			wp_enqueue_script( 'flexslider' );
		}

	endif;
}


/****************************************
ADDITIONAL SCRIPTS AND STYLES
****************************************/
function yippee_more_scripts_and_styles() {
	$protocol = ( is_ssl() ) ? 'https:' : 'http:';
}
add_action( 'wp_enqueue_scripts', 'yippee_more_scripts_and_styles', 1 );


/****************************************
TYPEKIT SUPPORT
****************************************/
if( TYPEKIT_ID ) :

	function client_typekit_fonts() {
		$protocol = ( is_ssl() ) ? 'https:' : 'http:';
		$typekit_id = TYPEKIT_ID;

		// TypeKit  [header]
		echo "<script type=\"text/javascript\" src=\"$protocol//use.typekit.net/$typekit_id.js\"></script>"."\n";
		echo '<script type="text/javascript">try{Typekit.load();}catch(e){}</script>'."\n";
	}
	add_action( 'wp_head', 'client_typekit_fonts' );

endif;


/****************************************
REMOVE DASHBOARD WIDGETS
****************************************/
function remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
}
add_action( 'admin_init', 'remove_dashboard_widgets' );


/****************************************
DYANMIC / ACTIVE SIDEBARS
****************************************/
// Sidebars & Widgetizes Areas
function themebase_register_sidebars() {
    register_sidebar(array(
    	'id' 		 	=> 'sidebar',
    	'name' 		 	=> __( 'Sidebar' ),
    	'description'	=> __( '' ),
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget'  => '</div>',
    	'before_title'  => '<h4 class="widgettitle">',
    	'after_title'	=> '</h4>',
    ));
}


/****************************************
WOOCOMMERCE CART CHANGES
****************************************/
if( WOOCOMMERCE === true ) :

	function themebase_change_cart_label() {
		global $menu;
		global $submenu;
		global $admin_menu_options;
	
		$menu["55.5"][0] = 'Store';
	}
	add_action( 'admin_menu', 'themebase_change_cart_label' );

endif;


/****************************************
GET SINGLE TERM FOR POST
****************************************/
function get_single_term( $post_id, $taxonomy, $var = 'slug' ) {
	$terms = wp_get_object_terms( $post_id, $taxonomy );

	if( ! is_wp_error( $terms ) ) {
		return $terms[0]->$var;
	}
}


/****************************************
BOOTSTRAP
****************************************/
// Prep the bootstrap for use
function bootstrap_styles() {
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', false, null );
	//wp_enqueue_style( 'bootstrap_responsive', get_template_directory_uri() . '/assets/css/bootstrap-responsive.css', array( 'bootstrap' ), null );
}
// Uncomment the following line to use the bootstrap stylesheets
//add_action( 'wp_enqueue_scripts', 'bootstrap_styles', 1 );


/****************************************
MISCELLANEOUS
****************************************/
// http://wordpress.stackexchange.com/questions/15850/remove-classes-from-body-class
function themebase_body_class( $wp_classes, $extra_classes ) {
    // List of the only WP generated classes allowed
    $whitelist = array( 'home', 'page', 'blog', 'archive', 'single', 'category', 'tag', 'error404', 'logged-in', 'admin-bar' );

    // List of the only WP generated classes that are not allowed
    $blacklist = array( 'home', 'blog', 'archive', 'single', 'category', 'tag', 'error404', 'logged-in', 'admin-bar' );

    // Filter the body classes
    // Whitelist result: (comment if you want to blacklist classes)
    $wp_classes = array_intersect( $wp_classes, $whitelist );
    // Blacklist result: (uncomment if you want to blacklist classes)
    # $wp_classes = array_diff( $wp_classes, $blacklist );

    // Add the extra classes back untouched
    return array_merge( $wp_classes, (array) $extra_classes );
}