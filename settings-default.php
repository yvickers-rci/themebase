<?php
$themebase_defaults = array(
	'core_directory'=>'core',
	'client_directory'=>'',

	'framework_builtin'=>false,
	'framework_menu_title'=>'',

	'meta_title_filter'=>true,          //add blog name and separator to meta title, usually set to false when an seo plugin is added
	'local_jquery'=>false,
	'jquery_version'=>1.8,
	'bootstrap_styles'=>false,
	'boostrap_styles_responsive'=>false,

	'typekit_id'=>'',
	'addthis_id'=>'',
	'ga_id'=>'',

	'flexslider'=>false,
	'yvfader'=>false,

	'woocommerce'=>false,

	'image_sizes'=>array(),

	'nav_menus'=>array(),

	'body_class_white_list'=>array( 'home', 'page', 'blog', 'archive', 'single', 'category', 'tag', 'error404', 'logged-in', 'admin-bar' ),
	'body_class_black_list'=>array(),

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