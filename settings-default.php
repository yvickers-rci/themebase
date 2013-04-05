<?php
$themebase_defaults = array(
	/**
	 * sub directories of theme in which to find the code we run
	 * default is in core, do not edit the files in core
	 * client directory is included before default, so it can overwite 0, 1 or even all functions within the corresponding core file
	 */
	'core_directory'=>'core',
	'client_directory'=>'',

	'meta_title_filter'=>true,          		//add blog name and separator to meta title, usually set to false when an seo plugin is added

	/**
	 * configure standard javascript/css variables
	 */
	'local_jquery'=>false,      				//set to true to use google's jquery cdn
	'jquery_version'=>1.8,          			//set version of jquery to use
	'bootstrap_styles'=>false,      			//use prebuilt boostrap styles, set to false to not use or to use custom
	'boostrap_styles_responsive'=>false,        //use prebuilt bootstrap responsive styles

	/**
	 * Client specific ids to pass to general javascript templates
	 */
	'typekit_id'=>'',
	'addthis_id'=>'',
	'ga_id'=>'',

	/**
	 * This identifies menu locations that are available within the appearance > menus section of the admin
	 */
	'nav_menus'=>array(
        'header' => __('Main Menu'),
        'footer' => __('Footer Menu'),
	),

	/**
	 * This controls what size images wordpress should make whenever an upload occurs
	 * please note these should be formatted for use in a call back like the commented out example
	 * it is basically an array using the same arguments as the register_thumbnail_size function would take
	 */
	'image_sizes'=>array(
		//array('thumb-800x600', 800, 600, true),
	),

	/**
	 * these control what classes are applied to the body template
	 */
	'body_class_white_list'=>array( 'home', 'page', 'blog', 'archive', 'single', 'category', 'tag', 'error404', 'logged-in', 'admin-bar' ),
	'body_class_black_list'=>array(),

	'flexslider'=>false,
	'yvfader'=>false,

	'woocommerce'=>false,

	'framework_builtin'=>false,
	'framework_menu_title'=>'',

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