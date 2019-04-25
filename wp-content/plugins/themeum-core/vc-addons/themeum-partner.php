<?php
if(!class_exists('ThemeumPartnerCarousel'))
{
	class ThemeumPartnerCarousel
	{
		
		function __construct()
		{	
			add_action('init', array($this, 'add_themeum_partner_carousel'));			
			add_shortcode( 'themeum_partner_carousel_wrap', array($this, 'themeum_partner_carousel_wrap' ) );
			add_shortcode( 'themeum_partner_carousel_item', array($this, 'themeum_partner_carousel_item' ) );
		}

		function themeum_partner_carousel_wrap($atts, $content = null)
		{
			$class 				= '';
			extract(shortcode_atts(array(
				'class' 			=> '',
			), $atts));
			$output = '<div id="themeum-partner-carosuel" class="adons-themeum-partner-carosuel '.esc_attr($class).'">';
				$output  .= '<div class="themeum-partner-carosuel owl-carousel owl-theme">';
						$output .= do_shortcode($content);
				$output .= '</div>';//.themeum-partner-carosuel
			$output .= '</div>'; //.addon-themeum-partner-carosuel
			return $output;
		}

		function themeum_partner_carousel_item($atts,$content = null)
		{
			$image 				= '';
			$img_url 			= '';

			extract(shortcode_atts(array(
				'image' 			=> '',
				'img_url' 			=> '',

			), $atts));
			
			$output = '';
			$src_image   = wp_get_attachment_image_src($image, 'full');
	
            $output   .= '<div class="item themeum-partner">';
            	if ($img_url != '') {
					if ($src_image) {
	                    $output  .= '<a href="'.$img_url.'"><img src="'.esc_url($src_image[0]).'" alt="partner"></a>';
	                }
            	} else {
            		$output  .= '<img src="'.esc_url($src_image[0]).'" alt="partner">';
            	}

			$output  .= '</div>';//text-center
				
			return $output;
		}
		
		// Shortcode Functions for frontend editor
		function front_themeum_partner_carousel_wrap($atts, $content = null)
		{
			// Do nothing
				$class 				= '';
			extract(shortcode_atts(array(
				'class' 			=> '',
			), $atts));

			$output = '<div id="themeum-partner-carosuel" class="adons-themeum-partner-carosuel '.esc_attr($class).'">';
				$output  .= '<div class="themeum-partner-carosuel owl-carousel owl-theme">';
						$output .= do_shortcode($content);
				$output .= '</div>';//.themeum-partner-carosuel
			$output .= '</div>'; //.addon-themeum-partner-carosuel


			return $output;
		}
		function front_themeum_partner_carousel_item($atts,$content = null)
		{
			// Do nothing
			$image 				= '';
			$img_url 			= '';
			extract(shortcode_atts(array(
				'image' 			=> '',
				'img_url' 			=> '',
			), $atts));
			
			$src_image   = wp_get_attachment_image_src($image, 'full');


	        $output   = '<div class="item themeum-partner">';
            	if ($img_url != '') {
					if ($src_image) {
	                    $output  .= '<a href="'.$img_url.'"><img src="'.esc_url($src_image[0]).'" alt="partner"></a>';
	                }
            	} else {
            		$output  .= '<img src="'.esc_url($src_image[0]).'" alt="partner">';
            	}
				$output .= wpb_js_remove_wpautop($content, true);
			$output  .= '</div>';//item

			return $output;
		}
		function add_themeum_partner_carousel()
		{
			if(function_exists('vc_map'))
			{
				vc_map(
				array(
				   "name" => __("Themeum Partner","themeum-core"),
				   "base" => "themeum_partner_carousel_wrap",
				   "class" => "",
				   "icon" => "icon-partner-wrap",
				   "category" => "Travelkit",
				   "as_parent" => array('only' => 'themeum_partner_carousel_item'),
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
				// Add partner Item
				vc_map(
					array(
					   "name" => __("Partner Item","themeum-core"),
					   "base" => "themeum_partner_carousel_item",
					   "class" => "",
					   "icon" => "icon-partner-list",
					   "category" => "Travelkit",
					   "content_element" => true,
					   "as_child" => array('only' => 'themeum_partner_carousel_wrap'),
					   "is_container"    => false,
					   "params" => array(
	
							array(
								"type" => "textfield",
								"class" => "",
								"heading" => __("Parter Link","themeum-core"),
								"param_name" => "img_url",
								"description" => __("Add Partner Link ","themeum-core")
							),						
							array(
								"type" => "attach_image",
								"heading" => esc_html__("Upload Slider Image", 'themeum-core'),
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
global $ThemeumPartnerCarousel;
if(class_exists('WPBakeryShortCodesContainer'))
{
	class WPBakeryShortCode_themeum_partner_carousel_wrap extends WPBakeryShortCodesContainer {
        function content( $atts, $content = null ) {
            global $ThemeumPartnerCarousel;
            return $ThemeumPartnerCarousel->front_themeum_partner_carousel_wrap($atts, $content);
        }
	}
	class WPBakeryShortCode_themeum_partner_carousel_item extends WPBakeryShortCode {
        function content($atts, $content = null ) {
            global $ThemeumPartnerCarousel;
            return $ThemeumPartnerCarousel->front_themeum_partner_carousel_item($atts, $content);
        }
	}
}
if(class_exists('ThemeumPartnerCarousel'))
{
	$ThemeumPartnerCarousel = new ThemeumPartnerCarousel;
}