<?php
add_shortcode( 'themeum_flight_result', function($atts, $content = null) {

	extract(shortcode_atts(array(
		), $atts));

	ob_start();
	?>
		<div class="thm-flight-results">
			<div class="alert thm-flight-alert alert-danger" role="alert"><?php esc_html_e('There is some error to get flight data. Please try again.', 'themeum-core'); ?></div>
			<div class="alert thm-flight-alert-no-result alert-danger" role="alert"><?php esc_html_e('Sorry, no result found.', 'themeum-core'); ?></div>
			<div class="flight-loader"><div class="loader"><?php esc_html_e( 'Loading...', 'themeum-core' ); ?></div> <?php esc_html_e( 'Please wait. It may take up to 2 mins.', 'themeum-core' ); ?></div>
			<h2 class="thm-titlestandardstyle"><span class="thm-flight-count"></span> <?php esc_html_e( 'Flights Found', 'themeum-core' ); ?></h2>
			<ul id="all-flights">
				<li data-pos="0"></li>
				<li data-pos="1"></li>
				<li data-pos="2"></li>
				<li data-pos="3"></li>
				<li data-pos="4"></li>
				<li data-pos="5"></li>
				<li data-pos="6"></li>
				<li data-pos="7"></li>
				<li data-pos="8"></li>
				<li data-pos="9"></li>
			</ul>
			<ul id="flight-pagination"></ul>
		</div>
	<?php

	return ob_get_clean();

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => esc_html__("Flight Result", 'themeum-core'),
	"base" => "themeum_flight_result",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => esc_html__("Widget Title Heading", 'themeum-core'),
	"category" => esc_html__('Travelkit', 'themeum-core'),
	"params" => array()
	));
}