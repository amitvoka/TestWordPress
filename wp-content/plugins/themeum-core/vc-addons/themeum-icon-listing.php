<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Upcoming Events
add_shortcode( 'themeum_icon_listing', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'icon_list'					=> '',
		'icon_size'					=> '',
		'icon_color'				=> '',
		'contant_text'				=> '',
		'contant_font_size'			=> '',
		'contant_text_color'		=> '',
		'text_content_margin'		=> '',
		'content_text_url'			=> '',
		'class'						=> ''
		), $atts));

	global $post;

	$output = $style = $style1 = $style2 = '';
	if($icon_color) $style .= 'color:' . $icon_color  . ';';
	if($contant_text_color) $style1 .= 'color:' . $contant_text_color  . ';';
	if($text_content_margin) $style2 .= 'margin:' . esc_attr( $text_content_margin ) . ';';


	$output  .= '<div class="atmosphere-details ' . $class .'">';
        $output  .= '<article class="atmosphere-content" style = "'.$style2.'">';
            $output  .= '<ul>';
                $output  .= '<li>';
                	if($icon_list && $contant_text) {
						$font = '';
						if ($icon_size) {
							$font 	= 'font-size: '.(int)esc_attr($icon_size).'px';
						} else {
							$font 	= 'font-size: 16px';
						}
						$icon_style  = 'style="'.$font.'; '.$style.'"';
						$output		.= '<i '.$icon_style.' class="fa '.esc_attr( $icon_list ).'"></i>';

						$fontcolor = '';
						if ($contant_font_size) {
							$fontcolor 	= 'font-size: '.(int)esc_attr($contant_font_size).'px';
						} else {
							$fontcolor 	= 'font-size: 16px';
						}
						$icon_style1  = 'style="'.$fontcolor.'; '.$style1.'"';

						if ($content_text_url) {
							$output 	.= '<a '.$icon_style1.' href="'.$content_text_url.'" target="_blank"><span>' . esc_attr($contant_text) . '</span></a>';
						}else{
							 $output  	.= '<span '.$icon_style1.'>' . esc_attr($contant_text) . '</span>';
						}
					}
                $output  .= '</li>';
            $output  .= '</ul>';
        $output  .= '</article>';
    $output  .= '</div>';

	return $output;
});

//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
	vc_map(array(
		"name" => __("Themeum Icon listing", "themeum-core"),
		"base" => "themeum_icon_listing",
		'icon' => 'icon-thm-gallery',
		"class" => "",
		"description" => "Themeum Icon Listing",
		"category" 	=> __('Travelkit', "themeum-core"),
		"params" => array(

			array(
				"type" 			=> "dropdown",
				"heading" 		=> esc_html__("Icon List", 'themeum-core'),
				"param_name" 	=> "icon_list",
				"value" 		=> getIconsList(),
			),

			array(
				"type" 			=> "textfield", 
				"heading" 		=> esc_html__("Icon Font Size", 'themeum-core'),
				"param_name" 	=> "icon_size",
				"value" 		=> "",
			),

			array(
				"type" 			=> "colorpicker",
				"heading" 		=> esc_html__("Icon Color", 'themeum-core'),
				"param_name" 	=> "icon_color",
				"value" 		=> "",
			),

			array(
				"type" 			=> "textarea", 
				"heading" 		=> esc_html__("Content", 'themeum-core'),
				"param_name" 	=> "contant_text",
				"value" 		=> "",
			),

			array(
				"type" 			=> "textfield", 
				"heading" 		=> esc_html__("Contant Font Size", 'themeum-core'),
				"param_name" 	=> "contant_font_size",
				"value" 		=> "",
			),

			array(
				"type" 			=> "colorpicker",
				"heading" 		=> esc_html__("Contant Text Color", 'themeum-core'),
				"param_name" 	=> "contant_text_color",
				"value" 		=> "",
			),

			array(
				"type" => "textfield",
				"heading" => esc_html__("Text Content Margin", 'themeum-core'),
				"param_name" => "text_content_margin",
				"value" => "10px 0 10px 0",
			),

			array(
				"type" => "textfield",
				"heading" => esc_html__("Content Text URL", 'themeum-core'),
				"param_name" => "content_text_url",
				"value" => "",
			),

			array(
				"type" 			=> "textfield",
				"heading" 		=> esc_html__("Extra CSS Class", "themeum-core"),
				"param_name" 	=> "class",
				"value" 		=> "",
				"description" 	=> "Add your class"
				),

			)
		));
}
