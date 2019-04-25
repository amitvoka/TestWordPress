<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Admin functions for the Event post type
 *
 * @author 		Themeum
 * @category 	Admin
 * @version     1.0
 *-------------------------------------------------------------*/

/**
 * Register post type Package
 *
 * @return void
 */

function reg_themeum_travelkit_package_post_type()
{
	$labels = array( 
		'name'                	=> _x( 'Packages', 'Packages', 'themeum-core' ),
		'singular_name'       	=> _x( 'Package', 'Package', 'themeum-core' ),
		'menu_name'           	=> __( 'Packages', 'themeum-core' ),
		'parent_item_colon'   	=> __( 'Parent Package:', 'themeum-core' ),
		'all_items'           	=> __( 'All Packages', 'themeum-core' ),
		'view_item'           	=> __( 'View Package', 'themeum-core' ),
		'add_new_item'        	=> __( 'Add New Package', 'themeum-core' ),
		'add_new'             	=> __( 'New Package', 'themeum-core' ),
		'edit_item'           	=> __( 'Edit Package', 'themeum-core' ),
		'update_item'         	=> __( 'Update Package', 'themeum-core' ),
		'search_items'        	=> __( 'Search Package', 'themeum-core' ),
		'not_found'           	=> __( 'No article found', 'themeum-core' ),
		'not_found_in_trash'  	=> __( 'No article found in Trash', 'themeum-core' )
		);

	$args = array(  
		'labels'             	=> $labels,
		'public'             	=> true,
		'publicly_queryable' 	=> true,
		'show_in_menu'       	=> true,
		'show_in_admin_bar'   	=> true,
		'can_export'          	=> true,
		'has_archive'        	=> false,
		'hierarchical'       	=> false,
		'menu_position'      	=> true,
		'menu_icon'				=> 'dashicons-palmtree',
		'supports'           	=> array( 'title','editor','thumbnail'),
		);

	register_post_type('package',$args);

}

add_action('init','reg_themeum_travelkit_package_post_type');


/**
 * View Message When Updated Packages
 *
 * @param array $messages Existing post update messages.
 * @return array
 */

function themeum_travelkit_package_update_message_course( $messages )
{
	global $post, $post_ID;

	$message['package'] = array(
		0 => '',
		1 => sprintf( __('Package updated. <a href="%s">View Package</a>', 'themeum-core' ), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.', 'themeum-core' ),
		3 => __('Custom field deleted.', 'themeum-core' ),
		4 => __('Package updated.', 'themeum-core' ),
		5 => isset($_GET['revision']) ? sprintf( __('Package restored to revision from %s', 'themeum-core' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Package published. <a href="%s">View Package</a>', 'themeum-core' ), esc_url( get_permalink($post_ID) ) ),
		7 => __('Package saved.', 'themeum-core' ),
		8 => sprintf( __('Package submitted. <a target="_blank" href="%s">Preview Package</a>', 'themeum-core' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Package scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Package</a>', 'themeum-core' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Package draft updated. <a target="_blank" href="%s">Preview Package</a>', 'themeum-core' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

return $message;
}

add_filter( 'post_updated_messages', 'themeum_travelkit_package_update_message_course' );


/**
 * Register Package Category Taxonomies
 *
 * @return void
 */

function reg_themeum_travelkit_package_cat_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'Package Categories', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'Package Category', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search Package Category', 'themeum-core' ),
		'all_items'         	=> __( 'All Package Category', 'themeum-core' ),
		'parent_item'       	=> __( 'Package Parent Category', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'Package Parent Category:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit Package Category', 'themeum-core' ),
		'update_item'       	=> __( 'Update Package Category', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New Package Category', 'themeum-core' ),
		'new_item_name'     	=> __( 'New Package Category Name', 'themeum-core' ),
		'menu_name'         	=> __( 'Package Category', 'themeum-core' )
		);

	$args = array(
		'hierarchical'      	=> true,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('package-category',array( 'package' ),$args);
}

add_action('init','reg_themeum_travelkit_package_cat_taxonomy');


/**
 * Register Package Category Taxonomies
 *
 * @return void
 */

function reg_themeum_travelkit_package_location_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'Package Locations', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'Package Location', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search Package Location', 'themeum-core' ),
		'all_items'         	=> __( 'All Package Location', 'themeum-core' ),
		'parent_item'       	=> __( 'Package Parent Location', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'Package Parent Location:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit Package Location', 'themeum-core' ),
		'update_item'       	=> __( 'Update Package Location', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New Package Location', 'themeum-core' ),
		'new_item_name'     	=> __( 'New Package Location Name', 'themeum-core' ),
		'menu_name'         	=> __( 'Package Location', 'themeum-core' )
		);

	$args = array(
		'hierarchical'      	=> true,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('package-location',array( 'package' ),$args);
}

add_action('init','reg_themeum_travelkit_package_location_taxonomy');



/**
 * Register Package Tag Taxonomies
 *
 * @return void
 */

function reg_themeum_travelkit_package_tag_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'Package Tags', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'Package Tag', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search Package Tag', 'themeum-core' ),
		'all_items'         	=> __( 'All Package Tag', 'themeum-core' ),
		'parent_item'       	=> __( 'Package Parent Tag', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'Package Parent Tag:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit Package Tag', 'themeum-core' ),
		'update_item'       	=> __( 'Update Package Tag', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New Package Tag', 'themeum-core' ),
		'new_item_name'     	=> __( 'New Package Tag Name', 'themeum-core' ),
		'menu_name'         	=> __( 'Package Tag', 'themeum-core' )
		);

	$args = array(
		'hierarchical'      	=> false,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('package-tag',array( 'package' ),$args);
}

add_action('init','reg_themeum_travelkit_package_tag_taxonomy');

