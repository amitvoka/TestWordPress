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
 * Register post type Hotel
 *
 * @return void
 */

function reg_themeum_travelkit_hotel_post_type()
{
	$labels = array( 
		'name'                	=> _x( 'Hotels', 'Hotels', 'themeum-core' ),
		'singular_name'       	=> _x( 'Hotel', 'Hotel', 'themeum-core' ),
		'menu_name'           	=> __( 'Hotels', 'themeum-core' ),
		'parent_item_colon'   	=> __( 'Parent Hotel:', 'themeum-core' ),
		'all_items'           	=> __( 'All Hotels', 'themeum-core' ),
		'view_item'           	=> __( 'View Hotel', 'themeum-core' ),
		'add_new_item'        	=> __( 'Add New Hotel', 'themeum-core' ),
		'add_new'             	=> __( 'New Hotel', 'themeum-core' ),
		'edit_item'           	=> __( 'Edit Hotel', 'themeum-core' ),
		'update_item'         	=> __( 'Update Hotel', 'themeum-core' ),
		'search_items'        	=> __( 'Search Hotel', 'themeum-core' ),
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
		'menu_icon'				=> 'dashicons-location-alt',
		'supports'           	=> array( 'title','editor','thumbnail'),
		);

	register_post_type('hotel',$args);

}

add_action('init','reg_themeum_travelkit_hotel_post_type');


/**
 * View Message When Updated Hotels
 *
 * @param array $messages Existing post update messages.
 * @return array
 */

function themeum_travelkit_hotel_update_message_course( $messages )
{
	global $post, $post_ID;

	$message['hotel'] = array(
		0 => '',
		1 => sprintf( __('Hotel updated. <a href="%s">View Hotel</a>', 'themeum-core' ), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.', 'themeum-core' ),
		3 => __('Custom field deleted.', 'themeum-core' ),
		4 => __('Hotel updated.', 'themeum-core' ),
		5 => isset($_GET['revision']) ? sprintf( __('Hotel restored to revision from %s', 'themeum-core' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Hotel published. <a href="%s">View Hotel</a>', 'themeum-core' ), esc_url( get_permalink($post_ID) ) ),
		7 => __('Hotel saved.', 'themeum-core' ),
		8 => sprintf( __('Hotel submitted. <a target="_blank" href="%s">Preview Hotel</a>', 'themeum-core' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Hotel scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Hotel</a>', 'themeum-core' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Hotel draft updated. <a target="_blank" href="%s">Preview Hotel</a>', 'themeum-core' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

return $message;
}

add_filter( 'post_updated_messages', 'themeum_travelkit_hotel_update_message_course' );


/**
 * Register Hotel Category Taxonomies
 *
 * @return void
 */

function reg_themeum_travelkit_hotel_cat_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'Hotel Categories', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'Hotel Category', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search Hotel Category', 'themeum-core' ),
		'all_items'         	=> __( 'All Hotel Category', 'themeum-core' ),
		'parent_item'       	=> __( 'Hotel Parent Category', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'Hotel Parent Category:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit Hotel Category', 'themeum-core' ),
		'update_item'       	=> __( 'Update Hotel Category', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New Hotel Category', 'themeum-core' ),
		'new_item_name'     	=> __( 'New Hotel Category Name', 'themeum-core' ),
		'menu_name'         	=> __( 'Hotel Category', 'themeum-core' )
		);

	$args = array(
		'hierarchical'      	=> true,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('hotel-category',array( 'hotel' ),$args);
}

add_action('init','reg_themeum_travelkit_hotel_cat_taxonomy');


/**
 * Register Hotel Category Taxonomies
 *
 * @return void
 */

function reg_themeum_travelkit_hotel_location_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'Hotel Locations', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'Hotel Location', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search Hotel Location', 'themeum-core' ),
		'all_items'         	=> __( 'All Hotel Location', 'themeum-core' ),
		'parent_item'       	=> __( 'Hotel Parent Location', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'Hotel Parent Location:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit Hotel Location', 'themeum-core' ),
		'update_item'       	=> __( 'Update Hotel Location', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New Hotel Location', 'themeum-core' ),
		'new_item_name'     	=> __( 'New Hotel Location Name', 'themeum-core' ),
		'menu_name'         	=> __( 'Hotel Location', 'themeum-core' )
		);

	$args = array(
		'hierarchical'      	=> true,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('hotel-location',array( 'hotel' ),$args);
}

add_action('init','reg_themeum_travelkit_hotel_location_taxonomy');



/**
 * Register Hotel Tag Taxonomies
 *
 * @return void
 */

function reg_themeum_travelkit_hotel_tag_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'Hotel Tags', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'Hotel Tag', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search Hotel Tag', 'themeum-core' ),
		'all_items'         	=> __( 'All Hotel Tag', 'themeum-core' ),
		'parent_item'       	=> __( 'Hotel Parent Tag', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'Hotel Parent Tag:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit Hotel Tag', 'themeum-core' ),
		'update_item'       	=> __( 'Update Hotel Tag', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New Hotel Tag', 'themeum-core' ),
		'new_item_name'     	=> __( 'New Hotel Tag Name', 'themeum-core' ),
		'menu_name'         	=> __( 'Hotel Tag', 'themeum-core' )
		);

	$args = array(
		'hierarchical'      	=> false,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('hotel-tag',array( 'hotel' ),$args);
}

add_action('init','reg_themeum_travelkit_hotel_tag_taxonomy');

