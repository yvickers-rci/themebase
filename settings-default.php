<?php
$themebase_defaults = array(
	'core_directory'=>'core',
	'client_directory'=>'',

	'framework_builtin'=>false,
	'framework_menu_title'=>'',

	'local_jquery'=>false,
	'jquery_version'=>1.8,

	'typekit_id'=>'',
	'addthis_id'=>'',
	'ga_id'=>'',

	'flexslider'=>false,
	'yvfader'=>false,

	'woocommerce'=>false,

    /**
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
	'inc_files'=>array(
		//various utility functions
        'inc/utils.php',
		//configuration
		'inc/config.php',
		//theme setup
		'inc/init.php',
		//navigation overhaul
		'inc/nav.php',
		//comments overhaul
		'inc/comments.php',
	),

    /**
     * EXTRA FRONTEND FUNCTIONS
     */
	'function_files'=>array(
		// Dynamically updating copyright info in footer
		'functions/func-copyright.php',
		// Limit the amount of content is show in blurbs
		'functions/func-limit_post.php',
		// Breadcrumbs
		'functions/func-breadcrumbs.php',
		// Side Navigation
		'functions/func-side_nav.php',
		// Twitter Statuses
		'functions/func-twitter.php',
	),
);