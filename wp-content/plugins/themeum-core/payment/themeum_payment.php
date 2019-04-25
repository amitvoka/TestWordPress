<?php
defined('ABSPATH') or die("No script kiddies please!");
add_action('init','paypal_payment');
function paypal_payment(){
	if (!is_admin()){
		wp_enqueue_script('jquery'); //jQuery add if it not exits.
		}
	}


//Plugin's SESSION Library
require_once plugin_dir_path( __FILE__ ).'lib/wp-session-manager.php';



//View Order Table Start
function add_lmsorder_order_columns($columns) {
		$total_column = array_slice( $columns, 0, 1, true ) + array(
						'themeum_product_name' =>__('Course Name','themeum-lms'),
						'themeum_order_course_id' =>__('Product ID','themeum-lms'),
						'themeum_order_user_id' =>__('User ID','themeum-lms'),
	                    'themeum_order_price' =>__('Price','themeum-lms'),
	                    'themeum_payment_id' =>__('Payment ID','themeum-lms'),
	                    'themeum_status_all' =>__('Order Status','themeum-lms'),
	                    ) + array_slice( $columns, 3, NULL, true );
	    return $total_column;
	}
	add_filter('manage_lmsorder_posts_columns' , 'add_lmsorder_order_columns');



// Set Value to Column
function custom_lmsorder_order_column( $column ) {
		global $post;
	    switch ( $column ) {
	      case 'themeum_order_user_id':
	        echo esc_attr(get_post_meta( $post->ID , 'themeum_order_user_id' , true ));
	        break;

	      case 'themeum_order_course_id':
	        echo '<a href="'.get_edit_post_link( $post->ID ).'">'.esc_html(get_post_meta( $post->ID , 'themeum_order_course_id' , true )).'</a>'; 
	        break;

	      case 'themeum_product_name':
	      	echo esc_html(get_post_meta( $post->ID , 'themeum_product_name' , true )); 
	      	break;

	      case 'themeum_order_price':
	      	$currency_array = array('AUD' => '$','BRL' => 'R$','CAD' => '$','CZK' => 'Kč','DKK' => 'kr.','EUR' => '€','HKD' => 'HK$','HUF' => 'Ft','ILS' => '₪','JPY' => '¥','MYR' => 'RM','MXN' => 'Mex$','NOK' => 'kr','NZD' => '$','PHP' => '₱','PLN' => 'zł','GBP' => '£','RUB' => '₽','SGD' => '$','SEK' => 'kr','CHF' => 'CHF','TWD' => '角','THB' => '฿','TRY' => 'TRY','USD' => '$');
			$symbol = '';
			$currency_type = get_option('paypal_curreny_code');
			if (array_key_exists( $currency_type , $currency_array)) {
			    $symbol = $currency_array[$currency_type];
			}else{
				 $symbol = '$';
			}
	        echo $symbol.''.esc_html(get_post_meta( $post->ID , 'themeum_order_price' , true )); 
	        break;

	      case 'themeum_status_all':
	      	echo esc_html(get_post_meta( $post->ID , 'themeum_status_all' , true ));
	      	break;

	      case 'themeum_payment_id':
	        echo esc_html(get_post_meta( $post->ID , 'themeum_payment_id' , true )); 
	        break;
	    }
	}
	add_action( 'manage_lmsorder_posts_custom_column' , 'custom_lmsorder_order_column' );


// Column Rearrange 
function lmsorder_sortable_columns( $columns ) {
	    $columns['themeum_order_user_id'] = __('User ID','themeum-lms');
	    $columns['themeum_order_course_id'] = __('Product ID','themeum-lms');
	    $columns['themeum_order_price'] = __('Price','themeum-lms');
	    $columns['themeum_payment_id'] = __('Payment ID','themeum-lms');
	    $columns['themeum_status_all'] = __('themeum_status_all','themeum-lms');
	    return $columns;
	}
	add_filter( 'manage_edit-lmsorder_sortable_columns', 'lmsorder_sortable_columns' );



	//Plugin's Checkout Page Session Manager
	$components = parse_url(get_option('siteurl'));
	if(isset($_POST['product_id']) && ($_SERVER['SERVER_NAME']==$components['host'])){

		global $wp_session;
		wp_session_start();

		$new_order = '';
		$order_remaining = 0;
		$array_var = $wp_session['cart_items'];

			if(isset($wp_session['cart_items'])){
				if(is_array($wp_session['cart_items'])){
					foreach ($wp_session['cart_items'] as $value) {
							if( $value['product_id'] == $_POST['product_id']){
								$order_remaining = 1;
							} 
						}
					if( $order_remaining == 0 ){
						$new_order = array(
								'product_id'	=> $_POST['product_id'],
								'product_name'	=> $_POST['product_name'],
								'user_id'		=> $_POST['user_id'],
								'email'			=> $_POST['email'],
								'price'			=> $_POST['price']
								);
						$array_var[] = $new_order;
					}
				}
				else{
					$new_order = array(
							'product_id'	=> $_POST['product_id'],
							'product_name'	=> $_POST['product_name'],
							'user_id'		=> $_POST['user_id'],
							'email'			=> $_POST['email'],
							'price'			=> $_POST['price']
							);
					$array_var[] = $new_order;
				}
			}
			else{
				$new_order = array(
						'product_id'	=> $_POST['product_id'],
						'product_name'	=> $_POST['product_name'],
						'user_id'		=> $_POST['user_id'],
						'email'			=> $_POST['email'],
						'price'			=> $_POST['price']
						);
				$array_var[] = $new_order;
			}
			$wp_session['cart_items'] = $array_var;
	}



	// Product remove  test when call remonve all button
	if(isset($_POST['remove_all']) && ($_SERVER['SERVER_NAME']==$components['host'])){
		global $wp_session;
		wp_session_start();
		wp_session_unset();
	}


	// Product remove  test when call remonve One button
	if(isset($_POST['remove_one']) && ($_SERVER['SERVER_NAME'] == $components['host'])){
		global $wp_session;
		wp_session_start();
		if(isset($wp_session['cart_items'])){
			if(is_array($wp_session['cart_items'])){
				$i = 0;
				$temp_array = '';
				foreach ($wp_session['cart_items'] as $value) {
						if( $value['product_id'] == esc_attr($_POST['remove_one'])){
						}else{
							$temp_array[] = $wp_session['cart_items'][$i];
						}
					$i++;
					}
					$wp_session['cart_items'] = $temp_array;
				}
			}

	}

	

	//Plugin's buynow
	add_filter( 'the_content', 'insert_post_buynow' );

	function insert_post_buynow( $content ) {
			 if( get_the_ID() == get_option("paypal_payment_checkout_page_id") ){
					// For Checkout page Start Here...
					// Checkout Table Generator
					global $wp_session;
					$html_table = "";
					if($wp_session['cart_items'] != "" ){
					
					$currency_array = array('AUD' => '$','BRL' => 'R$','CAD' => '$','CZK' => 'Kč','DKK' => 'kr.','EUR' => '€','HKD' => 'HK$','HUF' => 'Ft','ILS' => '₪','JPY' => '¥','MYR' => 'RM','MXN' => 'Mex$','NOK' => 'kr','NZD' => '$','PHP' => '₱','PLN' => 'zł','GBP' => '£','RUB' => '₽','SGD' => '$','SEK' => 'kr','CHF' => 'CHF','TWD' => '角','THB' => '฿','TRY' => 'TRY','USD' => '$');
					$symbol = '';
					$currency_type = get_option('paypal_curreny_code');
					if (array_key_exists( $currency_type , $currency_array)) {
					    $symbol = $currency_array[$currency_type];
					}else{
						 $symbol = '$';
					}
					

						$html_table = "<table><tr><th>".__('Product Name','themeum-lms')."</th><th>".__('Price','themeum-lms')."</th><th class='text-right'>".__('Remove','themeum-lms')."</th></tr>";
							foreach ($wp_session['cart_items'] as $value) {
								$html_table .= "<td>".esc_html($value['product_name'])."</td>";
								$html_table .= "<td>".esc_html($symbol.$value['price'])."</td>";
								$html_table .= '<td class="text-right removebtn"><form action="" method="post" ><input type="hidden" name="remove_one" value="'.esc_html($value['product_id']).'"><button type="submit" class="btn btn-danger">'.__("Remove","themeum-lms").'</button></form></td></tr>';
							}
						$html_table .= "</table>";
						}

			// Checkout Address Form
			$html_address = "";

			$type_of_payment_url = "";
			if(get_option('paypal_mode') == "real" ){
				$type_of_payment_url = "https://www.paypal.com/cgi-bin/webscr";
			}
			elseif(get_option('paypal_mode') == "developer" ) {
				$type_of_payment_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
			}


		$html_extra = '
			<input type="hidden" name="cmd" value="_cart">
			<input type="hidden" name="upload" value="1">
			<input type="hidden" name="business" value="'.sanitize_email(get_option("paypal_email_address")).'">
			';

		if( $wp_session['cart_items'] != '' ){
				$i = 1;
				foreach ($wp_session['cart_items'] as $value) {
					$html_extra .= '
					<div id = "item_'.$i.'" class = "itemwrap">
					<input type="hidden" name="item_name_'.$i.'" value="'.esc_html($value["product_name"]).'">
					<input type="hidden" name="item_number_'.$i.'" value="'.esc_html($value["product_id"]).'">
					<input type="hidden" name="quantity_'.$i.'" value="1">
					<input type="hidden" name="amount_'.$i.'" value="'.esc_html($value["price"]).'">
					</div>
					';
					$i++;
				}
			}

		$notify_url_link =  plugin_dir_url( __FILE__ ).'wp-remote-receiver.php';
		


		$continue_button = $remove_cart_all = $payment_option = $continue_button_logout = '';
		if ( is_user_logged_in() ) { 
			if( $wp_session['cart_items'] != '' ){
				if(get_option('enable_paypal_payment')==1){
					$continue_button = '<input class="pull-right continue-btn" type="submit" value="'.__('Continue','themeum-lms').'">';
				}else{
					$continue_button = '<div class="col-md-6 text-center pull-right alert alert-danger" role="alert"><strong>'.__('Warning!','themeum-lms').'</strong> '.__('Please Enable PayPal standard for Checkout.','themeum-lms').'</div>';
				}
				
				$remove_cart_all = '<form action="" method="post" ><input type="hidden" name="remove_all" value="xpcsxxxy"><button type="submit" class="pull-left btn btn-danger">'.__('Remove All','themeum-lms').'</button></form>';
				
				$payment_option = '<div class="col-md-12 payment-option"><h5>'.__('Payment option','themeum-lms').':</h5><span><input type="radio" name="payment-method" value="" checked="checked"></span> Paypal</div>';
				}
			}
		else{
			$continue_button_logout = '<h4>'.__('Login First To Checkout.','themeum-lms').'</h4>';
			ob_start();
			$continue_button_logout .= '<div class="checkup-login clearfix">'. do_shortcode('[custom_login]'). '</div>';
			}
		
		$html_address .= '
						<form action="'.$type_of_payment_url.'" method="post" >
							'.$html_extra.'
								<input type="hidden" name="currency_code" value="'.esc_html(get_option("paypal_curreny_code")).'">
								<input type="hidden" name="custom" value="'.esc_html(get_current_user_id()).'">
								<input type="hidden" name="invoice" value="'.time().rand( 1000 , 9999 ).'">
								<input type="hidden" name="notify_url" value="'.esc_url($notify_url_link).'"/>
								<input type="hidden" name="return" value="'.esc_url(get_option("payment_success_page")).'"/>
								<input type="hidden" name="cancel_return" value="'.esc_url(get_option("payment_cancel_page")).'"/>
								'.$continue_button.'
						</form>
						';
					return $content.$html_table.$remove_cart_all.$payment_option.$html_address.$continue_button_logout;
				 }
			 else{
				return $content;
			 }
		
	}