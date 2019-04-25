<?php get_header(); ?>

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


	    	if (function_exists('thm_paypal_form')) {
	    		thm_paypal_form();
	    	}
	    ?>
	</div>


	<?php

	//additional info
	$packcheckin  = get_post_meta(get_the_ID(),'themeum_packcheckin',true);
	if (!empty($packcheckin)) {
		$packcheckin = date_i18n( 'd F, Y' ,strtotime($packcheckin) );
	}
	
	$packcheckout  = get_post_meta(get_the_ID(),'themeum_packcheckout',true);
	if (!empty($packcheckout)) {
		$packcheckout = date_i18n( 'd F, Y' ,strtotime($packcheckout) );
	}
	
	$packduration  		= get_post_meta(get_the_ID(),'themeum_packduration',true);
	$packperson  		= get_post_meta(get_the_ID(),'themeum_packperson',true);
	$packkids  			= get_post_meta(get_the_ID(),'themeum_packkids',true);
	$packavailability  	= get_post_meta(get_the_ID(),'themeum_packavailability',true);
	$packprice  		= get_post_meta(get_the_ID(),'themeum_packprice',true);
	$packlocation  		= get_post_meta(get_the_ID(),'themeum_packlocation',true);
	$packhelptext  		= get_post_meta(get_the_ID(),'themeum_packhelptext',true);
	$packcontactnum  	= get_post_meta(get_the_ID(),'themeum_packcontactnum',true);
	$packcontactemail  	= get_post_meta(get_the_ID(),'themeum_packcontactemail',true);

	# details info
	$packgallery 		= get_post_meta(get_the_ID(), 'themeum_packgallery');
	$packchooselist 	= get_post_meta(get_the_ID(), 'themeum_packchooselist',true);
	# itinerary
	$itinerary_info   	= get_post_meta(get_the_ID(),'themeum_package_itinerary_info',true);
	# tour guide
	$tour_guid   	 	= get_post_meta(get_the_ID(),'themeum_package_tour_guide',true);
	# hotel info
	$hotel_info   	 	= get_post_meta(get_the_ID(),'packhotel');

	# toue video
	$video_info   	 	= get_post_meta(get_the_ID(),'themeum_package_video_info',true);
	$external_link   	= get_post_meta(get_the_ID(),'themeum_external_link',true);
	$book_now_link 		= (empty($external_link)) ? '#buynowbtn' : $external_link ;
	$book_now_cls 		= (empty($external_link)) ? 'buynowbtn' : '' ;

	# map
	$map = rwmb_meta('themeum_packnmap');

	if(!get_theme_mod( 'currency_right' )){
		$packprice = get_theme_mod( 'package_currency', '$' ).$packprice;
	} else {
		$packprice = $packprice.get_theme_mod( 'package_currency', '$' );
	}
	
	$packbooknow   	 	= get_post_meta(get_the_ID(),'themeum_packbookingtrigger',true);
	$packmaptrigger   	= get_post_meta(get_the_ID(),'themeum_packmaptrigger',true);
	$booknow_btn		= get_post_meta(get_the_ID(),'themeum_packbooknow',true);

	$packbooknow 	= (int) $packbooknow;
	$packmaptrigger = (int) $packmaptrigger;

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

	<div class="package-details-wrap">
		<div class="container" >
			<div class="row">
				<div class="packagedetailin clearfix">
					<?php if($packbooknow === 0): ?>
						<div class="pull-right">
							<?php  
							if (isset($booknow_btn) && $booknow_btn != '') { ?>
								<a href="<?php echo esc_attr( $book_now_link ); ?>" class="<?php echo esc_attr( $book_now_cls ); ?> btn btn-success" data-effect="mfp-zoom-in"><?php echo $booknow_btn; ?></a>
		                	<?php }else { ?>
								<a href="<?php echo esc_attr( $book_now_link ); ?>" class="<?php echo esc_attr( $book_now_cls ); ?> btn btn-success" data-effect="mfp-zoom-in"><?php esc_html_e('Book Now','travelkit');?></a>
		                	<?php } ?>
						</div>
					<?php endif; ?>
					<div class="col-sm-9">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs package-nav-tab" role="tablist">
						    <li class="active"><a href="#tourinfo" role="tab" data-toggle="tab"><?php esc_html_e('Tour Info','travelkit');?></a></li>
						  	<?php if( is_array(($itinerary_info)) ) { ?>
							    <?php if(!empty($itinerary_info)) { ?>
							    	<li><a href="#itinerary" role="tab" data-toggle="tab"><?php esc_html_e('Tour Itinerary','travelkit');?></a></li>
					            <?php } ?>
				            <?php } ?>
							<?php if( is_array(($tour_guid)) ) { ?>
								<?php if(!empty($tour_guid)) { ?>
						    		<li><a href="#guide" role="tab" data-toggle="tab"><?php esc_html_e('Tour Guide','travelkit');?></a></li>
					            <?php } ?>
				            <?php } ?>
							<?php if( is_array(($hotel_info)) ) { ?>
								<?php if(!empty($hotel_info)) { ?>
						    		<li><a href="#hotelinfo" role="tab" data-toggle="tab"><?php esc_html_e('Hotel Information','travelkit');?></a></li>
					            <?php } ?>
				            <?php } ?>
							<?php if( is_array(($video_info)) ) { ?>
								<?php if(!empty($video_info)) { ?>
						    		<li><a href="#tourvideo" role="tab" data-toggle="tab"><?php esc_html_e('Videos','travelkit');?></a></li>
					            <?php } ?>
				            <?php } ?>
						</ul><!--/.package-nav-tab-->

						<div class="tab-content package-tab-content">

						    <!--tourinfo-->
						    <div class="tab-pane fade in active" id="tourinfo">
						    	<?php if ( is_array($packgallery)) { ?>
									<?php if(!empty($packgallery)) { ?>
									  	<div class="package-details-gallery">
								            <div class="row margin-bottom photo-gallery-item">
									            <?php
								            	$limit  = 12;
								            	$slices = (count($packgallery) > $limit) ? array_slice($packgallery, 0, $limit , true ) : $packgallery;
							                    foreach ($slices as $key=>$photo) {
								                    $photo_thumb = wp_get_attachment_image_src( $photo, 'travelkit-medium' );
								                    $photo_thumb_full = wp_get_attachment_image_src( $photo, 'full_url' ); ?>

								                    <div class="photo-gallery-items col-sm-6 col-md-4">
								                        <div class="gallery-items-img">
								                           <a href="<?php echo esc_url($photo_thumb_full[0]);?>" class="plus-icon"><img src="<?php echo esc_url($photo_thumb[0]);?>" class="img-responsive" alt="<?php esc_html_e('photo : ','travelkit');?>" /></a>
								                        </div><!--/.gallery-items-img-->
								                    </div> <!--/.col-md-3-->
							                    <?php }
											?>
										</div><!--/.row-->
								  	</div><!--/.package-details-gallery-->
							  	<?php } ?>
				                <?php } ?>
							  	<div class="package-details-content">
							  		<h3 class="title"><?php esc_html_e( 'Tour Details','travelkit' ); ?></h3>
									<?php the_content(); ?>

									<?php if ( is_array($packchooselist)) { ?>
										<?php if(!empty($packchooselist)) { ?>
											<div class="package-details-choose">
												<h3 class="title"><?php esc_html_e( 'Why you choose this package!','travelkit' ); ?></h3>
												<ul class="clearfix">
													<?php foreach ($packchooselist as $value) { ?>
								                        <li><span><i class="fa fa-check"></i><?php echo esc_html($value); ?> </span></li>
								                    <?php } ?>
							                    </ul>
						                    </div><!--/.package-details-choose-->
					                    <?php } ?>
				                    <?php } ?>

									<?php  if( isset($map) && !empty($map) && $packmaptrigger === 0 ){?>
									  	<div class="package-details-tourmap">
											<?php print $map; ?>
										</div><!--/.package-details-map-->
								    <?php } ?>

								</div><!--/.package-details-content-->
						    </div><!--/.tab-pane-->
						    <!--/#tourinfo-->

						    <!--itinerary-->
							<?php if( is_array(($itinerary_info)) ) { ?>
								<?php if(!empty($itinerary_info)) { ?>
								    <div class="tab-pane fade in" id="itinerary">
								    	<div class="package-details-itinerary">
											<?php foreach( $itinerary_info as $value ){ ?>
													<div class="media common-media-list">
														<?php  if( isset($value["themeum_packhotelgallery"]) && !empty($value["themeum_packhotelgallery"]) ){?>
															<div class="pull-left">
															<?php $image = $value["themeum_packhotelgallery"];
																$img   = wp_get_attachment_image_src($image[0], 'travelkit-medium');
															?>
															<img class="img-responsive" src="<?php echo esc_url($img[0]);?>" alt="<?php esc_html_e('photo', 'travelkit');?>">
															</div><!--/.pull-left-->
														<?php } ?>

														<div class="media-body">
															<?php if( (isset($value["themeum_packitineraryday"]) && !empty($value["themeum_packitineraryday"])) || (isset($value["themeum_packitineraryname"]) && !empty($value["themeum_packitineraryname"]))  ){ ?>
																<h3 class="title"> <strong><?php echo esc_attr($value["themeum_packitineraryday"]);?>,</strong><?php echo esc_html($value["themeum_packitineraryname"]);?></h3>
															<?php } ?>
															<?php if( isset($value["themeum_packitineraryintro"]) && !empty($value["themeum_packitineraryintro"]) ){ ?>
																<div class="media-body-content">
																	<?php echo wp_kses_post($value["themeum_packitineraryintro"]);?>
																</div><!--/.media-body-content-->
															<?php } ?>
														</div><!--/.media-body-->
													</div><!--/.media-->
							                <?php } ?>
										</div><!--/.package-details-itinerary-->
								    </div><!--/.tab-pane-->
						    	<?php } ?>
					        <?php } ?>
						    <!--/#itinerary-->

						    <!--guide-->
							<?php if( is_array(($tour_guid)) ) { ?>
								<?php if(!empty($tour_guid)) { ?>
								  <div class="tab-pane fade in" id="guide">
								  	<div class="package-details-guide">
										<?php foreach( $tour_guid as $value ){ ?>
												<div class="media common-media-list">
													<?php  if( isset($value["themeum_packguideimg"]) && !empty($value["themeum_packguideimg"]) ){?>
														<div class="pull-left">
														<?php $image = $value["themeum_packguideimg"];
															$img   = wp_get_attachment_image_src($image[0], 'travelkit-medium');
														?>
														<img class="img-responsive" src="<?php echo esc_url($img[0]);?>" alt="<?php esc_html_e('photo', 'travelkit');?>">
														</div><!--/.pull-left-->
													<?php } ?>

													<div class="media-body">
														<?php if( isset($value["themeum_packguidename"]) && !empty($value["themeum_packguidename"]) ){ ?>
															<h3 class="title"><?php echo esc_html($value["themeum_packguidename"]);?></h3>
														<?php }?>
														<?php if( isset($value["themeum_packguideintro"]) && !empty($value["themeum_packguideintro"]) ) { ?>
															<div class="media-body-content">
																<?php echo esc_html($value["themeum_packguideintro"]);?>
															</div><!--/.media-body-content-->
														<?php }?>
														<ul class="package-share">
															<?php if( isset($value["themeum_packguidefb_url"]) && !empty($value["themeum_packguidefb_url"]) ){ ?>
																<li><a href="<?php echo esc_url($value["themeum_packguidefb_url"]);?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
															<?php }?>
															<?php if( isset($value["themeum_packguidetw_url"]) && !empty($value["themeum_packguidetw_url"]) ){ ?>
																<li><a href="<?php echo esc_url($value["themeum_packguidetw_url"]);?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
															<?php }?>
															<?php if( isset($value["themeum_packguidegplus_url"])  && !empty($value["themeum_packguidegplus_url"]) ){ ?>
																<li><a href="<?php echo esc_url($value["themeum_packguidegplus_url"]);?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
															<?php }?>
															<?php if( isset($value["themeum_packguideyoutube_url"]) && !empty($value["themeum_packguideyoutube_url"]) ){ ?>
																<li><a href="<?php echo esc_url($value["themeum_packguideyoutube_url"]);?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
															<?php }?>
															<?php if( isset($value["themeum_packguideinstagram_url"]) && !empty($value["themeum_packguideinstagram_url"])  ){ ?>
																<li><a href="<?php echo esc_url($value["themeum_packguideinstagram_url"]);?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
															<?php }?>
															<?php if( isset($value["themeum_packguidewebsite_url"]) && !empty($value["themeum_packguidewebsite_url"]) ){ ?>
																<li><a href="<?php echo esc_url($value["themeum_packguidewebsite_url"]);?>" target="_blank"><i class="fa fa-globe"></i></a></li>
															<?php }?>
														</ul>

													</div><!--/.media-body-->
												</div><!--/.media-->
						                <?php } ?>
									</div><!--/.package-details-guide-->
								  </div><!--/.tab-pane-->
						    	<?php } ?>
					        <?php } ?>
						    <!--/#guide-->

						    <!--Hotel Info-->
							<?php if( is_array(($hotel_info)) ) { ?>
								<?php if(!empty($hotel_info)) { ?>
								  <div class="tab-pane fade in" id="hotelinfo">
								  	<div class="package-details-hotel">

								  	    <?php $posts_id = array();

		                                  foreach ( $hotel_info as $value ) {
		                                    $posts_id[] = $value;
		                                  }
		                                  $hotel_info = get_posts( array( 'post_type' => 'hotel', 'post__in' => $posts_id, 'posts_per_page'   => 20) );

		                                ?>
										<?php foreach($hotel_info as $key=>$post ){
											
											$hotelefb_url  			= get_post_meta(get_the_ID(),'themeum_hotelefb_url',true);
											$hoteletw_url  			= get_post_meta(get_the_ID(),'themeum_hoteletw_url',true);
											$hotelegplus_url  		= get_post_meta(get_the_ID(),'themeum_hotelegplus_url',true);
											$hoteleyoutube_url  	= get_post_meta(get_the_ID(),'themeum_hoteleyoutube_url',true);
											$hoteleinstagram_url  	= get_post_meta(get_the_ID(),'themeum_hoteleinstagram_url',true);
											$hotelewebsite_url  	= get_post_meta(get_the_ID(),'themeum_hotelewebsite_url',true);
											$hotelintro  			= get_post_meta(get_the_ID(),'themeum_hotelintro',true); ?>

											<div class="media common-media-list">
													<?php if ( has_post_thumbnail() && ! post_password_required() ) { ?>
														<?php  $img =  wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'travelkit-medium' ); ?>
													<div class="pull-left">
													 <a href="<?php the_permalink();?>"><img class="img-responsive" src="<?php echo esc_url($img[0]);?>" alt="<?php esc_html_e('photo', 'travelkit');?>"></a>
													</div><!--/.pull-left-->
													<?php } ?>


												<div class="media-body">
													<h3 class="title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
													<?php if( isset($hotelintro) && !empty($hotelintro) ) { ?>
														<div class="media-body-content">
															<?php  echo esc_html($hotelintro); ?>
														</div><!--/.media-body-content-->
													<?php }?>

													<ul class="package-share">
														<?php if( isset($hotelefb_url) && !empty($hotelefb_url) ){ ?>
															<li><a href="<?php echo esc_url($hotelefb_url);?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
														<?php }?>
														<?php if( isset($hoteletw_url) && !empty($hoteletw_url) ){ ?>
															<li><a href="<?php echo esc_url($hoteletw_url);?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
														<?php }?>
														<?php if( isset($hotelegplus_url) && !empty($hotelegplus_url) ){ ?>
															<li><a href="<?php echo esc_url($hotelegplus_url);?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
														<?php }?>
														<?php if( isset($hoteleyoutube_url) && !empty($hoteleyoutube_url) ){ ?>
															<li><a href="<?php echo esc_url($hoteleyoutube_url);?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
														<?php }?>
														<?php if( isset($hoteleinstagram_url) && !empty($hoteleinstagram_url) ){ ?>
															<li><a href="<?php echo esc_url($hoteleinstagram_url);?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
														<?php }?>
														<?php if( isset($hotelewebsite_url) && !empty($hotelewebsite_url) ){ ?>
															<li><a href="<?php echo esc_url($hotelewebsite_url);?>" target="_blank"><i class="fa fa-globe"></i></a></li>
														<?php }?>
													</ul>
												</div><!--/.media-body-->
											</div><!--/.media-->
						                <?php } ?>
									</div><!--/.package-details-hotel-->
								  </div><!--/.tab-pane-->
						    	<?php } ?>
					        <?php } ?>
						    <!--/#Hotel Info-->

						    <!--Tour Video-->
							<?php if( is_array(($video_info)) ) { ?>
								<?php if(!empty($video_info)) { ?>
								  <div class="tab-pane fade in" id="tourvideo">
								  	<div class="package-details-video">
									  	<div class="row">
											<?php
											$i = 1;
											foreach( $video_info as $value ){
												$video_type = $value["themeum_packvideosource"];
												if ( $i == '1' ) {
												?>
												<div class="col-sm-12">
										            <div class="tour-video leading">
														<?php  if( isset($value["themeum_packvideoimg"]) && !empty($value["themeum_packvideoimg"]) ){?>
															<?php $image = $value["themeum_packvideoimg"];
																$img   = wp_get_attachment_image_src($image[0], 'travelkit-large');
															?>
															<img class="img-responsive" src="<?php echo esc_url($img[0]);?>" alt="<?php esc_html_e('photo', 'travelkit');?>">
														<?php } ?>
														<?php
														if ( isset($value["themeum_packvideolink"]) &&  !empty($value["themeum_packvideolink"]) ) {
											              	if($video_type == 'youtube'){?>
											              		<a class="btn-video" href="<?php echo esc_attr($value["themeum_packvideolink"]);?>"><i class="fa fa-play-circle-o"></i></a>
											              	<?php } elseif ($video_type == 'vimeo') {?>
											                  <a class="btn-video" href="<?php echo esc_attr($value["themeum_packvideolink"]);?>"><i class="fa fa-play-circle-o"></i></a>
											                <?php } else{ ?>
											                  <a class="btn-video" href="<?php echo esc_attr($value["themeum_packvideolink"]);?>"><i class="fa fa-play-circle-o"></i></a>
											               	<?php }?>
										               	<?php }?>

										               	<?php if( isset($value["themeum_packvideotitle"]) && !empty($value["themeum_packvideotitle"]) ){ ?>
															<h3 class="title"><?php echo esc_attr($value["themeum_packvideotitle"]);?></h3>
														<?php } ?>
													</div><!--/.tour-video-->
												</div><!--/.tour-video-->
							                <?php } else { ?>
								                <div class="col-sm-4">
										            <div class="tour-video">
														<?php  if( isset($value["themeum_packvideoimg"]) && !empty($value["themeum_packvideoimg"]) ){?>
															<?php $image = $value["themeum_packvideoimg"];
																$img   = wp_get_attachment_image_src($image[0], 'travelkit-medium');
															?>
															<img class="img-responsive" src="<?php echo esc_url($img[0]);?>" alt="<?php esc_html_e('photo', 'travelkit');?>">
														<?php } ?>
														<?php
														if ( isset($value["themeum_packvideolink"]) &&  !empty($value["themeum_packvideolink"]) ) {
											              	if($video_type == 'youtube'){?>
											              		<a class="btn-video" href="<?php echo esc_attr($value["themeum_packvideolink"]);?>"><i class="fa fa-play-circle-o"></i></a>
											              	<?php } elseif ($video_type == 'vimeo') {?>
											                  <a class="btn-video" href="<?php echo esc_attr($value["themeum_packvideolink"]);?>"><i class="fa fa-play-circle-o"></i></a>
											                <?php } else{ ?>
											                  <a class="btn-video" href="<?php echo esc_attr($value["themeum_packvideolink"]);?>"><i class="fa fa-play-circle-o"></i></a>
											               	<?php }?>
										               	<?php }?>

										               	<?php if( isset($value["themeum_packvideotitle"]) && !empty($value["themeum_packvideotitle"]) ){ ?>
															<h3 class="title"><?php echo esc_attr($value["themeum_packvideotitle"]);?></h3>
														<?php } ?>
													</div><!--/.tour-video-->
												</div><!--/.col-sm-3-->
							                	<?php } ?>
							                	<?php $i++; ?>
							                <?php } ?>
										</div><!--/.row-->
									</div><!--/.package-details-video-->
								  </div><!--/.tab-pane-->
						    	<?php } ?>
					        <?php } ?>
						    <!--/#Tour Video-->
						</div><!--/.package-tab-content-->

					</div> <!-- //col-sm-9 -->

					<!--Sidebar-->
					<div class="col-sm-3">
						<div class="package-sidebar <?php echo esc_attr( $sidebar_cls ); ?>">
							<h3 class="title"><?php esc_html_e( 'Additional Info','travelkit' ); ?></h3>
							<ul>
								<?php if( $packcheckin !='' ){ ?>
									<li><span><?php esc_html_e('Check In : ','travelkit');?></span> <?php echo esc_html($packcheckin);?> </li>
								<?php }?>
								<?php if( $packcheckout !='' ){ ?>
									<li><span><?php esc_html_e('Check Out : ','travelkit');?></span> <?php echo esc_html($packcheckout);?> </li>
								<?php }?>
								<?php if( $packduration !='' ){ ?>
									<li><span><?php esc_html_e('Duration : ','travelkit');?></span> <?php echo esc_html($packduration);?> </li>
								<?php }?>
								<?php if( $packperson !='' ){ ?>
									<li><span><?php esc_html_e('Person : ','travelkit');?> </span> <?php echo esc_html($packperson);?> </li>
								<?php }?>
								<?php if( $packkids !='' ){ ?>
									<li><span><?php esc_html_e('Kids : ','travelkit');?> </span> <?php echo esc_html($packkids);?> </li>
								<?php }?>
								<?php if( $packavailability !='' ){ ?>
									<li><span><?php esc_html_e('Availability : ','travelkit');?> </span> <?php echo esc_html($packavailability);?> </li>
								<?php }?>
								<?php if( $packprice !='' ){ ?>
									<li><span><?php esc_html_e('Price : ','travelkit');?></span> <?php echo esc_html($packprice);?> </li>
								<?php }?>
								<?php if( $packlocation !='' ){ ?>
									<li><span><?php esc_html_e('Location : ','travelkit');?></span> <?php echo esc_html($packlocation);?> </li>
								<?php }?>
							</ul>

							<div class="need-help">
								<h3 class="title"><?php esc_html_e( 'Need Help!','travelkit' ); ?></h3>
								<?php if( $packhelptext !='' ){ ?>
									<div><?php echo esc_html($packhelptext);?></div>
								<?php }?>
								<?php if( $packcontactnum !='' ){ ?>
									<p><i class="fa fa-phone-square"></i><?php echo esc_html($packcontactnum);?></p>
								<?php }?>
								<?php if( $packcontactemail !='' ){ ?>
									<p><i class="fa fa-envelope-square"></i><?php print $packcontactemail;?> </li></p>
								<?php }?>
							</div>
						</div>
					</div>
				</div><!--/.packagedetailin-->
			</div><!--/.row-->

				<!-- Recommend Package -->
				<div class="recommend-package">

					<h3 class="title"><?php esc_html_e( 'Recommend Tour Packages','travelkit' ); ?></h3>
					<div class="row">
						<?php
							$tag_list = "";
							$posttags = get_the_tags();
							if ($posttags) {
								foreach($posttags as $tag) {
								    $tag_list .= ',' . $tag->name;
								}
							}
							$tag_list = substr($tag_list, 1); # remove first comma
							$arr = array(
								'post_type' 	=> 'package',
								'tag'			=> $tag_list,
								'posts_per_page'=> 6,
								'post__not_in'	=> array(get_the_ID())
							);
							query_posts( $arr );
							while (have_posts()) : the_post();
							$packduration  	= get_post_meta(get_the_ID(),'themeum_packduration',true);
							$no_thumb 		= '';
							if (!has_post_thumbnail(get_the_id())) {
								$no_thumb = 'package-list-no-thumb';
							}
						?>

						<div class="col-md-4">
							<div class="package-list-wrap <?php echo esc_attr($no_thumb); ?>">
								<?php the_post_thumbnail('travelkit-medium', array('class' => 'img-responsive')); ?>
								<div class="package-list-content">
									<?php if(!get_theme_mod( 'currency_right' )): ?>
										<p class="package-list-duration"><?php echo sprintf('%s Start From %s', $packduration, get_theme_mod( 'package_currency', '$' ).get_post_meta(get_the_id(),'themeum_packprice',true)); ?></p>
									<?php else: ?>
										<p class="package-list-duration"><?php echo sprintf('%s Start From %s', $packduration, get_post_meta(get_the_id(),'themeum_packprice',true).get_theme_mod( 'package_currency', '$' )); ?></p>
									<?php endif; ?>
									<h3 class="package-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

									<!-- Book Now BTN -->
									<?php $btn_button = get_theme_mod('booknow_btn');
									if (isset($btn_button) && $btn_button != '') { ?>
										<a class="package-list-button" href="<?php echo get_the_permalink();?>"><?php echo $btn_button; ?></a>
				                	<?php }else { ?>
										<a class="package-list-button" href="<?php echo get_the_permalink();?>"><?php esc_html_e('Book Now','travelkit')?></a>
				                	<?php } ?>
				                	<!-- End BTN -->

								</div>
							</div><!--/.package-list-wrap-->
						</div> <!--/.col-md-3-->

						<?php
							endwhile;
							wp_reset_query();
						?>
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

<?php get_footer(); ?>
