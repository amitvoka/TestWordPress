<?php
add_shortcode( 'themeum_button', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'position'				=> 'left',
		'display'				=> 'block',
		'btn_link' 				=> '',
		'btn_name'		 		=> '',
		'target'		 		=> '_blank',
		'btn_text_size'			=> '',
		'btn_color' 			=> '',
		'btn_color_hover' 		=> '',
		'btn_background' 		=> '',
		'btn_background_hover' 	=> '',
		'border_radius' 		=> '',
		'btn_weight' 			=> '300',
		'btn_margin' 			=> '',
		'btn_padding' 			=> '',
		'btntype'				=> '',
		'btnsize'				=> '',
		'class' 				=> '',
		), $atts));

	$style = '';
	$align = '';
	if($position) $align .= 'text-align:'. esc_attr( $position ) .';';
	if($display) $align .= 'display:'. esc_attr( $display ) .';';

	if($btn_text_size) $style .= 'font-size:' . (int) esc_attr($btn_text_size) . 'px;line-height:'. (int) esc_attr($btn_text_size)  .'px;';

	if($btn_color) $style .= 'color:' . esc_attr($btn_color)  . ';';

	if($btn_background) $style .= 'background:' . esc_attr($btn_background)  . ';';

	if($border_radius) $style .= 'border-radius:' . (int) esc_attr($border_radius)  . 'px;';
	
	if($btn_weight) $style .= 'font-weight:'. esc_attr($btn_weight) .';';

	if($btn_margin) $style .= 'margin:' . esc_attr($btn_margin)  . ';';

	if($btn_padding) $style .= 'padding:' . esc_attr($btn_padding)  . ';display:inline-block';


	$output = '';

        if ($btn_link)
        {
        	$output .=  '<div class="themeum-button '.esc_attr($class).'" style="display: inline; '. $align .'">';
				$output .=  '<a data-hover-color="'.esc_attr($btn_color_hover).'" data-hover-bg-color="'.esc_attr($btn_background_hover).'"  class="action-btn btn btn-'. $btntype .' '. $btnsize .'" style="'.$style.'" href="'.esc_url($btn_link).'" target="'.esc_attr($target).'">'.esc_attr($btn_name).'</a>';
			$output .=  '</div>';
        }

		
      
	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
	vc_map(array(
		"name" => esc_html__(" Themeum Button", 'themeum-core'),
		"base" => "themeum_button",
		'icon' => 'icon-thm-btn',
		"category" => esc_html__('Travelkit', 'themeum-core'),
		"params" => array(


			array(
				"type" => "dropdown",
				"heading" => esc_html__("Position", 'themeum-core'),
				"param_name" => "position",
				"value" => array('Left'=>'left','Center'=>'center','Right'=>'right'),
			),	

			array(
				"type" => "dropdown",
				"heading" => esc_html__("Display", 'themeum-core'),
				"param_name" => "display",
				"value" => array('Block'=>'block','inline'=>'Inline','Inline Block'=>'inline-block'),
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
				"type" => "textfield",
				"heading" => esc_html__("Button Text Size", 'themeum-core'),
				"param_name" => "btn_text_size",
				"value" => "",
				),	

			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Button Text Color", 'themeum-core'),
				"param_name" => "btn_color",
				"value" => "",
				),

			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Hover Button Text Color", 'themeum-core'),
				"param_name" => "btn_color_hover",
				"value" => "",
				),	

			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Button Background", 'themeum-core'),
				"param_name" => "btn_background",
				"value" => "",
				),

			array(
				"type" => "colorpicker",
				"heading" => esc_html__("Hover Button Background", 'themeum-core'),
				"param_name" => "btn_background_hover",
				"value" => "",
				),	

			array(
				"type" => "textfield",
				"heading" => esc_html__("Border Radius", 'themeum-core'),
				"param_name" => "border_radius",
				"value" => "",
				),	

			array(
				"type" => "dropdown",
				"heading" => esc_html__("Button Font Wight", 'themeum-core'),
				"param_name" => "btn_weight",
				"value" => array('400'=>'400','100'=>'100','200'=>'200','300'=>'300','500'=>'500','600'=>'600','700'=>'700'),
				),				

			array(
				"type" => "textfield",
				"heading" => esc_html__("Button Margin Ex. 5px 0 5px 0", 'themeum-core'),
				"param_name" => "btn_margin",
				"value" => "",
				),				

			array(
				"type" => "textfield",
				"heading" => esc_html__("Button Padding Ex. 5px 0 5px 0", 'themeum-core'),
				"param_name" => "btn_padding",
				"value" => "",
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
				"type" => "textfield",
				"heading" => esc_html__("Custom Class ", 'themeum-core'),
				"param_name" => "class",
				"value" => "",
				)
			),
		));
}