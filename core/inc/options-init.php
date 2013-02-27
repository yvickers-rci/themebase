<?php
/*
 * Theme Options
 ******************************************
 * Check for Options Framework plugin
 * If plugin is active OR if the OF is
 * built into the theme itself
 ******************************************
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
//exit this file if we aren't using options
if( !is_plugin_active( 'options-framework/options-framework.php' ) && OPTIONS_BUILTIN === false ) :
	return;
endif;

//this contains some sort of options - need to move to client
require_once( TEMPLATEPATH . '/options.php' );

/****************************************
OPTIONS FRAMEWORK
****************************************/
if( OPTIONS_BUILTIN === true ) :

	if ( !function_exists( 'optionsframework_init' ) ) {
		define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/options/' );
		require_once ( TEMPLATEPATH . '/options/options-framework.php' );
	}

endif;

function theme_get_option( $name, $default = false ) {
	$config = get_option( 'optionsframework' );

	if ( ! isset( $config['id'] ) ) {
		return $default;
	}

	$options = get_option( $config['id'] );

	if ( $options[$name] != '' ) {
		return $options[$name];
	} else {
		return $default;
	}

	return $default;
}


// -------------------------- MENUS -------------------------- //

	/****************************************
	LEFT NAVIGATION OPTIONS MENU
	****************************************/
	function optionsframework_toplevel_page() {
		$menu_title = ( OPTIONS_FRAMEWORK_MENU_TITLE ) ? OPTIONS_FRAMEWORK_MENU_TITLE : 'Website Options';
		$page = add_menu_page( $menu_title, $menu_title, 1, 'options-framework', 'optionsframework_page');
	}
	
	function optionsframework_remove_page() {
		$page = remove_submenu_page( 'themes.php', 'options-framework' );
	}
	
	add_action( 'admin_menu', 'optionsframework_toplevel_page' );
	add_action( 'admin_menu', 'optionsframework_remove_page', 999 );

	/****************************************
	ADMIN BAR OPTIONS MENU
	****************************************/
	class OptionsMenu {
	  
		function OptionsMenu() {
			add_action( 'admin_bar_menu', array( $this, 'options_menu_links' ) );
		}
	
		function add_root_menu($name, $id, $href = FALSE) {
			global $wp_admin_bar;
		
			if ( !is_super_admin() || !is_admin_bar_showing() )
				return;
		
			$wp_admin_bar->add_menu(
				array(
					'id' => $id,
					'title' => $name,
					'href' => $href
				)
			);
		}
	
		function add_sub_menu($name, $link, $root_menu, $meta = FALSE) {
			global $wp_admin_bar;
	
			if ( !is_super_admin() || !is_admin_bar_showing() )
				return;
		
			$wp_admin_bar->add_menu(
				array(
					'parent' => $root_menu,
					'title' => $name,
					'href' => $link,
					'meta' => $meta
				)
			);
		}
	
		function options_menu_links() {
			$menu_title = ( OPTIONS_FRAMEWORK_MENU_TITLE ) ? OPTIONS_FRAMEWORK_MENU_TITLE : 'Website Options';
			$this->add_root_menu( $menu_title, 'website_options', admin_url( 'admin.php?page=options-framework' ) );
		}
	
	}
	
	function OptionsMenuInit() {
		global $OptionsMenu; $OptionsMenu = new OptionsMenu();
	}
	add_action( 'init', 'OptionsMenuInit' );
	
	function option_styles() {
		echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . '/assets/css/options.css">';
	}
	add_action( 'admin_head', 'option_styles' );

// -------------------------- MENUS -------------------------- //
