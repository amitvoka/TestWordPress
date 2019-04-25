<?php
add_shortcode( 'themeum_popup_video', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'alignment' 			=> 'center',
		'icon_list'					=> '',
		'icon_size'					=> '',
		'icon_color'				=> '',
		'title' 				=> '',
		'title_size'			=> '36',
		'title_weight'			=> '400',
		'title_heading' 		=> 'h2',
		'title_color'			=> '',
		'title_margin'			=> '10px 0 10px 0',
		'title_padding'			=> '10px 0 10px 0',
		'video_url'			=> '',
		'class'					=> ''



		), $atts));

		$inline1 ='';
		$inline2 ='';
		$titlestyle ='';
		$output = '';
		$btnclass = '';
		$align = '';


	//align
	if($alignment) { $align = 'style="text-align:'. esc_attr( $alignment ) .';"';}

	// title
	if($title_size) $inline1 		.= 'font-size:' . (int) esc_attr( $title_size ) . 'px;line-height: normal;';
	if($title_color) $inline1		.= 'color:' . esc_attr( $title_color )  . ';';
	if($title_weight) $inline1  	.= 'font-weight:' . (int) esc_attr( $title_weight ) . ';';
	if($title_margin) $inline1		.= 'margin:' . esc_attr( $title_margin ) . ';';
	if($title_padding) $inline1		.= 'padding:' . esc_attr( $title_padding ) . ';';
	$inline1 = 'style="'.$inline1.'"';

	$output = $style = $style1 = $style2 = '';
	if($icon_color) $style .= 'color:' . $icon_color  . ';';



	$output .= '<div class="addon-themeum-popup-video '.esc_attr( $class ).'" '.$align.'>';
		$output .= '<div class="themeum-popup-video">';
			$font = '';
			if ($icon_size) {
				$font 	= 'font-size: '.(int)esc_attr($icon_size).'px';
			} else {
				$font 	= 'font-size: 16px';
			}
			$icon_style  = 'style="'.$font.'; '.$style.'"';

			// $output		.= ;
			if ($video_url) {
				$output 	.= '<p><a class="popup-video" href="'.$video_url.'" target="_blank">' . '<i '.$icon_style.' class="fa '.esc_attr( $icon_list ).'"></i>' . '</a></p>';
			}
			if ($title) {
				$output 	.= '<'.$title_heading.' class="action-title" '.$inline1.'>' . esc_attr( $title ) . '</'.$title_heading.'>';
			}
		$output .= '</div>';//themeum-action
	$output .= '</div>';//addon-themeum-action

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => esc_html__("Popup Video", 'themeum-core'),
	"base" => "themeum_popup_video",
	'icon' => 'icon-thm-action',
	"class" => "",
	"description" => esc_html__("Widget Title Heading", 'themeum-core'),
	"category" => esc_html__('Travelkit', 'themeum-core'),
	"params" => array(

		array(
			"type" => "dropdown",
			"heading" => esc_html__("Alignment", 'themeum-core'),
			"param_name" => "alignment",
			"value" => array('Select'=>'','Left'=>'left','Center'=>'center','Right'=>'right'),
		),

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
			"type" => "textfield",
			"heading" => esc_html__("Title Text", 'themeum-core'),
			"param_name" => "title",
			"value" => "",
		),


		array(
			"type" => "textfield",
			"heading" => esc_html__("Title Font Size", 'themeum-core'),
			"param_name" => "title_size",
			"value" => "",
		),

		array(
			"type" => "colorpicker",
			"heading" => esc_html__("Title Color", 'themeum-core'),
			"param_name" => "title_color",
			"value" => "",
		),

		array(
			"type" => "dropdown",
			"heading" => esc_html__("Title Font Weight", 'themeum-core'),
			"param_name" => "title_weight",
			"value" => array('Select'=>'','200'=>'200','300'=>'300','400'=>'400','500'=>'500','600'=>'600','700'=>'700','800'=>'800'),
		),

		array(
			"type" => "textfield",
			"heading" => esc_html__("Title Margin", 'themeum-core'),
			"param_name" => "title_margin",
			"value" => "10px 0 10px 0",
		),

		array(
			"type" => "textfield",
			"heading" => esc_html__("Title Padding", 'themeum-core'),
			"param_name" => "title_padding",
			"value" => "10px 0 10px 0",
		),

		array(
			"type" => "textfield",
			"heading" => esc_html__("Video URL", 'themeum-core'),
			"param_name" => "video_url",
			"value" => "https:",
		),

		array(
			"type" => "textfield",
			"heading" => esc_html__("Custom Class ", 'themeum-core'),
			"param_name" => "class",
			"value" => "",
		),

		)
	));
}
