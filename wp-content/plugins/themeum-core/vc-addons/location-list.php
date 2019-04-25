<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Performer Scroller
add_shortcode( 'location_list_shortcode', function($atts, $content = null){

	extract(shortcode_atts(array(
		'counts'		=> '',
		'columns'		=> '6',
		'class'		=> ''
		), $atts));


	global $post;

	$i=1;

	$output = '<div class="package-location-shortcode ' . esc_attr($class) .'">';

		$filters = get_terms('package-location');

		$coun_item = 1;
		$filters = array_values($filters);

		foreach ($filters as $key=>$filter)
		{
			if ($coun_item<=esc_attr($counts)) {
					$term_link = get_term_link( $filter );
					$img = get_option('package-location_custom_order_'.$filter->term_taxonomy_id);
				if($img != '') {
					if($i==1) {
						$output .= '<div class="row">';
					}

					$output .= '<div class="col-sm-3 package-location col-xs-4 col-md-'.$columns.'">';
						$output .= '<a href="'.$term_link.'"><img class="img-responsive" src="'.esc_url($img).'" alt="photo"></a>';
						$output .= '<h3 class="feature-carosuel-title"><a href="'.$term_link.'">'.$filter->name.'</a></h3>';
						$output .= '<div>'.$filter->description.'</div>';
					$output .= '</div>';


					if( $i == (12/$columns) ) {
						$output .= '</div>';//row
						$i=1;
					} else {
						$i++;
					}
				}
				$coun_item++;
			}
		}
		if($i !=  1 ){
			$output .= '</div>'; //row
		}

	$output .= '</div>';

	return $output;
});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
	vc_map(array(
		"name" => __("Package location", "themeum-core"),
		"base" => "location_list_shortcode",
		'icon' => 'icon-wpb-package-location',
		"class" => "",
		"description" => "Package location",
		"category" => __("Travelkit", "themeum-core"),
		"params" => array(

			array(
				"type" => "textfield",
				"heading" => __("Count", "themeum-core"),
				"param_name" => "counts",
				"description" => __("Enter the number of performers you want to display.", "themeum-core"),
				"value" => 6,
				),
			array(
				"type" => "dropdown",
				"heading" => esc_html__("Number Of Column", "themeum-core"),
				"param_name" => "columns",
				"value" => array('Select'=>'','column 2'=>'6','column 3'=>'4','column 4'=>'3','column 6'=>'2'),
				),
			array(
				"type" => "textfield",
				"heading" => __("Extra CSS Class", "themeum-core"),
				"param_name" => "class",
				"value" => "",
				"description" => "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file."
				),

			)

));
}
