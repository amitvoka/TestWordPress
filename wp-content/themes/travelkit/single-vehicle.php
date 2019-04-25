<?php
/**
 * Display Single Movie 
 *
 * @author 		Themeum
 * @category 	Template
 * @package 	Package
 *-------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

get_header();
?>

<section id="main" class="package-single clearfix">
	<?php while ( have_posts() ) : the_post(); 

	$cover = '';
	if ( has_post_thumbnail() ) { 
		$cover_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		$cover = 'style="background-image:url('.esc_url($cover_img[0]).');background-repeat:no-repeat;background-size: cover;background-position: 50% 50%;"';
	} else { 
		$cover = 'style="background-color: #333;"';
	} //.entry-thumbnail ?>

	<!-- buy now button popup -->
	<div id="buynowbtn" class="white-popup mfp-with-anim mfp-hide">
	    <h3 class="title"><?php echo esc_html__('Booking for :','travelkit') . ' <span>' . get_the_title() . '</span>'; ?></h3>
	    <?php
	    	if (get_post_meta(get_the_ID(),'themeum_contact_forms',true)) {
	    		echo do_shortcode( '[contact-form-7 id="'.get_post_meta(get_the_ID(),'themeum_contact_forms',true).'"]' );
	    	}
	    ?>
	</div>

	<?php

		# additional info	
		$vehiclename  			= get_post_meta(get_the_ID(),'themeum_vehiclename',true);
		$passenger  			= get_post_meta(get_the_ID(),'themeum_passenger',true);
		$vehicleavailability  	= get_post_meta(get_the_ID(),'themeum_vehicleavailability',true);
		$vehicleprice  			= get_post_meta(get_the_ID(),'themeum_vehicleprice',true);
		$vehicleduration  			= get_post_meta(get_the_ID(),'themeum_vehicleduration',true);
		$vehiclehelptext  		= get_post_meta(get_the_ID(),'themeum_vehiclehelptext',true);
		$vehiclecontactnum  	= get_post_meta(get_the_ID(),'themeum_vehiclecontactnum',true);
		$vehiclecontactemail  	= get_post_meta(get_the_ID(),'themeum_vehiclecontactemail',true);

		# details info
		$vehiclegallery 		= get_post_meta(get_the_ID(), 'themeum_vehiclegallery');
		$vehiclechooselist 		= get_post_meta(get_the_ID(), 'themeum_vehiclechooselist',true);
		$vehiclecontact_forms 	= get_post_meta(get_the_ID(), 'themeum_vehiclecontact_forms',true);
		$contactshortnote 		= get_post_meta(get_the_ID(), 'themeum_contactshortnote',true);
		$vehiclereview 			= get_post_meta(get_the_ID(), 'themeum_vehiclereview',true);

		$external_link   	 	 = get_post_meta(get_the_ID(),'themeum_external_link',true);

		# Vehicle
		$vehiclebooknow   	 	 = get_post_meta(get_the_ID(),'themeum_vehiclebooknow',true);

		$book_now_link 		= (empty($external_link)) ? '#buynowbtn' : $external_link ;
		$book_now_cls 		= (empty($external_link)) ? 'buynowbtn' : '' ;
		$vehicleschedule 	= array();

		$schedules_args 	= array(
			'post_type' 		=> 'schedules',
			'posts_per_page' 	=> -1,
			'meta_query' 		=> array(
	            'relation' => 'AND',
	            array(
	                'key'     => 'themeum_schedule_booked',
	                'value'   => '0',
	                'compare' => '=',
	            ),
	            array(
	                'key'     => 'themeum_schedule_car',
	                'value'   => get_the_id(),
	                'compare' => '=',
	            )
	        ),
		);

		$schedules = get_posts($schedules_args);

		foreach ($schedules as $key => $schedule) {
			$vehicleschedule[$key] = array();

			$vehicleschedule[$key]['pickup'] = get_post_meta($schedule->ID,'themeum_schedule_pickup_time',true);
			$vehicleschedule[$key]['drop'] = get_post_meta($schedule->ID,'themeum_schedule_drop_time',true);
			$vehicleschedule[$key]['available'] = get_post_meta($schedule->ID,'themeum_schedule_booked',true);
		}


		// $vehicleschedule                = get_post_meta(get_the_ID(),'themeum_schedule_list',true);

		if ($vehicleduration != '') {
			$vehicleduration = '/'.$vehicleduration;
		}


		$packbooknow   	 	 		= get_post_meta(get_the_ID(),'themeum_vehiclebookingtrigger',true);

		$packbooknow = (int) $packbooknow;

		$sidebar_cls = ($packbooknow === 1) ? 'bigtop' : '';
	?>

	<div class="subtitle-cover" <?php print $cover;?>>
		<div class="container">
			<div class="row">
			    <div class="col-sm-12">
			    	<h2><?php echo get_the_title();?></h2>
			    	<?php travelkit_breadcrumbs(); ?>
			    </div> <!--//col-sm-12-->
			</div> <!--//row-->
		</div> <!--//container-->
	</div> <!--//moview-cover-->
	

	<!-- Paypal Form -->
	<?php thm_paypal_form_vehicle(); ?>
	<!-- Paypal Form End -->

	<div class="package-details-wrap">
		<div class="container" >
			<div class="row">
				<div class="packagedetailin clearfix">
					<?php if($packbooknow === 0): ?>
						<div class="pull-right">
							<?php if ( isset($vehiclebooknow) && $vehiclebooknow != '') { ?>
								<a href="<?php echo esc_attr( $book_now_link ); ?>" class="<?php echo esc_attr( $book_now_cls ); ?> btn btn-success" data-effect="mfp-zoom-in"><?php echo $vehiclebooknow; ?></a>
							<?php } else { ?>
								<a href="<?php echo esc_attr( $book_now_link ); ?>" class="<?php echo esc_attr( $book_now_cls ); ?> btn btn-success" data-effect="mfp-zoom-in"><?php echo esc_html_e('Book Now','travelkit');?></a>
							<?php } ?>
						</div>
					<?php endif; ?>
					<div class="col-sm-9">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs package-nav-tab" role="tablist">
						    <li class="active"><a href="#vehicleinfo" role="tab" data-toggle="tab"><?php esc_html_e('Vehicle Info','travelkit');?></a></li>
				            <?php if(is_array($vehiclegallery)){ ?>
								<?php if(!empty($vehiclegallery)) { ?>					            
						    		<li><a href="#gallery" role="tab" data-toggle="tab"><?php esc_html_e('Gallery','travelkit');?></a></li>
					            <?php } ?>
				            <?php } ?>			            	
			            	<?php if(is_array($vehicleschedule)){ 
				            	if(!empty($vehicleschedule)){
				            	?>				            					    
					    		<li><a href="#schedule" role="tab" data-toggle="tab"><?php esc_html_e('Schedule','travelkit');?></a></li>
				            <?php } ?>
				            <?php } ?>
														    
						</ul><!--/.package-nav-tab-->

						<div class="tab-content package-tab-content">

						    <!--vehicleinfo-->
						    <div class="tab-pane fade in active" id="vehicleinfo">

							  	<div class="package-details-content">
							  		<h3 class="title"><?php esc_html_e( 'Vehicle Details','travelkit' ); ?></h3>
									<?php the_content(); ?>	
									<?php if ( is_array($vehiclechooselist)) { ?>
										<?php if(!empty($vehiclechooselist)) { ?>
											<div class="package-details-choose">		
												<h3 class="title"><?php esc_html_e( 'Why you choose this Vehicle','travelkit' ); ?></h3>
												<ul class="clearfix">
													<?php foreach ($vehiclechooselist as $value) { ?>
								                        <li><span><i class="fa fa-check"></i><?php echo esc_html($value); ?> </span></li>
								                    <?php } ?>
							                    </ul>
						                    </div><!--/.package-details-choose-->
					                    <?php } ?>
				                    <?php } ?>

								</div><!--/.package-details-content-->
						    </div><!--/.tab-pane-->
						    <!--/#vehicleinfo-->


						    <!--gallery-->
						    <?php if ( is_array($vehiclegallery)) { ?>
							<?php if(!empty($vehiclegallery)) { ?>	
							  <div class="tab-pane fade in" id="gallery">
							  	<div class="vehicle-quick-contact">    	
								  	<div class="package-details-gallery">
							            <div class="margin-bottom photo-gallery-item">
								            <?php 
							            	$limit  = 12;  
							            	$slices = (count($vehiclegallery) > $limit) ? array_slice($vehiclegallery, 0, $limit , true ) : $vehiclegallery;?>
						                    <div class="photos-gallery owl-carousel">
							                    <?php 
							                    foreach ($slices as $key=>$photo) {
								                    $photo_thumb = wp_get_attachment_image_src( $photo, 'travelkit-medium' );?>
							                        <div class="item">
							                           <img src="<?php echo esc_url($photo_thumb[0]);?>" class="img-responsive" alt="<?php esc_html_e('photo : ','travelkit');?>" />
							                        </div><!--/.gallery-items-img-->
							                    <?php }  ?>
											</div> <!--/.col-md-3-->
										</div><!--/.row-->
							  		</div><!--/.package-details-gallery-->
							  	</div><!--/.vehicle-quick-contact-->						  
							  </div><!--/.tab-pane-->
					    	<?php } ?>
					    	<?php } ?>
						    <!--/#gallery-->

						    <!--schedule-->
							<?php 
							if(is_array($vehicleschedule)) {
								if(!empty($vehicleschedule)) { ?>	
								  <div class="tab-pane fade in" id="schedule">
								  	<div class="vehicle-schedule">
									  	<table class="table table-bordered table-responsive time-schedule">
									  		<thead>
											<tr>
												<th><?php esc_html_e( 'Pickup Time','travelkit' ); ?></th>
												<th><?php esc_html_e( 'Drop Time ','travelkit' ); ?></th>
											</tr>
											</thead>
											<tbody>
												
												  	<?php foreach ( $vehicleschedule as $value) { ?>
												  		<tr>
									                   		<?php if( !empty($value["pickup"]) ) { ?>
																<td><?php  echo esc_html($value['pickup']);?></td>
															<?php } ?>	
															<?php if( !empty($value["drop"]) ) { ?>
																<td><?php echo esc_html($value['drop']);?></td>
															<?php } ?>
														</tr>
													<?php } ?>
												
											</tbody>
										</table>
									</div><!--/.vehicle-quick-contact-->						  
								  </div><!--/.tab-pane-->
						    	<?php } ?>
					    	<?php } ?>
						    <!--/#schedule-->

						</div><!--/.package-tab-content-->

					</div> <!-- //col-sm-9 -->

					<!--Sidebar-->
					<div class="col-sm-3">
						<div class="package-sidebar <?php echo esc_attr( $sidebar_cls ); ?>">
							<h3 class="title"><?php esc_html_e( 'Additional Info','travelkit' ); ?></h3>
							<ul>								
								<?php if( $vehiclename !='' ){ ?>	
									<li><span><?php esc_html_e('Vehicle Name : ','travelkit');?></span> <?php the_title();?> </li>
								<?php }?>									
								<?php 

								 $cats = get_the_terms($post->ID, 'vehicle-category');
								 foreach ($cats as $cat) { ?>
								 	<li><span><?php esc_html_e('Vehicle Type : ','travelkit');?></span> <a href="<?php echo get_term_link($cat); ?>"><?php echo esc_html($cat->name);?></a> </li>	
								 <?php } ?>

								<?php if( $passenger !='' ){ ?>	
									<li><span><?php esc_html_e('Passenger : ','travelkit');?> </span> <?php echo esc_html($passenger);?> </li>
								<?php }?>
								<?php if( $vehicleprice && $vehicleduration ){ ?>
									<li>
										<span><?php esc_html_e('Price : ','travelkit');?></span>
										<?php
											if (!get_theme_mod( 'currency_right' )){
												echo get_theme_mod( 'other_currency', '$' ).$vehicleprice.$vehicleduration;
											} else {
												echo $vehicleprice.get_theme_mod( 'other_currency', '$' ).$vehicleduration;
											}
										?>
									</li>
								<?php }?>								
							</ul>

							<div class="need-help">
								<h3 class="title"><?php esc_html_e( 'Need Help!','travelkit' ); ?></h3>
								<?php if( $vehiclehelptext !='' ){ ?>
									<div><?php echo esc_html($vehiclehelptext);?></div>
								<?php }?>						
								<?php if( $vehiclecontactnum !='' ){ ?>
									<p><i class="fa fa-phone-square"></i><?php echo esc_html($vehiclecontactnum);?></p>
								<?php }?>
								<?php if( $vehiclecontactemail !='' ){ ?>	
									<p><i class="fa fa-envelope-square"></i><?php print $vehiclecontactemail;?> </li></p>
								<?php }?>
							</div>	
						</div>
					</div>
				</div><!--/.packagedetailin-->		
			</div><!--/.row-->		

				<!-- Recommend Package -->
				<div class="recommend-package">
				
					<h3 class="title"><?php esc_html_e( 'Recommend Vehicle','travelkit' ); ?></h3>
					<div class="row">
						<?php
						$tag_list = "";
						$posttags = get_the_tags($post->ID);
						if ($posttags) {
							foreach($posttags as $tag) {
							    $tag_list .= ',' . $tag->name; 
							}
						}
						$tag_list = substr($tag_list, 1); // remove first comma
						$arr = array(
										'post_type' 	=> 'vehicle',
										'tag'			=> $tag_list,
										'posts_per_page'=> 6,
										'post__not_in'	=> array(get_the_ID())
									);
						query_posts( $arr ); 
						while (have_posts()) : the_post();

							$vehicleprice  			= get_post_meta(get_the_ID(),'themeum_vehicleprice',true);
							$vehicleduration  			= get_post_meta(get_the_ID(),'themeum_vehicleduration',true);
							if ($vehicleduration != '') {
								$vehicleduration = '/'.$vehicleduration;
							}
						?>

						<div class="col-md-4 col-sm-6">
							<div class="package-list-wrap">
								<?php the_post_thumbnail('travelkit-medium', array('class' => 'img-responsive')); ?>
								<div class="package-list-content">
									<?php if( $vehicleprice !='' ){ ?>
										<?php if (!get_theme_mod( 'currency_right' )): ?>
											<p class="package-list-duration"><?php esc_html_e('Start from','travelkit')?> <?php echo get_theme_mod( 'other_currency', '$' ).$vehicleprice.$vehicleduration;?></p>
										<?php else: ?>
											<p class="package-list-duration"><?php esc_html_e('Start from','travelkit')?> <?php echo $vehicleprice.get_theme_mod( 'other_currency', '$' ).$vehicleduration;?></p>
										<?php endif; ?>
									<?php }?>									
									<h3 class="package-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								
									<!-- View Details -->
									<?php $view_details = get_theme_mod('vehicle_booknow_btn');
									if (isset($view_details) && $view_details != '') {  ?>
										<a class="package-list-button" href="<?php the_permalink(); ?>"><?php echo $view_details; ?> </a>
									<?php }else { ?>
										<a class="package-list-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Book Now','travelkit' ); ?> </a>
									<?php } ?>
									<!-- View Details End -->
									
								</div>
							</div><!--/.package-list-wrap--> 
						</div> <!--/.col-md-4 col-sm-6-->

						<?php

						endwhile;
						wp_reset_query(); ?>
					</div> <!-- //row -->
				</div> <!-- //recommend-package -->
			</div><!--/.container-->
		</div><!--/.package-details-wrap-->
		<?php 

		endwhile;
	wp_reset_query();
	?>
</section>
<?php
    $count_key = 'post_views_count';
    $count = get_post_meta($post->ID, 'post_views_count', true);
    if($count==''){
        $count = 0;
        delete_post_meta($post->ID, 'post_views_count');
        add_post_meta($post->ID, 'post_views_count', '0');
    }else{
        $count++;
        update_post_meta($post->ID, 'post_views_count', $count);
    }
?>
<?php get_footer();