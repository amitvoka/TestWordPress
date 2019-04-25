<?php
add_shortcode( 'themeum_person', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'person_img' 			=> '',
		'person_name' 			=> '',
		'person_deg'			=> '',
		'person_description'	=> '',
		'facebook_url' 			=> '',
		'twitter_url' 			=> '',
		'gplus_url' 			=> '',
		'linkedin_url' 			=> '',
		'pinterest_url' 		=> '',
		'delicious_url' 		=> '',
		'tumblr_url' 			=> '',
		'stumbleupon_url' 		=> '',
		'dribble_url' 			=> '',
		'class' 				=> ''
		), $atts));


		$output  	 = '<div class="themeum-person '.esc_attr($class).'">';
			$output  	.= '<div class="themeum-person-image">';
				$src_image   = wp_get_attachment_image_src($person_img, 'full');
				$output  	.= '<img src="' . esc_url($src_image[0]). '" class="img-responsive" alt="photo">';
			$output  	.= '</div>';//themeum-person-image

			$output  	.= '<div class="person-details">';
				if ($person_name) $output 			.= '<h3 class="person-title">' . esc_attr($person_name) . '</h3>';
				if ($person_deg) $output 			.= '<h4 class="person-deg">' . esc_attr($person_deg) . '</h4>';
				if ($person_description) $output 	.= '<p class="person-description">' . esc_attr($person_description) . '</p>';
			$output  	.= '</div>';//person-details

			$output  	.= '<div class="social-icon">';
				$output  	.= '<ul class="list-unstyled list-inline">';
				if(isset($facebook_url) && !empty($facebook_url)) {
				$output 	.= '<li><a class="facebook" href="' . esc_url($facebook_url) . '" target="_blank"><i class="fa fa-facebook"></i></a></li>';
				}

				if(isset($twitter_url) && !empty($twitter_url)) {
				$output 	.= '<li><a class="twitter" href="' . esc_url($twitter_url) . '" target="_blank" ><i class="fa fa-twitter"></i></a></li>';
				}	

				if(isset($gplus_url) && !empty($gplus_url)) {
				$output 	.= '<li><a class="g-plus" href="' . esc_url($gplus_url) . '" target="_blank"><i class="fa fa-google-plus"></i></a></li>';
				}	

				if(isset($linkedin_url) && !empty($linkedin_url)) {
				$output 	.= '<li><a class="linkedin" href="' . esc_url($linkedin_url) . '" target="_blank"><i class="fa fa-linkedin"></i></a></li>';
				}

				if(isset($pinterest_url) && !empty($pinterest_url)) {
				$output 	.= '<li><a class="pinterest" href="' . esc_url($pinterest_url) . '" target="_blank"><i class="fa fa-pinterest"></i></a></li>';
				}	

				if(isset($delicious_url) && !empty($delicious_url)) {
				$output 	.= '<li><a class="delicious" href="' . esc_url($delicious_url) . '" target="_blank"><i class="fa fa-delicious"></i></a></li>';
				}	

				if(isset($tumblr_url) && !empty($tumblr_url)) {
				$output 	.= '<li><a class="tumblr" href="' . esc_url($tumblr_url) . '" target="_blank"><i class="fa fa-tumblr"></i></a></li>';
				}	

				if(isset($stumbleupon_url) && !empty($stumbleupon_url)) {
				$output 	.= '<li><a class="stumbleupon" href="' . esc_url($stumbleupon_url) . '" target="_blank"><i class="fa fa-stumbleupon"></i></a></li>';
				}	

				if(isset($dribble_url) && !empty($dribble_url)) {
				$output 	.= '<li><a class="dribble" href="' . esc_url($dribble_url) . '" target="_blank"><i class="fa fa-dribbble"></i></a></li>';
				}
				$output  	.= '</ul>';
			$output  	.= '</div>';//social-icon
		$output  	 .= '</div>';//themeum-person

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
	vc_map(array(
		"name" 			=> esc_html__("Person", 'themeum-core'),
		"base" 			=> "themeum_person",
		'icon' 			=> 'icon-thm-person',
		"class" 		=> "",
		"description" 	=> esc_html__("Widget Title Heading", 'themeum-core'),
		"category" 		=> esc_html__('Travelkit', 'themeum-core'),
		"params" => array(		

			array(
				"type" 			=> "attach_image",
				"heading" 		=> esc_html__("Upload Person Image", 'themeum-core'),
				"param_name" 	=> "person_img",
				"value" 		=> "",
				),			

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Person Name", 'themeum-core'),
				"param_name" 	=> "person_name",
				"value" 		=> "",
				),

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Team Designation", 'themeum-core'),
				"param_name" 	=> "person_deg",
				"value" 		=> "",
				),

			array(
				"type" 			=> "textarea",
				"heading" 		=> esc_html__("Person Description Text", 'themeum-core'),
				"param_name" 	=> "person_description",
				"value" 		=> "",
			),			

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Facebook URL", 'themeum-core'),
				"param_name" 	=> "facebook_url",
				"value" 		=> "",
				),			

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Twitter URL", 'themeum-core'),
				"param_name" 	=> "twitter_url",
				"value" 		=> "",
				),

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Google Plus URL", 'themeum-core'),
				"param_name" 	=> "gplus_url",
				"value" 		=> "",
				),			

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Linkedin URL", 'themeum-core'),
				"param_name" 	=> "linkedin_url",
				"value" 		=> "",
				),			

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Pinterest URL", 'themeum-core'),
				"param_name" 	=> "pinterest_url",
				"value" 		=> "",
				),			

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Delicious URL", 'themeum-core'),
				"param_name" 	=> "delicious_url",
				"value" 		=> "",
				),

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Tumblr URL", 'themeum-core'),
				"param_name" 	=> "tumblr_url",
				"value" 		=> "",
				),			

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Stumbleupon URL", 'themeum-core'),
				"param_name" 	=> "stumbleupon_url",
				"value" 		=> "",
				),

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Dribble URL", 'themeum-core'),
				"param_name" 	=> "dribble_url",
				"value" 		=> "",
				),	

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Class", 'themeum-core'),
				"param_name" 	=> "class",
				"value" 		=> ""
				),					

			)
		));
}