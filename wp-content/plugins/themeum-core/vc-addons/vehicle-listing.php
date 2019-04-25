<?php
add_shortcode( 'themeum_vehicle_listing', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'category_package'				=>  'themeumall',
		'number' 						=> '6',
		'column' 						=> '4',
        'order_by'  					=> 'date',               
        'paginationen'  				=> 'no',               
        'order'   						=> 'DESC',      
        'class' 						=> '',
		), $atts));

 	global $post;

 	$posts = 0;	
	$temp_post = $post;
	$output = $args = $pagenum = '';
	$pagenum = max( 1, get_query_var('paged') );
	
    // The Query
  	if( $category_package == 'themeumall' ){
  		$args = array(
	      'post_type' => 'vehicle',
		  'order' => esc_attr($order),
		  'orderby' => esc_attr($order_by),
	      'posts_per_page' => esc_attr($number),
	      'paged' => $pagenum
	    );
  	}else{
		$args = array(
	        'post_type' => 'vehicle',
	        'order' => 'DESC',
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'vehicle-category',
	                'field'    => 'slug',
	                'terms'    => esc_attr($category_package),
	                ),
	            ),
	        'order' => esc_attr($order),
		    'orderby' => esc_attr($order_by),
	        'posts_per_page' => esc_attr($number),
	        'paged' => $pagenum
	    );
  	}

	$posts = get_posts( $args );

    if(count($posts)>0){
		// The Loop
		$output .= '<div class="add-popular-tour-package">';
		$x = 1;
		$output .= '<div class="row">';	
		foreach ($posts as $key=>$mpost): setup_postdata($mpost);

			$vehicleprice  = get_post_meta($mpost->ID,'themeum_vehicleprice',true);
			$vehicleduration  = get_post_meta($mpost->ID,'themeum_vehicleduration',true);
			if ($vehicleduration) {
				$vehicleduration = '/'.$vehicleduration;
			}
			$output .= '<div class="col-sm-6 col-md-'.esc_attr($column).'">';
				$output .= '<div class="package-list-wrap">';
	                $output .= get_the_post_thumbnail($mpost->ID,'travelkit-medium', array('class' => 'img-responsive'));
	                $output .= '<div class="package-list-content">';
	                	if ($vehicleprice != '') {
	                		if (!get_theme_mod( 'currency_right' )) {
	                			$output .= '<p class="package-list-duration">'.__('Start from','themeum-core').' ' .get_theme_mod( 'other_currency', '$' ).$vehicleprice.$vehicleduration.'</p>';
							} else {
								$output .= '<p class="package-list-duration">'.__('Start from','themeum-core').' ' .$vehicleprice.get_theme_mod( 'other_currency', '$' ).$vehicleduration.'</p>';
							}
	                	}
		                $output .= '<h3 class="package-list-title"><a href="'.get_the_permalink($mpost->ID).'">'.get_the_title($mpost->ID).'</a></h3>';

		                $view_details = get_theme_mod('vehicle_booknow_btn');
						if (isset($view_details) && $view_details != '') {
		                	$output .= '<a class="package-list-button" href="'.get_the_permalink($mpost->ID).'">'.$view_details.'</a>';
						}else {
		                	$output .= '<a class="package-list-button" href="'.get_the_permalink($mpost->ID).'">'.__('View Details','themeum-core').'</a>';
						}

					$output .= '</div>';//package-list-content
				$output .= '</div>';//package-list-wrap
			$output .= '</div>';//col-sm-6
							
		endforeach;
		$output .= '</div>'; //row	
		wp_reset_postdata();
		$output .= '</div>';//add-popular-tour-package
		
		//pagination
		if($paginationen == 'yes') {
		    $args['posts_per_page'] = -1;
		    $total_post = get_posts( $args );
			$var = $number;
			if( $var == "" || $var == 0 ){
				$total_post = 1;
			}else{
				$total_post = ceil( count($total_post)/(int)$var );
			}

			$output .= '<div class="themeum-pagination">';
				$big = 999999999; // need an unlikely integer
				$output .= paginate_links( array(
					'type'               => 'list',
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) )),
					'format' => '?paged=%#%',
					'current' => $pagenum,
					'total' => $total_post
				) );
			$output .= '</div>'; //pagination-in
		}
	}
	$post = $temp_post;

	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
	vc_map(array(
		"name" => esc_html__("Vehicle listing", 'themeum-core'),
		"base" => "themeum_vehicle_listing",
		'icon' => 'icon-thm-vehicle',
		"class" => "",
		"description" => esc_html__("Widget title heading", 'themeum-core'),
		"category" => esc_html__('Travelkit', 'themeum-core'),
		"params" => array(					

			array(
				"type" => "dropdown",
				"heading" => esc_html__("Category filter", 'themeum-core'),
				"param_name" => "category_package",
				"value" => themeum_cat_list('package-category'),
				),	

			array(
				"type" => "textfield",
				"heading" => esc_html__("Number of items", 'themeum-core'),
				"param_name" => "number",
				"value" => "6",
				),	

			array(
				"type" => "dropdown",
				"heading" => esc_html__("Number of column", "themeum-core"),
				"param_name" => "column",
				"value" => array('Select'=>'','column 2'=>'6','column 3'=>'4','column 4'=>'3'),
				),	

			array(
				"type" => "dropdown",
				"heading" => esc_html__("Order", 'themeum-core'),
				"param_name" => "order",
				"value" => array('None'=>'','DESC'=>'DESC','ASC'=>'ASC'),
				),	

			array(
				"type" => "dropdown",
				"heading" => esc_html__("OderBy", 'themeum-core'),
				"param_name" => "order_by",
				"value" => array('None'=>'','Date'=>'date','Title'=>'title','Modified'=>'modified','Author'=>'author','Random'=>'rand'),
				),	

			array(
				"type" => "dropdown",
				"heading" => esc_html__("Pagination", 'themeum-core'),
				"param_name" => "paginationen",
				"value" => array('None'=>'','YES'=>'yes','NO'=>'no'),
				),	

			array(
				"type" => "textfield",
				"heading" => esc_html__("Custom Class", 'themeum-core'),
				"param_name" => "class",
				"value" => "",
				),	

			)

		));
}