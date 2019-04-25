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
 * Register post type Room Type
 *
 * @return void
 */

function reg_themeum_travelkit_room_post_type()
{
	$labels = array( 
		'name'                	=> _x( 'Hotel Rooms', 'Hotel Rooms', 'themeum-core' ),
		'singular_name'       	=> _x( 'Hotel Room', 'Hotel Room', 'themeum-core' ),
		'menu_name'           	=> __( 'Hotel Rooms', 'themeum-core' ),
		'parent_item_colon'   	=> __( 'Parent Hotel Room:', 'themeum-core' ),
		'all_items'           	=> __( 'All Hotel Rooms', 'themeum-core' ),
		'view_item'           	=> __( 'View Hotel Room', 'themeum-core' ),
		'add_new_item'        	=> __( 'Add New Hotel Room', 'themeum-core' ),
		'add_new'             	=> __( 'New Hotel Room', 'themeum-core' ),
		'edit_item'           	=> __( 'Edit Hotel Room', 'themeum-core' ),
		'update_item'         	=> __( 'Update Hotel Room', 'themeum-core' ),
		'search_items'        	=> __( 'Search Hotel Room', 'themeum-core' ),
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
		'menu_icon'				=> 'dashicons-admin-home',
		'supports'           	=> array( 'title','editor','thumbnail'),
		);

	register_post_type('room',$args);

}

add_action('init','reg_themeum_travelkit_room_post_type');


/**
 * View Message When Updated Hotel Rooms
 *
 * @param array $messages Existing post update messages.
 * @return array
 */

function themeum_travelkit_room_update_message_course( $messages )
{
	global $post, $post_ID;

	$message['room'] = array(
		0 => '',
		1 => sprintf( __('Hotel Room updated. <a href="%s">View Hotel Room</a>', 'themeum-core' ), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.', 'themeum-core' ),
		3 => __('Custom field deleted.', 'themeum-core' ),
		4 => __('Hotel Room updated.', 'themeum-core' ),
		5 => isset($_GET['revision']) ? sprintf( __('Hotel Room restored to revision from %s', 'themeum-core' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Hotel Room published. <a href="%s">View Hotel Room</a>', 'themeum-core' ), esc_url( get_permalink($post_ID) ) ),
		7 => __('Hotel Room saved.', 'themeum-core' ),
		8 => sprintf( __('Hotel Room submitted. <a target="_blank" href="%s">Preview Hotel Room</a>', 'themeum-core' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Hotel Room scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Hotel Room</a>', 'themeum-core' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Hotel Room draft updated. <a target="_blank" href="%s">Preview Hotel Room</a>', 'themeum-core' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

return $message;
}

add_filter( 'post_updated_messages', 'themeum_travelkit_room_update_message_course' );

/**
 * Register Room Category Taxonomies
 *
 * @return void
 */

function reg_themeum_travelkit_room_cat_taxonomy()
{
	$labels = array(
		'name'              	=> _x( 'Room Categories', 'taxonomy general name', 'themeum-core' ),
		'singular_name'     	=> _x( 'Room Category', 'taxonomy singular name', 'themeum-core' ),
		'search_items'      	=> __( 'Search Room Category', 'themeum-core' ),
		'all_items'         	=> __( 'All Room Category', 'themeum-core' ),
		'parent_item'       	=> __( 'Room Parent Category', 'themeum-core' ),
		'parent_item_colon' 	=> __( 'Room Parent Category:', 'themeum-core' ),
		'edit_item'         	=> __( 'Edit Room Category', 'themeum-core' ),
		'update_item'       	=> __( 'Update Room Category', 'themeum-core' ),
		'add_new_item'      	=> __( 'Add New Room Category', 'themeum-core' ),
		'new_item_name'     	=> __( 'New Room Category Name', 'themeum-core' ),
		'menu_name'         	=> __( 'Room Category', 'themeum-core' )
		);

	$args = array(
		'hierarchical'      	=> true,
		'labels'            	=> $labels,
		'show_in_nav_menus' 	=> true,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true
		);

	register_taxonomy('room-category',array( 'room' ),$args);
}

add_action('init','reg_themeum_travelkit_room_cat_taxonomy');





