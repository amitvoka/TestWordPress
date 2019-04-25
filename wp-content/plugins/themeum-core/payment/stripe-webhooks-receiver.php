<?php
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
$wp_load = $parse_uri[0].'wp-load.php'; // Total WP Load Code.
require_once($wp_load);

require_once plugin_dir_path( __FILE__ ).'vendor/autoload.php';

$api_key = esc_html(get_option('stripe_secret_key'));

\Stripe\Stripe::setApiKey($api_key);

// Retrieve the request's body and parse it as JSON
$input = @file_get_contents("php://input");
$event_json = json_decode($input);

$event_id = $event_json->id;

$event = \Stripe\Event::retrieve($event_id);
$event_object = $event->data->object;

if ($event->type == 'charge.succeeded')
{
    $event_object = $event->data->object;

    $amount = sprintf('%0.2f', $event_object->amount / 100.0); // amount
    $balance_transaction = $event_object->balance_transaction;
    $carged_id = $event_object->id;

    $event_metadata = $event_object->metadata;

    if(isset($event_metadata->invoice)){

        $payment_data = explode('_', $event_metadata->invoice);

        $order_page = array(
            'post_title'    => $event_metadata->item_name.' #'.$event_metadata->item_id,
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => (isset($payment_data[0]) && $payment_data[0] == 'vehicle') ? 'thm_vcl_order' : 'thmorder',
        );

        $post_id = wp_insert_post( $order_page );

        add_post_meta( $post_id , 'themeum_product_name', esc_attr($event_metadata->item_name));
        add_post_meta( $post_id , 'themeum_order_id', time().rand( 1000 , 9999 ));
        // add_post_meta( $post_id , 'themeum_order_user_id', esc_attr($_POST['custom']));
        add_post_meta( $post_id , 'themeum_order_course_id', esc_attr($event_metadata->item_id));
        add_post_meta( $post_id , 'themeum_order_price', $amount );
        add_post_meta( $post_id , 'themeum_payment_id', esc_attr($carged_id ));
        add_post_meta( $post_id , 'themeum_payment_method', 'stripe' );
        add_post_meta( $post_id , 'themeum_order_created', date("Y m d h:i",time()) );
        add_post_meta( $post_id , 'themeum_status_all', 'complete' );

        if (isset($payment_data[0]) && $payment_data[0] == 'package'){
          $pkg_avl = get_post_meta( $event_metadata->item_id, 'themeum_packavailability', true );
          $pkg_avl = (int) $pkg_avl;
          update_post_meta( $event_metadata->item_id, 'themeum_packavailability', $pkg_avl - 1 );
        }

        if (isset($payment_data[0]) && $payment_data[0] == 'vehicle' && isset($payment_data[1])) {
          update_post_meta( $payment_data[1], 'themeum_schedule_booked', '1' );
        }

    }
}