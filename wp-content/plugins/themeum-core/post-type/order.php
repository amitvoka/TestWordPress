<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Admin functions for the Event post type
 *
 * @author 		Themeum
 * @category 	Admin
 * @package 	Varsity
 * @version     1.0
 *-------------------------------------------------------------*/

/**
 * Register post type Order
 *
 * @return void
 */

function themeum_post_type_order()
{
	$labels = array( 
		'name'                	=> _x( 'Orders', 'Orders', 'themeum-lms' ),
		'singular_name'       	=> _x( 'Order', 'Order', 'themeum-lms' ),
		'menu_name'           	=> __( 'Orders', 'themeum-lms' ),
		'parent_item_colon'   	=> __( 'Parent Order:', 'themeum-lms' ),
		'all_items'           	=> __( 'All Order', 'themeum-lms' ),
		'view_item'           	=> __( 'View Order', 'themeum-lms' ),
		'add_new_item'        	=> __( 'Add New Order', 'themeum-lms' ),
		'add_new'             	=> __( 'New Order', 'themeum-lms' ),
		'edit_item'           	=> __( 'Edit Order', 'themeum-lms' ),
		'update_item'         	=> __( 'Update Order', 'themeum-lms' ),
		'search_items'        	=> __( 'Search Order', 'themeum-lms' ),
		'not_found'           	=> __( 'No article found', 'themeum-lms' ),
		'not_found_in_trash'  	=> __( 'No article found in Trash', 'themeum-lms' )
		);

	$args = array(  
		'labels'             	=> $labels,
		'public'             	=> true,
		'publicly_queryable' 	=> true,
		'show_in_menu'       	=> 'edit.php?post_type=thmorder',
		'show_in_admin_bar'   	=> true,
		'can_export'          	=> true,
		'has_archive'        	=> false,
		'hierarchical'       	=> false,
		'menu_position'      	=> null,
		'menu_icon'				=> true,
		'supports'           	=> array('comments')
		);

	register_post_type('thmorder',$args);

	$args['show_in_menu'] = 'edit.php?post_type=thm_vcl_order';

	register_post_type('thm_vcl_order',$args);

}

add_action('init','themeum_post_type_order');


/**
 * View Message When Updated Project
 *
 * @param array $messages Existing post update messages.
 * @return array
 */

function themeum_update_message_order( $messages )
{
	global $post, $post_ID;

	$message['order'] = array(
		0 => '',
		1 => sprintf( __('Order updated. <a href="%s">View Order</a>', 'themeum-lms' ), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.', 'themeum-lms' ),
		3 => __('Custom field deleted.', 'themeum-lms' ),
		4 => __('Order updated.', 'themeum-lms' ),
		5 => isset($_GET['revision']) ? sprintf( __('Order restored to revision from %s', 'themeum-lms' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Order published. <a href="%s">View Order</a>', 'themeum-lms' ), esc_url( get_permalink($post_ID) ) ),
		7 => __('Order saved.', 'themeum-lms' ),
		8 => sprintf( __('Order submitted. <a target="_blank" href="%s">Preview Order</a>', 'themeum-lms' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Order scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Order</a>', 'themeum-lms' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Order draft updated. <a target="_blank" href="%s">Preview Order</a>', 'themeum-lms' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

return $message;
}

add_filter( 'post_updated_messages', 'themeum_update_message_order' );



