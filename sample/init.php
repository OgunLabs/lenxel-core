<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
function lenxel_theme_support_path_demo_content(){
	return (__DIR__.'/demo-data/');
}
add_filter('lnx_importer_dir_path', 'lenxel_theme_support_path_demo_content');

//Way to set menu, import revolution slider, and set home page.
function lenxel_theme_support_import_sample( $demo_active_import , $demo_directory_path ) {
	reset( $demo_active_import );
	$current_key = key( $demo_active_import );

	//Setting Menus
	$wbc_menu_array = array( 'lenxel' );
	if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && in_array( $demo_active_import[$current_key]['directory'], $wbc_menu_array ) ) {

		$dash_menu_lenxel = get_term_by( 'name', 'lenxel Primary Menu', 'nav_menu' );
		$top_menu = get_term_by( 'name', 'Menu 1', 'nav_menu' );
		$set_menu = [];
		if ( isset( $top_menu->term_id ) ) {
			$set_menu['primary'] = $top_menu->term_id;
		}
		if ( isset( $dash_menu_lenxel->term_id ) ) {
			$set_menu['lenxel_loction_primary'] = $dash_menu_lenxel->term_id;
		}
		if($set_menu){
			set_theme_mod( 'nav_menu_locations', $set_menu );
		}
	}

	//Set HomePage
	$wbc_home_pages = array(
		'lenxel' => 'Landing page2'
	);
	if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_home_pages ) ) {
		$page = get_page_by_title( $wbc_home_pages[$demo_active_import[$current_key]['directory']] );
		if ( isset( $page->ID ) ) {
			update_option( 'page_on_front', $page->ID );
			update_option( 'show_on_front', 'page' );
		}
	}
}

add_action( 'wbc_importer_after_content_import', 'lenxel_theme_support_import_sample', 10, 2 );
