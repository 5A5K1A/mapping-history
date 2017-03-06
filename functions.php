<?php

/**
 * Enqueue styles & scripts
 */
add_action( 'wp_enqueue_scripts', function() {
	// enqueue styles
	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
	wp_enqueue_style( 'app', get_stylesheet_directory_uri().'/dist/css/app.min.css', '', getLastCssModified(), 'screen' ); // main style
	wp_enqueue_style( 'print', get_stylesheet_directory_uri().'/dist/css/print.min.css', '', getLastCssModified(), 'print' ); // print style

	// enqueue javascripts, will combined by grunt
	wp_enqueue_script( 'jquery'); // jQuery, in head
	wp_enqueue_script( 'googlemaps', 'http://maps.google.com/maps/api/js?language=nl', array('jquery'));
	wp_enqueue_script( 'vendor', get_stylesheet_directory_uri().'/dist/js/vendor.min.js', array( 'jquery' ), getLastJsModified() ); // vendor scripts, in head
	wp_enqueue_script( 'app', get_stylesheet_directory_uri().'/dist/js/app.min.js', array( 'vendor' ), getLastJsModified(), $in_footer = true ); // app scripts, in footer
});

add_action( 'after_setup_theme', 'child_theme_setup', 15 );
function child_theme_setup() {

	// register all models
	Marker::Register();
	Theme::Register();

	// register all taxonomies
	Taxonomy_Year::Register();



	if ( ! isset( $content_width ) ) {
		$content_width = 380;
	}

	// user restrictions for editor = Mapping History-invullers
	add_action( 'admin_menu', 'my_remove_menu_pages' );
	function my_remove_menu_pages() {
		remove_menu_page('edit-comments.php');
		if ( !current_user_can('activate_plugins') ) {
			remove_menu_page('tools.php');
			remove_menu_page('edit.php');
			remove_menu_page('admin.php');
		}
 	}

	add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );
 	function remove_admin_bar_links() {
 		if ( !current_user_can('activate_plugins') ) {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu('new-post');
			$wp_admin_bar->remove_menu('new-page');
		}
	}

	add_action( 'init', 'wpsites_remove_webmaster_capabilities' );
	function wpsites_remove_webmaster_capabilities() {

		$webmaster = get_role( 'webmaster' );

		if( isset($webmaster) ) {
			$caps = array(
				'delete_others_pages',
				'delete_pages',
				'delete_private_pages',
				'delete_published_pages',
				'manage_options',
				'manage_links',
				'ure_create_capabilities',
				'ure_create_roles',
				'ure_delete_capabilities',
				'ure_delete_roles',
				'ure_edit_roles Help',
				'ure_manage_options',
				'ure_reset_roles',
			);

			foreach ( $caps as $cap ) {
				if( is_object($webmaster) ) $webmaster->remove_cap( $cap );
			}
		}
	}

	add_action( 'init', 'wpsites_remove_editor_capabilities' );
	function wpsites_remove_editor_capabilities() {

		$editor = get_role( 'editor' );

		if( isset($editor) ) {
			$caps = array(
				'delete_others_marker',
				'edit_others_marker',
				'delete_others_pages',
				'delete_pages',
				'delete_private_pages',
				'delete_published_pages',
				'delete_others_posts',
				'delete_posts',
				'delete_private_posts',
				'delete_published_posts',
				'manage_options',
				'manage_links',
				'ure_create_capabilities',
				'ure_create_roles',
				'ure_delete_capabilities',
				'ure_delete_roles',
				'ure_edit_roles Help',
				'ure_manage_options',
				'ure_reset_roles',
			);

			foreach ( $caps as $cap ) {
				if( is_object($editor) ) $editor->remove_cap( $cap );
			}
		}
	}
}
