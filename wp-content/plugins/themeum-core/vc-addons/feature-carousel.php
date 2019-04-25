<?php
if(!class_exists('ThemeumFeatureCarousel'))
{
	class ThemeumFeatureCarousel
	{

		function __construct()
		{
			add_action('init', array($this, 'add_themeum_feature_carousel'));
			add_shortcode( 'themeum_feature_carousel_wrap', array($this, 'themeum_feature_carousel_wrap' ) );
			add_shortcode( 'themeum_feature_carousel_item', array($this, 'themeum_feature_carousel_item' ) );
		}

		function themeum_feature_carousel_wrap($atts, $content = null)
		{
			$class 				= '';
			extract(shortcode_atts(array(
				'class' 			=> '',
			), $atts));
			$output = '<div id="themeum-feature-carosuel" class="adons-themeum-feature-carosuel '.esc_attr($class).'">';
				$output  .= '<div class="themeum-feature-carosuel owl-carousel owl-theme">';
						$output .= do_shortcode($content);
				$output .= '</div>';//.themeum-feature-carosuel
			$output .= '</div>'; //.addon-themeum-feature-carosuel
			return $output;
		}

		function themeum_feature_carousel_item($atts,$content = null)
		{
			$image 				= '';
			$link_url 			= '';
			$title 				= '';
			$subtitle 			= '';

			extract(shortcode_atts(array(
				'image' 			=> '',
				'link_url' 			=> '',
				'title' 			=> '',
				'subtitle' 			=> '',
				'duration_cost' 			=> '',
				'some_info' 			=> '',
				'btn_link' 				=> '',
				'btn_name'		 		=> '',
				'target'		 		=> '_blank',
				'btntype'				=> '',
				'btnsize'				=> '',

			), $atts));

			$output = '';
			$src_image   = wp_get_attachment_image_src($image, 'full');

            $output   .= '<div class="item thm-feature-carosuel text-center">';
            	if ($link_url != '') {
					if ($src_image) {
	                    $output  .= '<a href="'.$link_url.'"><img src="'.esc_url($src_image[0]).'" alt="photo"></a>';
	                }
            	} else {
            		$output  .= '<img src="'.esc_url($src_image[0]).'" alt="photo">';
            	}

							$output  .= '<div class="thm-feature-carosuel-info">';
            	if ($link_url != '') {
            		if($title) {
	            		$output  .= '<h3 class="feature-carosuel-title"><a href="'.$link_url.'">'.$title.'</a></h3>';
	            	}
            	} else {
            		if($title) {
	            		$output  .= '<h3 class="feature-carosuel-title">'.$title.'</h3>';
	            	}
            	}
            	if($subtitle) {
            		$output  .= '<p class="subtitle">'.$subtitle.'</p>';
            	}
							if($duration_cost) {
            		$output  .= '<p class="duration">'.$duration_cost.'</p>';
            	}
							if($some_info) {
            		$output  .= '<p class="info">'.$some_info.'</p>';
            	}
							if($btn_link) {
            		$output .=  '<a class="btn btn-'. $btntype .' '. $btnsize .'" href="'.esc_url($btn_link).'" target="'.esc_attr($target).'">'.esc_attr($btn_name).'</a>';
            	}

			$output  .= '</div>';//thm-feature-carosuel-info
			$output  .= '</div>';//text-center

			return $output;
		}

		// Shortcode Functions for frontend editor
		function front_themeum_feature_carousel_wrap($atts, $content = null)
		{
			// Do nothing
				$class 				= '';
			extract(shortcode_atts(array(
				'class' 			=> '',
			), $atts));

			$output = '<div id="themeum-feature-carosuel" class="adons-themeum-feature-carosuel '.esc_attr($class).'">';
				$output  .= '<div class="themeum-feature-carosuel owl-carousel owl-theme">';
						$output .= do_shortcode($content);
				$output .= '</div>';//.themeum-feature-carosuel
			$output .= '</div>'; //.addon-themeum-feature-carosuel


			return $output;
		}
		function front_themeum_feature_carousel_item($atts,$content = null)
		{
			// Do nothing
			$image 				= '';
			$link_url 			= '';
			$title 				= '';
			$subtitle 			= '';
			extract(shortcode_atts(array(
				'image' 			=> '',
				'link_url' 			=> '',
				'title' 			=> '',
				'subtitle' 			=> '',
			), $atts));

			$src_image   = wp_get_attachment_image_src($image, 'full');


	        $output   = '<div class="item thm-feature-carosuel">';
            	if ($link_url != '') {
					if ($src_image) {
	                    $output  .= '<a href="'.$link_url.'"><img src="'.esc_url($src_image[0]).'" alt="photo"></a>';
	                }
            	} else {
            		$output  .= '<img src="'.esc_url($src_image[0]).'" alt="photo">';
            	}
            	if ($link_url != '') {
            		if($title) {
	            		$output  .= '<h3 class="feature-carosuel-title"><a href="'.$link_url.'">'.$title.'</a></h3>';
	            	}
            	} else {
            		if($title) {
	            		$output  .= '<h3 class="feature-carosuel-title">'.$title.'</h3>';
	            	}
            	}
            	if($subtitle) {
            		$output  .= '<p>'.$subtitle.'</p>';
            	}
							if($duration_cost) {
            		$output  .= '<p>'.$duration_cost.'</p>';
            	}
							if($some_info) {
            		$output  .= '<p>'.$some_info.'</p>';
            	}
							if($btn_link) {
            		$output .=  '<a class="btn btn-'. $btntype .'" href="'.esc_url($btn_link).'" target="'.esc_attr($target).'">'.esc_attr($btn_name).'</a>';
            	}
				$output .= wpb_js_remove_wpautop($content, true);
			$output  .= '</div>';//item

			return $output;
		}
		function add_themeum_feature_carousel()
		{
			if(function_exists('vc_map'))
			{
				vc_map(
				array(
				   "name" => __("Themeum Feature","themeum-core"),
				   "base" => "themeum_feature_carousel_wrap",
				   "class" => "",
				   "icon" => "icon-feature-wrap",
				   "category" => "Travelkit",
				   "as_parent" => array('only' => 'themeum_feature_carousel_item'),
				   "description" => __("Text blocks connected together in one list.","themeum-core"),
				   "content_element" => true,
				   "show_settings_on_create" => true,
				   "params" => array(

						array(
							"type" => "textfield",
							"class" => "",
							"heading" => __("Add Custom Class","themeum-core"),
							"param_name" => "class",
							"description" => __("Add Custom Class","themeum-core")
						),

					),
					"js_view" => 'VcColumnView'
				));
				// Add Feature Item
				vc_map(
					array(
					   "name" => __("Feature Item","themeum-core"),
					   "base" => "themeum_feature_carousel_item",
					   "class" => "",
					   "icon" => "icon-feature-list",
					   "category" => "Travelkit",
					   "content_element" => true,
					   "as_child" => array('only' => 'themeum_feature_carousel_wrap'),
					   "is_container"    => false,
					   "params" => array(

							array(
								"type" => "textfield",
								"class" => "",
								"heading" => __("Title","themeum-core"),
								"param_name" => "title",
								"description" => __("Add Title ","themeum-core")
							),
							array(
								"type" => "textfield",
								"class" => "",
								"heading" => __("Link","themeum-core"),
								"param_name" => "link_url",
								"description" => __("Add Partner Link ","themeum-core")
							),

							array(
								"type" => "textfield",
								"class" => "",
								"heading" => __("Sub Title","themeum-core"),
								"param_name" => "subtitle",
								"description" => __("Add Sub Title ","themeum-core")
							),

							array(
								"type" => "textfield",
								"class" => "",
								"heading" => __("Duration & Cost","themeum-core"),
								"param_name" => "duration_cost",
								"description" => __("Add Duration & Cost","themeum-core")
							),
							array(
								"type" => "textarea",
								"class" => "",
								"heading" => __("Some information","themeum-core"),
								"param_name" => "some_info",
								"description" => __("Add Some info ","themeum-core")
							),
							array(
								"type" => "textfield",
								"heading" => esc_html__("Link URL", 'themeum-core'),
								"param_name" => "btn_link",
								"value" => "",
								),

							array(
								"type" => "textfield",
								"heading" => esc_html__("Button Name", 'themeum-core'),
								"param_name" => "btn_name",
								"value" => "",
								),

							array(
								"type" => "dropdown",
								"heading" => esc_html__("Target Link", 'themeum-core'),
								"param_name" => "target",
								"value" => array('Self'=>'_self','Blank'=>'_blank','Parent'=>'_parent'),
								),
							array(
								"type" => "dropdown",
								"heading" => esc_html__("Button Type", 'themeum-core'),
								"param_name" => "btntype",
								"value" => array('Select'=>'','Default'=>'default', 'Primary'=>'primary','Success'=>'success','Warning'=>'warning','Danger'=>'danger'),
							),

							array(
								"type" => "dropdown",
								"heading" => esc_html__("Button Size", 'themeum-core'),
								"param_name" => "btnsize",
								"value" => array('Select'=>'','Extra large'=>'btn-lg','Small'=>'btn-sm','Extra Small'=>'btn-xs'),
							),
							array(
								"type" => "attach_image",
								"heading" => esc_html__("Upload Image", 'themeum-core'),
								"param_name" => "image",
								"value" => "",
							),
					   )
					)
				);
			}//endif
		}
	}
}
global $ThemeumFeatureCarousel;
if(class_exists('WPBakeryShortCodesContainer'))
{
	class WPBakeryShortCode_themeum_feature_carousel_wrap extends WPBakeryShortCodesContainer {
        function content( $atts, $content = null ) {
            global $ThemeumFeatureCarousel;
            return $ThemeumFeatureCarousel->front_themeum_feature_carousel_wrap($atts, $content);
        }
	}
	class WPBakeryShortCode_themeum_feature_carousel_item extends WPBakeryShortCode {
        function content($atts, $content = null ) {
            global $ThemeumFeatureCarousel;
            return $ThemeumFeatureCarousel->front_themeum_feature_carousel_item($atts, $content);
        }
	}
}
if(class_exists('ThemeumFeatureCarousel'))
{
	$ThemeumFeatureCarousel = new ThemeumFeatureCarousel;
}
