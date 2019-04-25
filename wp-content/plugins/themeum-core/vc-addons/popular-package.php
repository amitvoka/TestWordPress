<?php
add_shortcode( 'themeum_popular_tourpackage', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'category_package'				=>  'themeumall',
		'number' 						=> '6',
		'layoutstyle' 					=> 'style1',
		'column' 						=> '4',
        'order_by'  					=> 'date',
        'paginationen'  				=> 'no',
        'order'   						=> 'DESC',
        'class' 						=> '',
	), $atts));

 	global $post;

 	$posts = 0;
	$temp_post = $post;
	$output = $args = '';
	$pagenum = max( 1, get_query_var('paged') );

    # The Query
  	if( $category_package == 'themeumall' ){
  		$args = array(
	      'post_type' 		=> 'package',
		  'order' 			=> esc_attr($order),
	      'posts_per_page' 	=> esc_attr($number),
	      'orderby'      	=> 'meta_value_num',
	      'meta_key'     	=> 'post_views_count',
	      'paged' 			=> $pagenum,
	    );
  	}else{
		$args = array(
	        'post_type' => 'package',
	        'order' 	=> 'DESC',
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'package-category',
	                'field'    => 'slug',
	                'terms'    => esc_attr($category_package),
	                ),
	            ),
	        'order' 			=> esc_attr($order),
	        'orderby'      		=> 'meta_value_num',
	        'meta_key'     		=> 'post_views_count',
	        'posts_per_page' 	=> esc_attr($number),
	        'paged' 			=> $pagenum,
	    );
  	}

	$posts = get_posts( $args );


    if(count($posts)>0){
		// The Loop
		$output .= '<div class="add-popular-tour-package">';
		$output .= '<div class="row">';
		foreach ($posts as $key=>$mpost): setup_postdata($mpost);
			$no_thumb = '';
			if (!has_post_thumbnail($mpost->ID)) {
				$no_thumb = 'package-list-no-thumb';
			}
			$packduration  	= get_post_meta($mpost->ID,'themeum_packduration',true);
			$booknow_btn	= get_post_meta($mpost->ID,'themeum_packbooknow',true);

			if ( $layoutstyle == 'style2' ) {
				$output .= '<div class="col-sm-6 col-md-'.esc_attr($column).'">';
					$output .= '<div class="package-list-wrap package-list-wrap-v2 '.esc_attr($no_thumb).'">';
						$output .= '<div class="package-img-wrap">';
                		$output .= get_the_post_thumbnail($mpost->ID,'travelkit-medium', array('class' => 'img-responsive'));
							if ( isset($booknow_btn) && $booknow_btn != '') {
								$output .= '<a class="package-list-button" href="'.get_the_permalink($mpost->ID).'">'.$booknow_btn.'</a>';
		                	}else {
		                		$output .= '<a class="package-list-button" href="'.get_the_permalink($mpost->ID).'">'.__('Book Now','themeum-core').'</a>';
		                	}	
						$output .= '</div>'; //package-img-wrap
		                $output .= '<div class="package-list-content">';
		                	if ($packduration != '') {
		                		if (!get_theme_mod( 'currency_right' )) {
		                			$output .= '<p class="package-list-duration">'.sprintf('%s Start From %s', $packduration, get_theme_mod( 'package_currency', '$' ).get_post_meta($mpost->ID,'themeum_packprice',true)).'</p>';
		                		} else {
		                			$output .= '<p class="package-list-duration">'.sprintf('%s Start From %s', $packduration, get_post_meta($mpost->ID,'themeum_packprice',true).get_theme_mod( 'package_currency', '$' )).'</p>';
		                		}
		                	}
			                $output .= '<h3 class="package-list-title"><a href="'.get_the_permalink($mpost->ID).'">'.get_the_title($mpost->ID).'</a></h3>';
						$output .= '</div>';//package-list-content
					$output .= '</div>';//package-list-wrap
				$output .= '</div>';//col-sm-6
			} elseif ( $layoutstyle == 'style3' ) {
				$output .= '<div class="col-sm-6 col-md-'.esc_attr($column).'">';
					$output .= '<div class="package-list-wrap package-list-wrap-v3 '.esc_attr($no_thumb).'">';
						$output .= '<div class="package-img-wrap">';
		                	$output .= get_the_post_thumbnail($mpost->ID,'travelkit-medium', array('class' => 'img-responsive'));
		                	if ( isset($booknow_btn) && $booknow_btn != '') {
								$output .= '<a class="package-list-button" href="'.get_the_permalink($mpost->ID).'">'.$booknow_btn.'</a>';
		                	}else {
		                		$output .= '<a class="package-list-button" href="'.get_the_permalink($mpost->ID).'">'.__('Book Now','themeum-core').'</a>';
		                	}	
						$output .= '</div>'; //package-img-wrap
		                $output .= '<div class="package-list-content">';
		                	if ($packduration != '') {
		                		if (!get_theme_mod( 'currency_right' )) {
		                			$output .= '<p class="package-list-duration">'.sprintf('%s Start From %s', $packduration, get_theme_mod( 'package_currency', '$' ).get_post_meta($mpost->ID,'themeum_packprice',true)).'</p>';
		                		} else {
		                			$output .= '<p class="package-list-duration">'.sprintf('%s Start From %s', $packduration, get_post_meta($mpost->ID,'themeum_packprice',true).get_theme_mod( 'package_currency', '$' )).'</p>';
		                		}
		                	}
			                $output .= '<h3 class="package-list-title"><a href="'.get_the_permalink($mpost->ID).'">'.get_the_title($mpost->ID).'</a></h3>';
						$output .= '</div>';//package-list-content
					$output .= '</div>';//package-list-wrap
				$output .= '</div>';//col-sm-6
			} else{
			$output .= '<div class="col-sm-6 col-md-'.esc_attr($column).'">';
				$output .= '<div class="package-list-wrap '.esc_attr($no_thumb).'">';
	                $output .= get_the_post_thumbnail($mpost->ID,'travelkit-medium', array('class' => 'img-responsive'));
	                $output .= '<div class="package-list-content">';
	                	if ($packduration != '') {
	                		if (!get_theme_mod( 'currency_right' )) {
	                			$output .= '<p class="package-list-duration">'.sprintf('%s Start From %s', $packduration, get_theme_mod( 'package_currency', '$' ).get_post_meta($mpost->ID,'themeum_packprice',true)).'</p>';
	                		} else {
	                			$output .= '<p class="package-list-duration">'.sprintf('%s Start From %s', $packduration, get_post_meta($mpost->ID,'themeum_packprice',true).get_theme_mod( 'package_currency', '$' )).'</p>';
	                		}
	                	}
		                $output .= '<h3 class="package-list-title"><a href="'.get_the_permalink($mpost->ID).'">'.get_the_title($mpost->ID).'</a></h3>';
		                if ( isset($booknow_btn) && $booknow_btn != '') {
							$output .= '<a class="package-list-button" href="'.get_the_permalink($mpost->ID).'">'.$booknow_btn.'</a>';
	                	}else {
	                		$output .= '<a class="package-list-button" href="'.get_the_permalink($mpost->ID).'">'.__('Book Now','themeum-core').'</a>';
	                	}
					$output .= '</div>';//package-list-content
				$output .= '</div>';//package-list-wrap
			$output .= '</div>';//col-sm-6
			}
		endforeach;
		wp_reset_postdata();
		$output .= '</div>';//row
		$output .= '</div>';//add-popular-tour-package

		# Popular Package Pagination
		if($paginationen == 'yes') {

		    $args['posts_per_page'] = -1;
		    $total_post 			= get_posts( $args );
			$var 					= $number;

			if( $var == "" || $var == 0 ){
				$total_post = 1;
			}else{
				$total_post = ceil( count($total_post)/(int)$var );
			}

			$output .= '<div class="themeum-pagination">';
				$big = 999999999; # need an unlikely integer
				$output .= paginate_links( array(
					'type'              => 'list',
					'base' 				=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) )),
					'format' 			=> '?paged=%#%',
					'current' 			=> $pagenum,
					'total' 			=> $total_post
				) );
			$output .= '</div>'; # pagination-in
		}
	}
	$post = $temp_post;

	return $output;

});


# Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
	vc_map(array(
		"name" 			=> esc_html__("Popular Tour Package", 'themeum-core'),
		"base" 			=> "themeum_popular_tourpackage",
		'icon' 			=> 'icon-thm-tourpackage',
		"class" 		=> "",
		"description" 	=> esc_html__("Widget Title Heading", 'themeum-core'),
		"category" 		=> esc_html__('Travelkit', 'themeum-core'),
		"params" 		=> array(

			array(
				"type" 			=> "dropdown",
				"heading" 		=> esc_html__("Category Filter", 'themeum-core'),
				"param_name" 	=> "category_package",
				"value" 		=> themeum_cat_list('package-category'),
			),

			array(
				"type" 			=> "dropdown",
				"heading" 		=> esc_html__("Layout style", "themeum-core"),
				"param_name" 	=> "layoutstyle",
				"value" 		=> array('Select'=>'','Style 1'=>'style1','Style 2'=>'style2','Style 3'=>'style3'),
			),

			array(
				"type" => "textfield",
				"heading" => esc_html__("Number of items", 'themeum-core'),
				"param_name" => "number",
				"value" => "6",
			),

			array(
				"type" => "dropdown",
				"heading" => esc_html__("Number Of Column", "themeum-core"),
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
