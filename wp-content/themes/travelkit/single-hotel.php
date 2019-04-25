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
	    ?>
	</div>


	<?php

	#additional info
	$hotelcheckin  		= get_post_meta(get_the_ID(),'themeum_hotelcheckin',true);
	$hotelcheckout  	= get_post_meta(get_the_ID(),'themeum_hotelcheckout',true);
	$hotelduration  	= get_post_meta(get_the_ID(),'themeum_hotelduration',true);
	$hotelroom  		= get_post_meta(get_the_ID(),'themeum_hotelroom',true);
	$hotelhelptext  	= get_post_meta(get_the_ID(),'themeum_hotelhelptext',true);
	$packcontactnum  	= get_post_meta(get_the_ID(),'themeum_hotelcontactnum',true);
	$packcontactemail  	= get_post_meta(get_the_ID(),'themeum_hotelcontactemail',true);

	# details info
	$packgallery 		= get_post_meta(get_the_ID(), 'themeum_packgallery');
	$packchooselist 	= get_post_meta(get_the_ID(), 'themeum_packchooselist',true);

	# itinerary
	$itinerary_info   	= get_post_meta(get_the_ID(),'themeum_package_itinerary_info',true);

	#tour guide
	$tour_guid   	 	= get_post_meta(get_the_ID(),'themeum_package_tour_guide',true);

	# hotel info
	$hotel_info   	 	= get_post_meta(get_the_ID(),'themeum_package_hotel_info',true);

	# toue video
	$video_info   	 	= get_post_meta(get_the_ID(),'themeum_package_video_info',true);
	$themeum_room_info  = get_post_meta(get_the_ID(),'themeum_room_info',true);
	$external_link   	= get_post_meta(get_the_ID(),'themeum_external_link',true);

	# Hotel Book now BTN
	$hotel_booknow 		= get_post_meta(get_the_ID(),'themeum_packbooknow',true);

	$book_now_link 		= (empty($external_link)) ? '#buynowbtn' : $external_link ;
	$book_now_cls 		= (empty($external_link)) ? 'buynowbtn' : '' ;


	$hotel_prices  = get_post_meta(get_the_ID(),'themeum_room_info',true);
	$hotel_price_txt  = '';
	if (!empty($hotel_prices)) {
		$temp_prices = array();

		foreach ($hotel_prices as $hotel_price) {
			if (isset($hotel_price['themeum_roomprice'])) {
				$temp_prices[] = $hotel_price['themeum_roomprice'];
			}
		}

		if (!empty($temp_prices)) {
			if (!get_theme_mod( 'currency_right' )) {
				$hotel_price_txt = get_theme_mod( 'other_currency', '$' ).min($temp_prices).' - '.get_theme_mod( 'other_currency', '$' ).max($temp_prices);
			} else {
				$hotel_price_txt = min($temp_prices).get_theme_mod( 'other_currency', '$' ).' - '.max($temp_prices).get_theme_mod( 'other_currency', '$' );
			}
			
		}
	}

	# map
	$map = rwmb_meta('themeum_packnmap');

	$packbooknow   	 = get_post_meta(get_the_ID(),'themeum_packbookingtrigger',true);
	$packmaptrigger  = get_post_meta(get_the_ID(),'themeum_packmaptrigger',true);

	$packbooknow 	= (int) $packbooknow;
	$packmaptrigger = (int) $packmaptrigger;

	$sidebar_cls = ($packbooknow === 1) ? 'bigtop' : ''; ?>

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
							if (isset($hotel_booknow) && $hotel_booknow != '') { ?>
								<a href="<?php echo esc_attr( $book_now_link ); ?>" class="<?php echo esc_attr( $book_now_cls ); ?> btn btn-success" data-effect="mfp-zoom-in"><?php echo $hotel_booknow; ?></a>
		                	<?php }else { ?>
								<a href="<?php echo esc_attr( $book_now_link ); ?>" class="<?php echo esc_attr( $book_now_cls ); ?> btn btn-success" data-effect="mfp-zoom-in"><?php echo esc_html_e('Book Now','travelkit');?></a>
		                	<?php } ?>
						</div>
					<?php endif; ?>

					<div class="col-sm-9">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs package-nav-tab" role="tablist">
						    <li class="active"><a href="#hoteldetail" role="tab" data-toggle="tab"><?php esc_html_e('Description','travelkit');?></a></li>
						    <li><a href="#roomtype" role="tab" data-toggle="tab"><?php esc_html_e('Room Type','travelkit');?></a></li>
							<?php if( is_array(($video_info)) ) { ?>
								<?php if(!empty($video_info)) { ?>
						    		<li><a href="#hotelvideo" role="tab" data-toggle="tab"><?php esc_html_e('Video','travelkit');?></a></li>
					            <?php } ?>
				            <?php } ?>
						</ul><!--/.package-nav-tab-->

						<div class="tab-content package-tab-content">

						    <!--hoteldetail-->
						    <div class="tab-pane fade in active" id="hoteldetail">
						    	<h3 class="title"><?php echo get_the_title(); ?></h3>
						    	<?php the_content(); ?>

						    	<?php if ( is_array($packgallery)) { ?>
									<?php if(!empty($packgallery)) { ?>

									  	<div class="package-details-gallery hotel-details-gallery">
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

									<?php if ( is_array($packchooselist)) { ?>
										<?php if(!empty($packchooselist)) { ?>
											<div class="package-details-choose">
												<h3 class="title"><?php esc_html_e( 'Hotel Facility','travelkit' ); ?></h3>
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
						    <!--/#hoteldetail-->

						    <!--room info-->
						    <div class="tab-pane fade in" id="roomtype">
						    	<div class="hotel-details-roomtype">
						    	<?php if ( is_array($themeum_room_info)) { ?>
									<?php if(!empty($themeum_room_info)) { ?>
										<div class="row">
											<?php foreach( $themeum_room_info as $value ){ ?>
												<div class="col-sm-6">
													<?php
													if ( !empty($value['themeum_roomgallery']) ) {
													$roomgallery = $value['themeum_roomgallery'];
													$count = count($roomgallery);?>



									  						<div class="package-details-gallery">
													            <?php
													            if ( $count != '1') {
												            		$limit  = 12;
												            		$slices = (count($roomgallery) > $limit) ? array_slice($roomgallery, 0, $limit , true ) : $roomgallery;?>

												                    <div class="photos-gallery owl-carousel">
													                    <?php
													                    foreach ($slices as $key=>$photo) {

														                    $photo_thumb = wp_get_attachment_image_src( $photo, 'travelkit-medium' );
														                    $photo_thumb_full = wp_get_attachment_image_src( $photo, 'full_url' ); ?>
													                        <div class="item">
													                            <a href="<?php echo esc_url($photo_thumb_full[0]);?>" class="plus-icon"><img src="<?php echo esc_url($photo_thumb[0]);?>" class="img-responsive" alt="<?php esc_html_e('photo : ','travelkit');?>" /></a>
													                        </div><!--/.gallery-items-img-->
													                    <?php }  ?>
																	</div> <!--/.room-gallery-->
																<?php } else {
																 	$photo_thumb = wp_get_attachment_image_src( $roomgallery['0'], 'travelkit-medium' );
														            $photo_thumb_full = wp_get_attachment_image_src( $roomgallery['0'], 'full_url' );?>
														            <a href="<?php echo esc_url($photo_thumb_full[0]);?>" class="plus-icon"><img src="<?php echo esc_url($photo_thumb[0]);?>" class="img-responsive" alt="<?php esc_html_e('photo : ','travelkit');?>" /></a>
																<?php } ?>
													  		</div><!--/.package-details-gallery-->
													<?php } ?>
												</div> <!-- /.col-sm-6 -->
												<div class="col-sm-6">
													<div class="hotel-info">
														<?php if ( !empty($value['themeum_roomprice'])) {?>
															<span class="price"> $<?php echo esc_attr($value['themeum_roomprice']); ?> </span>
														<?php }?>
														<?php if( !empty($hotelduration) ){ ?>
															<p class="room-duration"><?php echo esc_attr($hotelduration); ?></p>
														<?php }?>
														<?php if( !empty($value['themeum_hotelroomtype']) ) { ?>
															<p class="room-type"><?php echo esc_attr($value['themeum_hotelroomtype']); ?></p>
														<?php }?>
														<?php if( !empty($value['themeum_hotelroomtext']) ) { ?>
															<p class="room-info"><?php echo esc_html($value['themeum_hotelroomtext']); ?></p>
														<?php } ?>
													</div> <!-- //.hotel-info -->
												</div> <!-- /.col-sm-6 -->
												<div class="clearfix mtb30"></div>
											<?php }?>
										</div>
							  		<?php } ?>
				                <?php } ?>
								</div><!--/.package-details-itinerary-->
						    </div><!--/.tab-pane-->
						    <!--/#roominfo-->


						    <!--Tour Video-->
							<?php if( is_array(($video_info)) ) { ?>
								<?php if(!empty($video_info)) { ?>
								  <div class="tab-pane fade in" id="hotelvideo">
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
															 <a href="<?php echo esc_url($photo_thumb_full[0]);?>" class="plus-icon"><img class="img-responsive" src="<?php echo esc_url($img[0]);?>" alt="<?php esc_html_e('photo', 'travelkit');?>">
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
								                <div class="col-md-4 col-sm-6">
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
												</div><!--/.col-md-4 col-sm-6-->
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
						<div class="package-sidebar  <?php echo esc_attr( $sidebar_cls ); ?>">
							<h3 class="title"><?php esc_html_e( 'Additional Info','travelkit' ); ?></h3>
							<ul>
								<?php if( $hotelcheckin !='' ){ ?>
									<li><span><?php esc_html_e('Check In : ','travelkit');?></span> <?php echo esc_html($hotelcheckin);?> </li>
								<?php }?>
								<?php if( $hotelcheckout !='' ){ ?>
									<li><span><?php esc_html_e('Check Out : ','travelkit');?></span> <?php echo esc_html($hotelcheckout);?> </li>
								<?php }?>
								<?php if( $hotelduration !='' ){ ?>
									<li><span><?php esc_html_e('Pricing : ','travelkit');?></span> <?php echo esc_html($hotel_price_txt); ?>/<?php echo esc_html($hotelduration);?> </li>
								<?php }?>
								<?php if( $hotelroom !='' ){ ?>
									<li><span><?php esc_html_e('Total Room : ','travelkit');?> </span> <?php echo esc_html($hotelroom);?> </li>
								<?php }?>
								<?php
								 $cats = get_the_terms($post->ID, 'hotel-location');
								 foreach ($cats as $cat) { ?>
								 	<li><span><?php esc_html_e('Location : ','travelkit');?></span> <?php echo esc_html($cat->name);?> </li>
								 <?php } ?>
							</ul>

							<div class="need-help">
								<h3 class="title"><?php esc_html_e( 'Need Help!','travelkit' ); ?></h3>
								<?php if( $hotelhelptext !='' ){ ?>
									<div><?php echo esc_html($hotelhelptext);?></div>
								<?php }?>
								<?php if( $packcontactnum !='' ){ ?>
									<p><i class="fa fa-phone-square"></i><?php echo esc_html($packcontactnum);?></p>
								<?php }?>
								<?php if( $packcontactemail !='' ){ ?>
									<p><i class="fa fa-envelope-square"></i><?php echo esc_html($packcontactemail);?> </li></p>
								<?php }?>
							</div>
						</div>
					</div>
				</div><!--/.packagedetailin-->
			</div><!--/.row-->

				<!-- Recommend Package -->
				<div class="recommend-package">

					<h3 class="title"><?php esc_html_e( 'RECOMMENDED HOTELS','travelkit' ); ?></h3>
					<div class="row margin-bottom">
						<?php
						$tag_list = "";
						$posttags = get_the_tags();
						if ($posttags) {
							foreach($posttags as $tag) {
							    $tag_list .= ',' . $tag->name;
							}
						}
						$tag_list = substr($tag_list, 1); // remove first comma
						$arr = array(
										'post_type' 	=> 'hotel',
										'tag'			=> $tag_list,
										'posts_per_page'=> 6,
										'post__not_in'	=> array(get_the_ID())
									);
						query_posts( $arr );
						while (have_posts()) : the_post();

						$hotelduration  = get_post_meta(get_the_ID(),'themeum_hotelduration',true);

						$hotel_prices  = get_post_meta(get_the_ID(),'themeum_room_info',true);
						$hotel_price_txt  = '';
						if (!empty($hotel_prices)) {
							$temp_prices = array();

							foreach ($hotel_prices as $hotel_price) {
								if (isset($hotel_price['themeum_roomprice'])) {
									$temp_prices[] = $hotel_price['themeum_roomprice'];
								}
							}

							if (!empty($temp_prices)) {
								if (!get_theme_mod( 'currency_right' )) {
									$hotel_price_txt = get_theme_mod( 'other_currency', '$' ).min($temp_prices);
								} else {
									$hotel_price_txt = min($temp_prices).get_theme_mod( 'other_currency', '$' );
								}
							}
						}

						$hotelduration = $hotel_price_txt.'/'.$hotelduration;
						?>

						<div class="col-md-4">
							<div class="package-list-wrap">
								<?php the_post_thumbnail('travelkit-medium', array('class' => 'img-responsive')); ?>
								<div class="package-list-content">

									<?php if( $hotelduration !='' ){ ?>
										<p class="package-list-duration"><?php esc_html_e( 'Start From ', 'travelkit' ); ?> <?php echo esc_html($hotelduration);?></p>
									<?php }?>
									<h3 class="package-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									
									<!-- Hotel Book Now BTN -->
									<?php $hotel_book_btn = get_theme_mod('hotel_booknow_btn');
									if ( isset($hotel_book_btn) && $hotel_book_btn != '' ) { ?>
										<a class="package-list-button" href="<?php the_permalink(); ?>"><?php echo $hotel_book_btn; ?></a>
									<?php } else { ?>
										<a class="package-list-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Book Now','travelkit' ); ?></a>
									<?php }	?>
									<!-- Hotel BTN end -->
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
