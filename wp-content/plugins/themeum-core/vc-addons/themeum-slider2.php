<?php
if(!class_exists('ThemeumSlider2Shortcode'))
{
	class ThemeumSlider2Shortcode
	{

		function __construct()
		{
			$active_counter = 0;
			add_action('init', array($this, 'add_themeum_slider2'));
			add_shortcode( 'themeum_slider2_wrap', array($this, 'themeum_slider2_wrap' ) );
			add_shortcode( 'themeum_slider2_item', array($this, 'themeum_slider2_item' ) );
		}

		function themeum_slider2_wrap($atts, $content = null)
		{
			$class 				= '';
			$time 				= '';
			$disable_slider 	= '';
			extract(shortcode_atts(array(
				'class' 			=> '',
				'time' 				=> '',
				'disable_slider' 	=> '',
			), $atts));
			if($disable_slider == 'enable'){
		        $time = 'false';
		    }else {
		    	$time = 'true';
		    }
			$randId = rand(10,100);
			$output  = '<div id="home-two-crousel'.$randId.'" class="home-two-crousel carousel carousel-fade slide ' . esc_attr($class) . '">';
				$output .= '<div class="carousel-inner">';
					$output .= do_shortcode($content);
				$output .= '</div>';//carousel-inner
                // Controls
                $output .= '<div class="slider-arrow-nav">';
	                $output .= '<a class="left carousel-control controller-prev" href="#home-two-crousel'.$randId.'" role="button" data-slide="prev">';
	                    $output .= '<i class="fa fa-angle-left"></i>';
	                $output .= '</a>';
	                $output .= '<a class="right carousel-control controller-next" href="#home-two-crousel'.$randId.'" role="button" data-slide="next">';
	                    $output .= '<i class="fa fa-angle-right"></i>';
	                $output .= '</a>';
				$output .= '</div>';//.slider-arrow-nav
			$output .= '</div>';//#home-two-crousel

			//JS time
    		$output .= "<script type='text/javascript'>jQuery(document).ready(function() { jQuery('#home-two-crousel".$randId."').carousel({ interval: ".$time." }) });</script>";
			return $output;
		}

		function themeum_slider2_item($atts,$content = null)
		{
			$slider_type = '';
			$title = '';
			$introtext = '';
			$subtitle = '';
			$image = '';
			$bgimage = '';
			$btn_url = '';
			$btn_text = '';
			extract(shortcode_atts(array(
				'slider_type'   => 'text_slide',
				'title'     	=> '',
				'subtitle'  	=> '',
				'introtext'  	=> '',
				'btn_url' 		=> '',
				'btn_text' 		=> '',
				'bgimage' 		=> '',
				'image' 		=> '',
			), $atts));

			$style = '';
			$output = '';
			$src_imagebg   = wp_get_attachment_image_src($bgimage, 'full');
			$src_image   = wp_get_attachment_image_src($image, 'full');
			if ($slider_type == 'image_slide') {
				$style = 'style="background: #333 url('.esc_url($src_imagebg[0]).'); background-size: cover; background-repeat: no-repeat;background-position: center center;"';
			} else {
				$style = 'style="overflow: hidden;backface-visibility: hidden;"';
			}

			//foreach ($title as $value) {
				global $active_counter;
				if( $active_counter == 0 ){
	            $output   .= '<div class="item active" '.$style.'>';
	            $active_counter = 2;
	            }else{
	            $output   .= '<div class="item" '.$style.'>';
	            }
					$output  .= '<div class="container">';
						$output  .= '<div class="slider-content">';
							$output  .= '<div class="vertical-middle">';
							if ($src_image) {
						    $output .= '<div class="slide-icon" data-animation="animated fadeInUp">';
				                $output  .= '<img src="'.esc_url($src_image[0]).'" alt="">';
					        $output  .= '</div>';
					        }
							if ($title) {
								$output .= '<h2 data-animation="animated fadeInUp">' . esc_attr($title) . '</h2>';
				            }
							if ($subtitle) {
								$output .= '<h3 data-animation="animated-two fadeInUp">' . $subtitle . '</h3>';
				            }
				            if ($introtext) {
								$output .= '<div class="slider-intro-text" data-animation="animated-three fadeInUp">' . $introtext . '</div>';
				            }
							if ($btn_url) {
								$output .= '<a class="btn  btn-transparent  btn-slider" data-animation="animated-four fadeInUp" href="' . esc_url($btn_url) . '" target="_blank">' . esc_attr($btn_text) . '</a>';
	            			}
							$output  .= '</div>';//vertical-middle
						$output  .= '</div>';//slider-content
					$output  .= '</div>';//container
				$output  .= '</div>';//item

			return $output;
		}

		// Shortcode Functions for frontend editor
		function front_themeum_slider2_wrap($atts, $content = null)
		{
			// Do nothing
			$class 				= '';
			$time 				= '';
			$disable_slider 	= '';
			extract(shortcode_atts(array(
				'class' 			=> '',
				'time' 				=> '',
				'disable_slider' 	=> '',
			), $atts));

			    if($disable_slider == 'enable'){
			        $time = 'false';
			    }
				$output  = '<div id="home-two-crousel" class="carousel carousel-fade slide ' . esc_attr($class) . '">';
					$output .= '<div class="carousel-inner">';
								$output .= do_shortcode($content);
					$output .= '</div>';//carousel-inner

	                // Controls
	                $output .= '<div class="slider-arrow-nav">';
		                $output .= '<a class="left carousel-control" href="#home-two-crousel" role="button" data-slide="prev">';
		                    $output .= '<i class="fa fa-angle-left"></i>';
		                $output .= '</a>';
		                $output .= '<a class="right carousel-control" href="#home-two-crousel" role="button" data-slide="next">';
		                    $output .= '<i class="fa fa-angle-right"></i>';
		                $output .= '</a>';
	                $output .= '</div>';//.slider-arrow-nav
				$output .= '</div>';//#home-two-crousel

				// JS time
    			$output .= "<script type='text/javascript'>jQuery(document).ready(function() { jQuery('#home-one-crousel".$randId."').carousel({ interval: ".$time." }) });</script>";

			return $output;
		}
		function front_themeum_slider2_item($atts,$content = null)
		{
			// Do nothing
			$title = '';
			$subtitle = '';
			$introtext = '';
			$image = '';
			$btn_url = '';
			$btn_text = '';
			extract(shortcode_atts(array(
				'title'     => '',
				'subtitle'  => '',
				'introtext'  => '',
				'btn_url' 	=> '',
				'btn_text' 	=> '',
				'image' 	=> '',
			), $atts));

			$style = '';
			$src_image   = wp_get_attachment_image_src($image, 'full');
			$style = 'style="background: #333 url('.esc_url($src_image[0]).') no-repeat center center cover; overflow: hidden;backface-visibility: hidden;padding: 154px 0 145px;"';

			$output = '<li class="icon_list_item">';
			$output   = '<div class="item" '.$style.'>';
						$output  .= '<div class="container">';
						$output  .= '<div class="slider-content">';
							$output  .= '<div class="vertical-middle">';
							if ($src_image) {
						    $output .= '<div class="slide-icon" data-animation="animated fadeInUp">';
				                $output  .= '<img src="'.esc_url($src_image[0]).'" alt="">';
					        $output  .= '</div>';
					        }
							if ($title) {
								$output .= '<h2 data-animation="animated fadeInUp">' . esc_attr($title) . '</h2>';
				            }
							if ($subtitle) {
								$output .= '<h3 data-animation="animated fadeInUp">' . $subtitle . '</h3>';
				            }
				            if ($introtext) {
								$output .= '<div class="slider-intro-text" data-animation="animated fadeInUp">' . $introtext . '</div>';
				            }
							if ($btn_url) {
								$output .= '<a class="bordered-button" data-animation="animated fadeInUp" href="' . esc_url($btn_url) . '" target="_blank">' . esc_attr($btn_text) . '</a>';
	            			}
							$output  .= '</div>';//vertical-middle
						$output  .= '</div>';//slider-content
						$output  .= '</div>';//container
				$output .= wpb_js_remove_wpautop($content, true);
			$output  .= '</div>';//item

			return $output;
		}
		function add_themeum_slider2()
		{
			if(function_exists('vc_map'))
			{
				vc_map(
				array(
				   "name" => __("Themeum Slider 2","themeum-core"),
				   "base" => "themeum_slider2_wrap",
				   "class" => "",
				   "icon" => "icon-slider-wrap",
				   "category" => "Travelkit",
				   "as_parent" => array('only' => 'themeum_slider2_item'),
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

			            array(
			                "type" => "checkbox",
			                "class" => "",
			                "heading" => esc_html__("Disable Auto Slide: ","themeum-core"),
			                "param_name" => "disable_slider",
			                "value" => array ( esc_html__('Disable','themeum-core') => 'enable'),
			                "description" => esc_html__("If you want disable slide check this.","themeum-core"),
			            ),

			            array(
			                "type" => "textfield",
			                "heading" => esc_html__("Sliding Time(Milliseconds Ex: 4000)", "themeum-core"),
			                "param_name" => "time",
			                "value" => "3000",
			                ),

					),
					"js_view" => 'VcColumnView'
				));
				// Add slider Item
				vc_map(
					array(
					   "name" => __("Themeum Slider2 Item","themeum-core"),
					   "base" => "themeum_slider2_item",
					   "class" => "",
					   "icon" => "icon-slider-list",
					   "category" => "Travelkit",
					   "content_element" => true,
					   "as_child" => array('only' => 'themeum_slider2_wrap'),
					   "is_container"    => false,
					   "params" => array(
					   		array(
								"type" => "dropdown",
								"heading" => esc_html__("Slider Type", 'themeum-core'),
								"param_name" => "slider_type",
								"value" => array('Select'=>'','Only Text Slide'=>'text_slide','Slide With Image '=>'image_slide'),
							),
							array(
								"type" => "textfield",
								"class" => "",
								"heading" => __("Title","themeum-core"),
								"param_name" => "title",
								"description" => __("Title","themeum-core")
							),
							array(
								"type" => "textarea",
								"class" => "",
								"heading" => __("Sub Title","themeum-core"),
								"param_name" => "subtitle",
								"description" => __("Sub Title","themeum-core")
							),
							array(
								"type" => "textarea",
								"class" => "",
								"heading" => __("Intro Text","themeum-core"),
								"param_name" => "introtext",
								"description" => __("Slider Intro Text","themeum-core")
							),
							array(
								"type" => "textfield",
								"class" => "",
								"heading" => __("Add Button URL","themeum-core"),
								"param_name" => "btn_url",
								"description" => __("Add URL","themeum-core")
							),
							array(
								"type" => "textfield",
								"class" => "",
								"heading" => __("Add Button Text","themeum-core"),
								"param_name" => "btn_text",
							),
							array(
								"type" => "attach_image",
								"heading" => esc_html__("Upload Icon Image", 'themeum-core'),
								"param_name" => "image",
								"value" => "",
							),
							array(
								"type" => "attach_image",
								"heading" => esc_html__("Upload Slider Background Image", 'themeum-core'),
								"param_name" => "bgimage",
								"value" => "",
								"dependency" => Array("element" => "slider_type", "value" => array("image_slide")),
							),
					   )
					)
				);
			}//endif
		}
	}
}
global $ThemeumSlider2Shortcode;
if(class_exists('WPBakeryShortCodesContainer'))
{
	class WPBakeryShortCode_themeum_slider2_wrap extends WPBakeryShortCodesContainer {
        function content( $atts, $content = null ) {
            global $ThemeumSlider2Shortcode;
            return $ThemeumSlider2Shortcode->front_themeum_slider2_wrap($atts, $content);
        }
	}
	class WPBakeryShortCode_themeum_slider2_item extends WPBakeryShortCode {
        function content($atts, $content = null ) {
            global $ThemeumSlider2Shortcode;
            return $ThemeumSlider2Shortcode->front_themeum_slider2_item($atts, $content);
        }
	}
}
if(class_exists('ThemeumSlider2Shortcode'))
{
	$ThemeumSlider2Shortcode = new ThemeumSlider2Shortcode;
}
