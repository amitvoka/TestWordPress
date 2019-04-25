<?php
add_shortcode( 'themeum_title', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'titlelayout' 			=> 'layout1',
		'alignment' 			=> 'center',
		'title_style' 			=> 'standardstyle',
		'title' 				=> '',
		'title_size'			=> '36',
		'title_weight'			=> '400',
		'title_heading' 		=> 'h2',
		'title_color'			=> '',
		'title_margin'			=> '10px 0 10px 0',
		'title_padding'			=> '10px 0 10px 0',

		'subtitle'				=> '',
		'sub_title_style'		=> 'standardstylesub',
		'sub_title_heading'		=> 'h3',
		'sub_title_size'		=> '24',
		'sub_title_color'		=> '',
		'sub_title_weight'		=> '400',
		'sub_title_margin'		=> '0 0 10px 0',
		'class'					=> ''
		), $atts));

		$inline1 ='';
		$inline2 ='';
		$layoutclass ='';
		$titlestyle ='';
		$subtitlestyle ='';
		$output = '';
		$align = '';

	//align
	if($alignment) { $align = 'style="text-align:'. esc_attr( $alignment ) .';"';}

	//layout
	if ( $titlelayout != '' ) {
		$layoutclass = $titlelayout;
	} else {
		$layoutclass = '';
	}	

	// title
	if ( $title_style == 'customstyle' ) {
		if($title_size) $inline1 		.= 'font-size:' . (int) esc_attr( $title_size ) . 'px;line-height: normal;';
		if($title_color) $inline1		.= 'color:' . esc_attr( $title_color )  . ';';	
		if($title_weight) $inline1  	.= 'font-weight:' . (int) esc_attr( $title_weight ) . ';';
		if($title_margin) $inline1		.= 'margin:' . esc_attr( $title_margin ) . ';';
		if($title_padding) $inline1		.= 'padding:' . esc_attr( $title_padding ) . ';';
		$inline1 = 'style="'.$inline1.'"';
		$titlestyle = esc_attr($title_style);
	} elseif ( $title_style == 'standardstyle' ) {
		$titlestyle = esc_attr($title_style);
	} else {
		$titlestyle = '';
	}	

	// subtitle
	if ( $sub_title_style == 'customstylesub' ) {
		if($sub_title_size) $inline2 		.= 'font-size:' . (int) esc_attr( $sub_title_size ) . 'px;line-height: normal;';
		if($sub_title_color) $inline2		.= 'color:' . esc_attr( $sub_title_color )  . ';';	
		if($sub_title_weight) $inline2  	.= 'font-weight:' . (int) esc_attr( $sub_title_weight ) . ';';
		if($sub_title_margin) $inline2		.= 'margin:' . esc_attr( $sub_title_weight ) . ';';
		$inline2 = 'style="'.$inline2.'"';
		$subtitlestyle = esc_attr($sub_title_style);
	} elseif ( $sub_title_style == 'standardstylesub' ) {
		$subtitlestyle = esc_attr($sub_title_style);
	} else {
		$subtitlestyle = '';
	}

	$output .= '<div class="addon-themeum-title '.esc_attr( $class ).'" '.$align.'>';
		$output .= '<div class="themeum-title'.esc_attr( $layoutclass ).'">';
			if ($title) {
				$output 	.= '<'.$title_heading.' class="thm-title'.$titlestyle.'" '.$inline1.'>' . esc_attr( $title ) . '</'.$title_heading.'>';
			}	
			if ($subtitle) {
				$output 	.= '<'.$sub_title_heading.' class="thm-sub-title'.$subtitlestyle.'" '.$inline2.'>' . balanceTags( $subtitle ) . '</'.$sub_title_heading.'>';	
			}
		$output .= '</div>';//themeum-title
	$output .= '</div>';//addon-themeum-title

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
vc_map(array(
	"name" => esc_html__("Title", 'themeum-core'),
	"base" => "themeum_title",
	'icon' => 'icon-thm-title',
	"class" => "",
	"description" => esc_html__("Widget Title Heading", 'themeum-core'),
	"category" => esc_html__('Travelkit', 'themeum-core'),
	"params" => array(

		array(
			"type" => "dropdown",
			"heading" => esc_html__("Title Layout", 'themeum-core'),
			"param_name" => "titlelayout",
			"value" => array('Select'=>'','Layout 1'=>'layout1','Layout 2'=>'layout2'),
		),

		array(
			"type" => "dropdown",
			"heading" => esc_html__("Alignment", 'themeum-core'),
			"param_name" => "alignment",
			"value" => array('Select'=>'','Left'=>'left','Center'=>'center','Right'=>'right'),
		),

		array(
			"type" => "textfield",
			"heading" => esc_html__("Title Text", 'themeum-core'),
			"param_name" => "title",
			"value" => "",
		),				

		array(
			"type" => "dropdown",
			"heading" => esc_html__("Select Title Style", 'themeum-core'),
			"param_name" => "title_style",
			"value" => array('Select'=>'','Standard Style'=>'standardstyle','Custom Style'=>'customstyle'),
		),	

		array(
			"type" => "dropdown",
			"heading" => esc_html__("Title Heading", 'themeum-core'),
			"param_name" => "title_heading",
			"value" => array('Select'=>'','h1'=>'h1','h2'=>'h2','h3'=>'h3','h4'=>'h4','h5'=>'h5','span'=>'span','p'=>'p'),
		),				

		array(
			"type" => "textfield",
			"heading" => esc_html__("Title Font Size", 'themeum-core'),
			"param_name" => "title_size",
			"value" => "",
			"dependency" => Array("element" => "title_style", "value" => array("customstyle")),
		),		

		array(
			"type" => "colorpicker",
			"heading" => esc_html__("Title Color", 'themeum-core'),
			"param_name" => "title_color",
			"value" => "",
			"dependency" => Array("element" => "title_style", "value" => array("customstyle")),
		),

		array(
			"type" => "dropdown",
			"heading" => esc_html__("Title Font Weight", 'themeum-core'),
			"param_name" => "title_weight",
			"value" => array('Select'=>'','200'=>'200','300'=>'300','400'=>'400','500'=>'500','600'=>'600','700'=>'700','800'=>'800'),
			"dependency" => Array("element" => "title_style", "value" => array("customstyle")),
		),

		array(
			"type" => "textfield",
			"heading" => esc_html__("Title Margin", 'themeum-core'),
			"param_name" => "title_margin",
			"value" => "10px 0 10px 0",
			"dependency" => Array("element" => "title_style", "value" => array("customstyle")),
		),			

		array(
			"type" => "textfield",
			"heading" => esc_html__("Title Padding", 'themeum-core'),
			"param_name" => "title_padding",
			"value" => "10px 0 10px 0",
			"dependency" => Array("element" => "title_style", "value" => array("customstyle")),
		),					

		//subtitle
		array(
			"type" => "textarea",
			"heading" => esc_html__("Sub Title Text", 'themeum-core'),
			"param_name" => "subtitle",
			"value" => "",
		),

		array(
			"type" => "dropdown",
			"heading" => esc_html__("Select Sub Title Style", 'themeum-core'),
			"param_name" => "sub_title_style",
			"value" => array('Select'=>'','Standard Style'=>'standardstylesub','Custom Style'=>'customstylesub'),
		),				

		array(
			"type" => "textfield",
			"heading" => esc_html__("Sub Title Font Size", 'themeum-core'),
			"param_name" => "sub_title_size",
			"value" => "",
			"dependency" => Array("element" => "sub_title_style", "value" => array("customstylesub")),
		),	

		array(
			"type" => "colorpicker",
			"heading" => esc_html__("Sub Title Color", 'themeum-core'),
			"param_name" => "sub_title_color",
			"value" => "",
			"dependency" => Array("element" => "sub_title_style", "value" => array("customstylesub")),
		),

		array(
			"type" => "dropdown",
			"heading" => esc_html__("Sub Title Heading", 'themeum-core'),
			"param_name" => "sub_title_heading",
			"value" => array('Select'=>'','h1'=>'h1','h2'=>'h2','h3'=>'h3','h4'=>'h4','h5'=>'h5','span'=>'span','p'=>'p'),
		),	

		
		array(
			"type" => "dropdown",
			"heading" => esc_html__("Sub Title Font Weight", 'themeum-core'),
			"param_name" => "sub_title_weight",
			"value" => array('Select'=>'','200'=>'200','300'=>'300','400'=>'400','500'=>'500','600'=>'600','700'=>'700','800'=>'800'),
			"dependency" => Array("element" => "sub_title_style", "value" => array("customstylesub")),
		),	

		array(
			"type" => "textfield",
			"heading" => esc_html__("Sub Title Margin", 'themeum-core'),
			"param_name" => "sub_title_margin",
			"value" => "",
			"dependency" => Array("element" => "sub_title_style", "value" => array("customstylesub")),
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