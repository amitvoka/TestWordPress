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
 * Register post type Vehicle
 *
 * @return void
 */

function reg_themeum_travelkit_vehicle_post_type()
{
	$labels = array( 
		'name'                	=> _x( 'Vehicles', 'Vehicles', 'themeum-core' ),
		'singular_name'       	=> _x( 'Vehicle', 'Vehicle', 'themeum-core' ),
		'menu_name'           	=> __( 'Vehicles', 'themeum-core' ),
		'parent_item_colon'   	=> __( 'Parent Vehicle:', 'themeum-core' ),
		'all_items'           	=> __( 'All Vehicles', 'themeum-core' ),
		'view_item'           	=> __( 'View Vehicle', 'themeum-core' ),
		'add_new_item'        	=> __( 'Add New Vehicle', 'themeum-core' ),
		'add_new'             	=> __( 'New Vehicle', 'themeum-core' ),
		'edit_item'           	=> __( 'Edit Vehicle', 'themeum-core' ),
		'update_item'         	=> __( 'Update Vehicle', 'themeum-core' ),
		'search_items'        	=> __( 'Search Vehicle', 'themeum-core' ),
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
		'menu_icon'				=> 'dashicons-sos',
		'supports'           	=> array( 'title','editor','thumbnail'),
		);

	register_post_type('vehicle',$args);


	$labels = array( 
		'name'                	=> _x( 'Schedules', 'Schedules', 'themeum-core' ),
		'singular_name'       	=> _x( 'Schedule', 'Schedule', 'themeum-core' ),
		'menu_name'           	=> __( 'Schedules', 'themeum-core' ),
		'parent_item_colon'   	=> __( 'Parent Schedule:', 'themeum-core' ),
		'all_items'           	=> __( 'All Schedules', 'themeum-core' ),
		'view_item'           	=> __( 'View Schedule', 'themeum-core' ),
		'add_new_item'        	=> __( 'Add New Schedule', 'themeum-core' ),
		'add_new'             	=> __( 'New Schedule', 'themeum-core' ),
		'edit_item'           	=> __( 'Edit Schedule', 'themeum-core' ),
		'update_item'         	=> __( 'Update Schedule', 'themeum-core' ),
		'search_items'        	=> __( 'Search Schedule', 'themeum-core' ),
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
		'menu_icon'				=> 'dashicons-sos',
		'supports'           	=> array( 'title' ),
		);

	register_post_type('schedules',$args);

}

add_action('init','reg_themeum_travelkit_vehicle_post_type');


/**
 * View Message When Updated Vehicles
 *
 * @param array $messages Existing post update messages.
 * @return array
 */

function themeum_travelkit_vehicle_update_message_course( $messages )
{
	global $post, $post_ID;

	$message['vehicle'] = array(
		0 => '',
		1 => sprintf( __('Vehicle updated. <a href="%s">View Vehicle</a>', 'themeum-core' ), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.', 'themeum-core' ),
		3 => __('Custom field deleted.', 'themeum-core' ),
		4 => __('Vehicle updated.', 'themeum-core' ),
		5 => isset($_GET['revision']) ? sprintf( __('Vehicle restored to revision from %s', 'themeum-core' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Vehicle published. <a href="%s">View Vehicle</a>', 'themeum-core' ), esc_url( get_permalink($post_ID) ) ),
		7 => __('Vehicle saved.', 'themeum-core' ),
		8 => sprintf( __('Vehicle submitted. <a target="_blank" href="%s">Preview Vehicle</a>', 'themeum-core' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Vehicle scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Vehicle</a>', 'themeum-core' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Vehicle draft updated. <a target="_blank" href="%s">Preview Vehicle</a>', 'themeum-core' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

		return $message;
}

add_filter( 'post_updated_messages', 'themeum_travelkit_vehicle_update_message_course' );


/**
 * Register Vehicle Category Taxonomies
 *
 * @return void
 */

function reg_themeum_travelkit_vehicle_cat_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'Vehicle Categories', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'Vehicle Category', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search Vehicle Category', 'themeum-core' ),
		'all_items'         	=> __( 'All Vehicle Category', 'themeum-core' ),
		'parent_item'       	=> __( 'Vehicle Parent Category', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'Vehicle Parent Category:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit Vehicle Category', 'themeum-core' ),
		'update_item'       	=> __( 'Update Vehicle Category', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New Vehicle Category', 'themeum-core' ),
		'new_item_name'     	=> __( 'New Vehicle Category Name', 'themeum-core' ),
		'menu_name'         	=> __( 'Vehicle Category', 'themeum-core' )
	);

	$args = array(
		'hierarchical'      	=> true,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('vehicle-category',array( 'vehicle' ),$args);
}

add_action('init','reg_themeum_travelkit_vehicle_cat_taxonomy');


/**
 * Register vehicle Category Taxonomies
 *
 * @return void
 */

function reg_themeum_travelkit_vehicle_location_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'vehicle Locations', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'vehicle Location', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search vehicle Location', 'themeum-core' ),
		'all_items'         	=> __( 'All vehicle Location', 'themeum-core' ),
		'parent_item'       	=> __( 'vehicle Parent Location', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'vehicle Parent Location:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit vehicle Location', 'themeum-core' ),
		'update_item'       	=> __( 'Update vehicle Location', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New vehicle Location', 'themeum-core' ),
		'new_item_name'     	=> __( 'New vehicle Location Name', 'themeum-core' ),
		'menu_name'         	=> __( 'vehicle Location', 'themeum-core' )
		);

	$args = array(
		'hierarchical'      	=> true,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('vehicle-location',array( 'vehicle' ),$args);
}

add_action('init','reg_themeum_travelkit_vehicle_location_taxonomy');

/**
 * Register Vehicle Tag Taxonomies
 *
 * @return void
 */

function reg_themeum_travelkit_vehicle_tag_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'Vehicle Tags', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'Vehicle Tag', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search Vehicle Tag', 'themeum-core' ),
		'all_items'         	=> __( 'All Vehicle Tag', 'themeum-core' ),
		'parent_item'       	=> __( 'Vehicle Parent Tag', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'Vehicle Parent Tag:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit Vehicle Tag', 'themeum-core' ),
		'update_item'       	=> __( 'Update Vehicle Tag', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New Vehicle Tag', 'themeum-core' ),
		'new_item_name'     	=> __( 'New Vehicle Tag Name', 'themeum-core' ),
		'menu_name'         	=> __( 'Vehicle Tag', 'themeum-core' )
		);

	$args = array(
		'hierarchical'      	=> false,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('vehicle-tag',array( 'vehicle' ),$args);
}

add_action('init','reg_themeum_travelkit_vehicle_tag_taxonomy');


function reg_themeum_travelkit_pickup_location_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'Pick Locations', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'Pick Location', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search Pick Location', 'themeum-core' ),
		'all_items'         	=> __( 'All Pick Location', 'themeum-core' ),
		'parent_item'       	=> __( 'Pick Parent Location', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'Pick Parent Location:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit Pick Location', 'themeum-core' ),
		'update_item'       	=> __( 'Update Pick Location', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New Pick Location', 'themeum-core' ),
		'new_item_name'     	=> __( 'New Pick Location Name', 'themeum-core' ),
		'menu_name'         	=> __( 'Pick Location', 'themeum-core' )
		);

	$args = array(
		'hierarchical'      	=> true,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('pickup-location',array( 'schedules' ),$args);
}

add_action('init','reg_themeum_travelkit_pickup_location_taxonomy');

function reg_themeum_travelkit_drop_location_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'Drop Locations', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'Pick Location', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search Drop Location', 'themeum-core' ),
		'all_items'         	=> __( 'All Drop Location', 'themeum-core' ),
		'parent_item'       	=> __( 'Drop Parent Location', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'Drop Parent Location:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit Drop Location', 'themeum-core' ),
		'update_item'       	=> __( 'Update Drop Location', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New Drop Location', 'themeum-core' ),
		'new_item_name'     	=> __( 'New Drop Location Name', 'themeum-core' ),
		'menu_name'         	=> __( 'Drop Location', 'themeum-core' )
		);

	$args = array(
		'hierarchical'      	=> true,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('drop-location',array( 'schedules' ),$args);
}

add_action('init','reg_themeum_travelkit_drop_location_taxonomy');