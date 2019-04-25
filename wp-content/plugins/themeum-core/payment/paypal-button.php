<?php
/**
** A base module for [submit]
**/

/* Shortcode handler */

add_action( 'wpcf7_init', 'themeum_add_shortcode_paypal_btn' );

function themeum_add_shortcode_paypal_btn() {
	wpcf7_add_form_tag( 'paypal', 'themeum_paypal_shortcode_handler' );
	wpcf7_add_form_tag( 'stripe', 'themeum_stripe_shortcode_handler' );
	wpcf7_add_form_tag( array('package', 'package*'), 'themeum_package_shortcode_handler', true );
}

function themeum_paypal_shortcode_handler( $tag ) {
	$tag = new WPCF7_FormTag( $tag );
	

	$atts = array();

	$atts['class'] = $tag->get_class_option( 'buybtnemail btn btn-warning btn-lg btn-block' );
	$atts['id'] = "paypal-buy-btn";

	$value = isset( $tag->values[0] ) ? $tag->values[0] : '';

	if ( empty( $value ) ){
		$value = __( 'Check Out Via PayPal', 'themeum-core' );
	}

	$atts['type'] = 'button';
	$atts['value'] = $value;


	$atts = wpcf7_format_atts( $atts );
	


	$html = sprintf( '<input %1$s />', $atts );

	return $html;
}

function themeum_stripe_shortcode_handler( $tag ) {
	$tag = new WPCF7_FormTag( $tag );
	

	$atts = array();

	$atts['class'] = $tag->get_class_option( 'stripebtn btn btn-warning btn-lg btn-block' );
	$atts['id'] = "stripe-buy-btn";

	$value = isset( $tag->values[0] ) ? $tag->values[0] : '';

	if ( empty( $value ) ){
		$value = __( 'Check Out Via PayPal', 'themeum-core' );
	}

	$atts['type'] = 'button';
	$atts['value'] = $value;


	$atts = wpcf7_format_atts( $atts );
	


	$html = sprintf( '<input %1$s />', $atts );

	return $html;
}

function themeum_package_shortcode_handler( $tag ) {
	$tag = new WPCF7_FormTag( $tag );
	

	$atts = array();

	$value_array = array(
		'id' => get_the_id(),
		'name' => get_the_title(get_the_id()),
		'url' => get_the_permalink(get_the_id()),
	);

	$post_id = get_the_id();
	$post_name = get_the_title(get_the_id());
	$post_link = get_the_permalink(get_the_id());

	$value = "ID: #{$post_id}\nName: {$post_name}\nLink: {$post_link}\n";

	$atts['type'] = 'hidden';
	$atts['value'] = $value;

	$atts['name'] = $tag->name;


	$atts = wpcf7_format_atts( $atts );
	


	$html = sprintf( '<input %1$s />', $atts );

	return $html;
}

/* Tag generator */

add_action( 'wpcf7_admin_init', 'themeum_add_tag_generator_paypal', 55 );

function themeum_add_tag_generator_paypal() {
	$tag_generator = WPCF7_TagGenerator::get_instance();
	$tag_generator->add( 'paypal', __( 'paypal', 'themeum-core' ), 'themeum_tag_generator_paypal', array( 'nameless' => 1, 'valueless' => 1 ) );
	$tag_generator->add( 'stripe', __( 'stripe', 'themeum-core' ), 'themeum_tag_generator_stripe', array( 'nameless' => 1, 'valueless' => 1 ) );
	$tag_generator->add( 'package', __( 'package', 'themeum-core' ), 'themeum_tag_generator_package', array( 'valueless' => 1, 'classless' => 1, 'idless' => 1 ) );
}

function themeum_tag_generator_package( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );

?>
<div class="control-box">
<fieldset>

<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="name" class="oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
	</tr>

</tbody>
</table>
</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="package" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>
</div>
<?php
}

function themeum_tag_generator_paypal( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );

?>
<div class="control-box">
<fieldset>

<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Label', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
	</tr>

</tbody>
</table>
</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="paypal" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>
</div>
<?php
}

function themeum_tag_generator_stripe( $contact_form, $args = '' ) {
	$args = wp_parse_args( $args, array() );

?>
<div class="control-box">
<fieldset>

<table class="form-table">
<tbody>
	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>"><?php echo esc_html( __( 'Label', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="values" class="oneline" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
	</tr>

	<tr>
	<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
	<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
	</tr>

</tbody>
</table>
</fieldset>
</div>

<div class="insert-box">
	<input type="text" name="stripe" class="tag code" readonly="readonly" onfocus="this.select()" />

	<div class="submitbox">
	<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
	</div>
</div>
<?php
}


function thm_paypal_form()
{

	global $post;

	if (empty($post)) {
		return;
	}

	$notify_url_link =  plugin_dir_url( __FILE__ ).'wp-remote-receiver.php';



	$type_of_payment_url = "https://www.paypal.com/cgi-bin/webscr";
	if(get_option('paypal_mode') == "real" ){
		$type_of_payment_url = "https://www.paypal.com/cgi-bin/webscr";
	} elseif(get_option('paypal_mode') == "developer" ) {
		$type_of_payment_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	}

	$price = get_post_meta( $post->ID, "themeum_packprice", true );


	?>
	<form action="<?php echo $type_of_payment_url; ?>" method="post" id="paypal-order-form" >
		<input type="hidden" name="cmd" value="_cart">
		<input type="hidden" name="upload" value="1">
		<input type="hidden" name="business" value="<?php echo sanitize_email(get_option("paypal_email_address")); ?>">
		<div id = "item_1" class = "itemwrap">
			<input type="hidden" name="item_name_1" value="<?php echo esc_html($post->post_title); ?>">
			<input type="hidden" name="item_number_1" value="<?php echo esc_html($post->ID); ?>">
			<input type="hidden" name="quantity_1" value="1">
			<input type="hidden" name="amount_1" value="<?php echo esc_html( $price ); ?>">
		</div>
		<input type="hidden" name="currency_code" value="<?php echo esc_html(get_option("paypal_curreny_code")); ?>">
		
		<input type="hidden" name="invoice" value="package_<?php echo time().rand( 1000 , 9999 ); ?>">
		<input type="hidden" name="notify_url" value="<?php echo esc_url($notify_url_link); ?>"/>
		<input type="hidden" name="return" value="<?php echo esc_url(get_option("payment_success_page")); ?>"/>
		<input type="hidden" name="rm" value="2"/>
		<input type="hidden" name="cancel_return" value="<?php echo esc_url(get_option("payment_cancel_page")); ?>"/>
	</form>
	<?php


	add_action( 'wp_footer', function(){
		global $post;

		$price = get_post_meta( $post->ID, "themeum_packprice", true );

		$price = ($price * 100);

	    echo '<script type="text/javascript">
	      function stripePaymentForm(){
	        StripeCheckout.open({
	          key: "'. esc_html(get_option('publish_secret_key')) .'",
	          address: false,
	          amount: '. esc_html( $price ) .',
	          currency: "'. esc_html(get_option('paypal_curreny_code')) .'",
	          name: "'. esc_html(get_option('stripe_site_name')) .'",
	          image: "'. esc_url(get_option('stripe_logo')) .'",
	          description: "'. esc_html(get_option('stripe_desc')) .'",
	          panelLabel: "Book Now",
	          token: function(response) {
	          	var paymentData = {
	          		amount: '. esc_html( $price ) .',
	          		item_name: "'.esc_html($post->post_title).'",
	          		item_id: "'.esc_html($post->ID).'",
	          		email: response.email,
	          		token: response.id,
	          		type: "package"
	          	};

	          	submitPaymentForm(paymentData);
	          }
	        });
	      }
	    </script>';
	  }, 100 );
}

function thm_paypal_form_vehicle()
{
	// var_dump($_GET);

	global $post;

	if (empty($post) || !isset($_GET['booking'])) {
		return;
	}


	$sedual_query_args = array(
        'post_type' => 'schedules',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key'     => 'themeum_schedule_booked',
                'value'   => '0',
                'compare' => '=',
            ),
            array(
                'key'     => 'themeum_schedule_pickup_time',
                'value'   => $_GET['pickuptime'],
                'compare' => '=',
                'type' => 'DATETIME'
            ),
            array(
                'key'     => 'themeum_schedule_car',
                'value'   => $post->ID,
                'compare' => '=',
            ),
        ),
    );

    $schedules = get_posts($sedual_query_args);

    if (empty($schedules)) {
		return;
	}

	$notify_url_link =  plugin_dir_url( __FILE__ ).'wp-remote-receiver.php';



	$type_of_payment_url = "https://www.paypal.com/cgi-bin/webscr";
	if(get_option('paypal_mode') == "real" ){
		$type_of_payment_url = "https://www.paypal.com/cgi-bin/webscr";
	} elseif(get_option('paypal_mode') == "developer" ) {
		$type_of_payment_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	}

	$price = get_post_meta( $post->ID, "themeum_vehicleprice", true );


	?>
	<form action="<?php echo $type_of_payment_url; ?>" method="post" id="paypal-order-form" >
		<input type="hidden" name="cmd" value="_cart">
		<input type="hidden" name="upload" value="1">
		<input type="hidden" name="business" value="<?php echo sanitize_email(get_option("paypal_email_address")); ?>">
		<div id = "item_1" class = "itemwrap">
			<input type="hidden" name="item_name_1" value="<?php echo esc_html($post->post_title); ?>">
			<input type="hidden" name="item_number_1" value="<?php echo esc_html($post->ID); ?>">
			<input type="hidden" name="quantity_1" value="1">
			<input type="hidden" name="amount_1" value="<?php echo esc_html( $price * $_GET['droptime']); ?>">
		</div>
		<input type="hidden" name="currency_code" value="<?php echo esc_html(get_option("paypal_curreny_code")); ?>">
		
		<input type="hidden" name="invoice" value="vehicle_<?php echo esc_attr($schedules[0]->ID); ?>_<?php echo time().rand( 1000 , 9999 ); ?>">
		<input type="hidden" name="notify_url" value="<?php echo esc_url($notify_url_link); ?>"/>
		<input type="hidden" name="return" value="<?php echo esc_url(get_option("payment_success_page")); ?>"/>
		<input type="hidden" name="rm" value="2"/>
		<input type="hidden" name="cancel_return" value="<?php echo esc_url(get_option("payment_cancel_page")); ?>"/>
	</form>
	<?php

	add_action( 'wp_footer', function(){
		global $post;

		$price = get_post_meta( $post->ID, "themeum_vehicleprice", true );

		$price = $price * $_GET['droptime'];

		$price = ($price * 100);


		$sedual_query_args = array(
	        'post_type' => 'schedules',
	        'posts_per_page' => -1,
	        'meta_query' => array(
	            'relation' => 'AND',
	            array(
	                'key'     => 'themeum_schedule_booked',
	                'value'   => '0',
	                'compare' => '=',
	            ),
	            array(
	                'key'     => 'themeum_schedule_pickup_time',
	                'value'   => $_GET['pickuptime'],
	                'compare' => '=',
	                'type' => 'DATETIME'
	            ),
	            array(
	                'key'     => 'themeum_schedule_car',
	                'value'   => $post->ID,
	                'compare' => '=',
	            ),
	        ),
	    );

	    $schedules = get_posts($sedual_query_args);

	    echo '<script type="text/javascript">
	      function stripePaymentForm(){
	        StripeCheckout.open({
	          key: "'. esc_html(get_option('publish_secret_key')) .'",
	          address: false,
	          amount: '. esc_html( $price ) .',
	          currency: "'. esc_html(get_option('paypal_curreny_code')) .'",
	          name: "'. esc_html(get_option('stripe_site_name')) .'",
	          image: "'. esc_url(get_option('stripe_logo')) .'",
	          description: "'. esc_html(get_option('stripe_desc')) .'",
	          panelLabel: "Book Now",
	          token: function(response) {
	          	var paymentData = {
	          		amount: '. esc_html( $price ) .',
	          		item_name: "'.esc_html($post->post_title).'",
	          		item_id: "'.esc_html($post->ID).'",
	          		email: response.email,
	          		token: response.id,
	          		type: "vehicle",
	          		schedule: '.$schedules[0]->ID.'
	          	};

	          	submitPaymentForm(paymentData);
	          }
	        });
	      }
	    </script>';
	  }, 100 );
}


add_action('wp_ajax_strip_payment_submit','strip_payment_submit');
add_action('wp_ajax_nopriv_strip_payment_submit','strip_payment_submit');

function strip_payment_submit()
{
	$report = array(
		'status' => false,
		'msg'   => 'There is an error in processing, try again later'
	);

	$api_key = esc_html(get_option('stripe_secret_key'));

	$data = $_POST['data'];


	$invoice = (isset($data['type']) && $data['type'] == 'vehicle') ? 'vehicle_'.$data['schedule'].'_'.time().rand( 1000 , 9999 ) : 'package_'.time().rand( 1000 , 9999 ) ;

	\Stripe\Stripe::setApiKey($api_key); // intiatlize stripe api

	$stripeEmail  = sanitize_email( $data['email'] ); // useremail
	$token        = esc_html( $data['token'] ); // card token by stripe

	$new_customer = \Stripe\Customer::create( array(
		'email' => $stripeEmail,
		'card'  => $token
	));

	try {
		$charge = \Stripe\Charge::create( array(
			'amount'      => $data['amount'], // amount in cents
			'currency'    => esc_html(get_option('paypal_curreny_code')),
			'customer'    => $new_customer['id'],
			'description' => $data['item_name'],
			'metadata'    => array(
				'item_name'   => esc_html($data['item_name']),
				'item_id'     => esc_html($data['item_id']),
				'invoice'	  => $invoice,
			)
		));

		$report = array(
			'status'  => true,
			'msg'     => 'success',
			'redirect'  => esc_url(get_option("payment_success_page"))
		);

		echo json_encode($report);die();
	} catch (Exception $e) {
		$e = $e->getJsonBody();
		echo json_encode($report);die();
	}
}
