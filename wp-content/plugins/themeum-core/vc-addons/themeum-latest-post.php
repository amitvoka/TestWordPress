<?php
add_shortcode( 'themeum_latest_post_shortcode', function($atts, $content = null) {

	extract(shortcode_atts(array(
		'category' 						=> '',
		'column' 						=> '4',
		'number' 						=> '3',
		'layoutstyle' 					=> 'style1',
		'class' 						=> '',
        'order_by'  					=> 'date',
        'order'   						=> 'DESC',
		), $atts));

 	global $post;

 	$output     = '';



 	$posts= 0;

 	if (isset($category) && $category!='') {
 		$idObj 	= get_category_by_slug( $category );

 		if (isset($idObj) && $idObj!='') {
			$idObj 	= get_category_by_slug( $category );
			$cat_id = $idObj->term_id;

			$args = array(
		    	'category' => $cat_id,
		        'orderby' => $order_by,
		        'order' => $order,
		        'posts_per_page' => $number,
		    );
		    $posts = get_posts($args);
 		}else{
 			echo "Please Enter a valid category name";
 			$args = 0;
 		}
 		}else{
			$args = array(
		        'orderby' => $order_by,
		        'order' => $order,
		        'posts_per_page' => $number,
		    );
		    $posts = get_posts($args);
	 	}

    if(count($posts)>1){
		$x = 1;
	    foreach ($posts as $post): setup_postdata($post);
			if( $x == 1 ){
		    	$output .= '<div class="row">';
		    }
		    $output .= '<div class="col-xs-12 col-sm-6 col-md-'.esc_attr( $column ).'">';
				if ( $layoutstyle == 'style2' ) {
					$output .= '<div class="themeum-latest-post themeum-latest-post-v2">';
					$output .= '<a class="review-item-image"  href="'.get_permalink().'">'.get_the_post_thumbnail($post->ID, 'travelkit-medium', array('class' => 'img-responsive')).'</a>';
					$output .= '<div class="themeum-latest-post-content clearfix">';
					$output .= '<h3 class="entry-title"><a href="'.get_permalink().'">'. get_the_title() .'</a></h3>';
					$output .= '<span class="meta-date"> <i class="fa fa-clock-o"></i>'. date_i18n( get_option( 'date_format' ), strtotime( get_the_date() ) ) .'</span>';
					if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
						//$output .= '<span class="comments">' . comments_popup_link( '0',  '1' , '%'  ) . '</span>';
						$output .= '<span class="latest-post-comments"><i class="fa fa-commenting-o"></i> <a href="'.get_permalink().'#comments" >' . get_comments_number(). '</a></span>';
					endif;
					$output .= '</div>';//themeum-latest-post-content
					$output .= '</div>';//themeum-latest-post
				} else{
					$output .= '<div class="themeum-latest-post">';
					$output .= '<a class="review-item-image"  href="'.get_permalink().'">'.get_the_post_thumbnail($post->ID, 'travelkit-medium', array('class' => 'img-responsive')).'</a>';
					$output .= '<div class="themeum-latest-post-content clearfix">';
					$output .= '<h3 class="entry-title"><a href="'.get_permalink().'">'. get_the_title() .'</a></h3>';
					$output .= '<span class="meta-date">'. date_i18n( get_option( 'date_format' ), strtotime( get_the_date() ) ) .'</span>';
					if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
						//$output .= '<span class="comments">' . comments_popup_link( '0',  '1' , '%'  ) . '</span>';
						$output .= '<span class="latest-post-comments"><i class="fa fa-commenting-o"></i> <a href="'.get_permalink().'#comments" >' . get_comments_number(). '</a></span>';
					endif;
					$output .= '</div>';//themeum-latest-post-content
					$output .= '</div>';//themeum-latest-post
				}
			$output .= '</div>';//col-sm-6

			if( $x == (12/$column) ){
				$output .= '</div>'; //row
				$x = 1;
			}else{
				$x++;
			}
	    endforeach;
	    if($x !=  1 ){
			$output .= '</div>'; //row
		}
	    wp_reset_postdata();

	}
	return $output;

});


//Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
	vc_map(array(
		"name" => esc_html__("Themeum Latest Post", 'themeum-core'),
		"base" => "themeum_latest_post_shortcode",
		'icon' => 'icon-thm-latest-post',
		"class" => "",
		"description" => esc_html__("Widget Title Heading", 'themeum-core'),
		"category" => esc_html__('Travelkit', 'themeum-core'),
		"params" => array(

			array(
				"type" => "dropdown",
				"heading" => esc_html__("Layout style", "themeum-core"),
				"param_name" => "layoutstyle",
				"value" => array('Select'=>'','Style 1'=>'style1','Style 2'=>'style2'),
				),

			array(
				"type" => "textfield",
				"heading" => esc_html__("Category Name (leave empty for all latest post list)", 'themeum-core'),
				"param_name" => "category",
				"value" => "",
				),

			array(
				"type" => "dropdown",
				"heading" => esc_html__("Number Of Column", "themeum-themeum-core"),
				"param_name" => "column",
				"value" => array('column 2'=>'6','column 3'=>'4','column 4'=>'3'),
			),

			array(
				"type" => "textfield",
				"heading" => esc_html__("Number of items", 'themeum-core'),
				"param_name" => "number",
				"value" => "",
				),

			array(
				"type" => "dropdown",
				"heading" => esc_html__("OderBy", 'themeum-core'),
				"param_name" => "order_by",
				"value" => array('Date'=>'date','Title'=>'title','Modified'=>'modified','Author'=>'author','Random'=>'rand'),
				),

			array(
				"type" => "dropdown",
				"heading" => esc_html__("Order", 'themeum-core'),
				"param_name" => "order",
				"value" => array('DESC'=>'DESC','ASC'=>'ASC'),
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
