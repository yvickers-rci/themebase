<?php
/**
 * ATTENTION!
 * Do not modify this file, the file you are most likely looking for is settings.php.  Otherwise it is in the client specific sub folder
 *
 * This file is updated periodically, so any edits you make here will get overwritten.
 * Sites will break.
 * Kittens will cry.
 * Why do you hate kittens?
 *
 */

//initialize empty settings
$themebase_settings = array();

//load up our defaults and client specific settings - rename settings-sample.php to settings.php to start
//use defaults to see all possible settings you can configure
include('settings-default.php');
if(is_file(TEMPLATEPATH.'/settings.php')){
	include(TEMPLATEPATH.'/settings.php');
}

//this basically says that we will use our settings and any that are not specifically set, use the default
$themebase_settings += $themebase_defaults;

/* Option										// Defaults
 * --------------------------------------------------------------- */
define( 'OPTIONS_FRAMEWORK_BUILTIN', $themebase_settings['framework_builtin'] );	// false (use plugin)
define( 'OPTIONS_FRAMEWORK_MENU_TITLE', $themebase_settings['framework_menu_title'] );	// Website Options

define( 'LOCAL_JQUERY', $themebase_settings['local_jquery'] );					// false
define( 'JQUERY_VERSION', $themebase_settings['jquery_version'] );					// 1.8 (latest)

define( 'TYPEKIT_ID', $themebase_settings['typekit_id'] );						// null
define( 'ADDTHIS_ID', $themebase_settings['addthis_id'] );						// null

define( 'FLEXSLIDER', $themebase_settings['flexslider'] );					// false
define( 'YVFADER', $themebase_settings['yvfader'] );						// false

define( 'WOOCOMMERCE', $themebase_settings['woocommerce'] );					// false

foreach($themebase_settings['inc_files'] as $file){
	tb_include_file($file);
}

foreach($themebase_settings['function_files'] as $file){
    tb_include_file($file);
}

/**
 * include core file and possibly a client file that overwrites core
 */
function tb_include_file($file){
	global $themebase_settings;
    $core_file = TEMPLATEPATH.'/'.$themebase_settings['core_directory'].'/'.$file;
	$client_file = TEMPLATEPATH.'/'.$themebase_settings['client_directory'].'/'.$file;
	if(is_file($client_file)){
		include($client_file);
	}
	require_once($core_file);
}

/**
 * function to provide access to themebase settings no matter where you are
 * this will handle scoping for you, if you don't know how to
 */
function tb_get_setting($setting){
	global $themebase_settings;
	return isset($themebase_settings[$setting])? $themebase_settings[$setting]:false;
}

/**
 * checks for client specific template file to use in place of themebase template
 */
function tb_template_file($template){
	$client_directory = tb_get_setting('client_directory');
	if('' == $client_directory) return;

	$file = TEMPLATEPATH.'/'.$client_directory.'/templates/'.$template;

	if(!is_file($file)){
		return false;
	}

	include($file);
	return true;
}