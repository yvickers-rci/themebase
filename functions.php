<?php
/****************************************
DEFINITIONS
****************************************/
/*
 * 1. Various Theme Keys and "Enables"
 ******************************************
 *	- Options Framework builtin support
 *	- Options Framework menu adjustment support
 *	- Use local jQuery
 *	- jQuery version
 *	- Typekit
 *	- AddThis
 *	- Red Clay Interactive Yancey Image Fader
 *	- Flexslider
 *	- WooCommerce (tweaks)
 ******************************************
 */
/* Option										// Defaults
 * --------------------------------------------------------------- */
define( 'OPTIONS_FRAMEWORK_BUILTIN', false );	// false (use plugin)
define( 'OPTIONS_FRAMEWORK_MENU_TITLE', '' );	// Website Options

define( 'LOCAL_JQUERY', true );					// false
define( 'JQUERY_VERSION', '' );					// 1.8 (latest)

define( 'TYPEKIT_ID', '' );						// null
define( 'ADDTHIS_ID', '' );						// null

define( 'FLEXSLIDER', false );					// false
define( 'YVFADER', false );						// false

define( 'WOOCOMMERCE', false );					// false


/****************************************
INCLUDE NEEDED FILES
****************************************/
/*
 * Theme Base
 ******************************************
 * inc/thembase.php
 *	- head cleanup
 *	- enqueueing scripts & styles
 *	- theme support functions
 *	- custom menu output & fallbacks
 *	- removing <p> from around images
 *	- customizing the post excerpt
 *	- search form layout
 ******************************************
 */
require_once( 'inc/utils.php' ); // various utility functions
require_once( 'inc/init.php' ); // theme setup
require_once( 'inc/config.php' ); // configuration
require_once( 'inc/nav.php' ); // navigation overhaul
require_once( 'inc/comments.php' ); // comments overhaul
require_once( 'inc/client.php' ); // client specific


/*
 * Theme Options
 ******************************************
 * Check for Options Framework plugin
 * If plugin is active OR if the OF is
 * built into the theme itself
 ******************************************
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
if( is_plugin_active( 'options-framework/options-framework.php' ) || OPTIONS_BUILTIN === true ) :
	require_once( 'inc/options-init.php' );
	require_once( TEMPLATEPATH . '/options.php' );
endif;


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

// Twitter Statuses
require_once( 'functions/func-twitter.php' );