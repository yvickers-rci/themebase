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
 *	  - search form layout
 */
require_once( 'inc/utils.php' ); // various utility functions
require_once( 'inc/init.php' ); // theme setup
require_once( 'inc/config.php' ); // configuration
require_once( 'inc/nav.php' ); // navigation overhaul
require_once( 'inc/comments.php' ); // comments overhaul

/*
 * 2. Various Theme Keys and "Enables"
 *	 - Use Local jQuery
 *	 - jQuery Version
 *	 - Typekit
 *	 - AddThis
 *	 - YV Image Fader
 *	 - Flexslider
 *	 - WooCommerce
 */
define( 'LOCAL_JQUERY', true ); // default: false
define( 'JQUERY_VERSION', NULL ); // default: 1.8.3
define( 'TYPEKIT_ID', '' );
define( 'ADDTHIS_ID', '' );
define( 'FLEXSLIDER', false ); // default: false
define( 'YVFADER', false ); // default: false
define( 'WOOCOMMERCE', false ); // default: false

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
if( TYPEKIT_ID ) :

	function client_typekit_fonts() {
		$protocol = ( is_ssl() ) ? 'https:' : 'http:';
		$typekit_id = 'TYPEKIT_ID_HERE';

		// TypeKit  [header]
		echo "<script type=\"text/javascript\" src=\"$protocol//use.typekit.net/$typekit_id.js\"></script>"."\n";
		echo '<script type="text/javascript">try{Typekit.load();}catch(e){}</script>'."\n";
	}
	add_action( 'wp_head', 'client_typekit_fonts' );

endif;


/****************************************
ADDITIONAL SCRIPTS AND STYLES
****************************************/
function yippee_more_scripts_and_styles() {
	$protocol = ( is_ssl() ) ? 'https:' : 'http:';

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
			wp_dequeue_style( 'style' );
			wp_enqueue_style( array( 'flexslider', 'style' ) );
			wp_enqueue_script( 'flexslider' );
		}

	endif;
}
add_action( 'wp_enqueue_scripts', 'yippee_more_scripts_and_styles' );


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

