<?php
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
$wp_load = $parse_uri[0].'wp-load.php'; // Total WP Load Code.
require_once($wp_load);

// Check if paypal request or response
if (isset($_POST["txn_id"]) && isset($_POST["txn_type"])){
  
  // Response from Paypal
  header('HTTP/1.1 200 OK');

  //read the post from PayPal system and add 'cmd'
  $req = 'cmd=_notify-validate';
  foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $req .= "&$key=$value";
        }


  //SplmsHelper::testIpn($order_item_name, $item_number);
  //post back to PayPal system to validate (replaces old headers)
  $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
  $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
  $header .= "Host: www.paypal.com\r\n";
  $header .= "Connection: close\r\n";
  $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

  // Open a socket for the acknowledgement request
  if ( 'developer' == get_option('paypal_mode') ) {
      $fp = fsockopen('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
  }elseif( 'real' == get_option('paypal_mode') ){
      $fp = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30);
  }


  // Send the HTTP POST request back to PayPal for validation
  fputs($fp, $header . $req);

  

      
  //error connecting to paypal
  //if (!$fp) {  }
  //
  

  //successful connection    
  if ($fp) {
      fputs ($fp, $header . $req);
      
      while (!feof($fp)) {
          $res = fgets ($fp, 1024);
          
          $res = trim($res);              
          if (strcmp($res, "VERIFIED") == 0) {
           
          // check whether the payment_status is Completed
          require_once plugin_dir_path( __FILE__ ).'lib/wp-session-manager.php';
					global $wp_session;
          wp_session_start();
          wp_session_unset();                  

          // Insert Into Database Start
          $i = 0;
  				$j = 1;
  				global $wpdb;
  				global $current_user;			  

          while($i == 0){
                  if(isset($_POST['item_number'.$j])){

                        


                        $invoice = explode('_', $_POST['invoice']);

                        $order_page = array(
                            'post_title'    => $_POST['item_name'.$j].' #'.$_POST['item_number'.$j],
                            'post_content'  => '',
                            'post_status'   => 'publish',
                            'post_author'   => 1,
                            'post_type'     => (isset($invoice[0]) && $invoice[0] == 'vehicle') ? 'thm_vcl_order' : 'thmorder',
                          );

                        // Insert the post into the database                                 
                        $post_id = wp_insert_post( $order_page, $wp_error );
                        add_post_meta( $post_id , 'themeum_product_name', esc_attr($_POST['item_name'.$j]));
                        add_post_meta( $post_id , 'themeum_order_id', time().rand( 1000 , 9999 ));
                        add_post_meta( $post_id , 'themeum_order_user_id', esc_attr($_POST['custom']));
                        add_post_meta( $post_id , 'themeum_order_course_id', esc_attr($_POST['item_number'.$j]));
                        add_post_meta( $post_id , 'themeum_order_price', $_POST['mc_gross_'.$j] );
                        add_post_meta( $post_id , 'themeum_payment_id', esc_attr($_POST['txn_id'] ));
                        add_post_meta( $post_id , 'themeum_payment_method', 'paypal' );
                        add_post_meta( $post_id , 'themeum_order_created', date("Y m d h:i",time()) );
                        add_post_meta( $post_id , 'themeum_status_all', 'complete' );              

                        if (isset($invoice[0]) && $invoice[0] == 'package'){
                          $pkg_avl = get_post_meta( $_POST['item_number'.$j], 'themeum_packavailability', true );
                          $pkg_avl = (int) $pkg_avl;
                          update_post_meta( $_POST['item_number'.$j], 'themeum_packavailability', $pkg_avl - 1 );
                        }

                        if (isset($invoice[0]) && $invoice[0] == 'vehicle' && isset($invoice[1])) {
                          update_post_meta( $invoice[1], 'themeum_schedule_booked', '1' );
                        }

                        $j++;   
                  }else{
                        $i = 1;
                  }
            } //endWhile

          // SplmsHelper::successOrder($order_info);
        }
      if (strcmp ($res, "INVALID") == 0) {
            //Invalid Request
          }
      } // endWhile
    fclose($fp);
  }
}

