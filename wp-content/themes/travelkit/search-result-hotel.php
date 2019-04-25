<section id="main" class="clearfix">
	<div class="container">
		<div class="row">
			<div id="content" class="courses col-md-12" role="main">
				<?php
					if ( have_posts() ) :

						$x = 0;
				?>
					<div class="row margin-bottom">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php
							if ($x == 3) {
								$x = 0;
								?>
									</div><div class="row margin-bottom">
								<?php
							}

							$hotel_prices  = get_post_meta(get_the_ID(),'themeum_room_info',true);
							$hotel_price_min  = '';
							if (!empty($hotel_prices)) {
								$temp_prices = array();

								foreach ($hotel_prices as $hotel_price) {
									if (isset($hotel_price['themeum_roomprice'])) {
										$temp_prices[] = $hotel_price['themeum_roomprice'];
									}
								}

								if (!empty($temp_prices)) {
									if (!get_theme_mod( 'currency_right' )) {
										$hotel_price_min = get_theme_mod( 'other_currency', '$' ).min($temp_prices);
									} else {
										$hotel_price_min = min($temp_prices).get_theme_mod( 'other_currency', '$' );
									}
								}

								
							}
						?>
						<div class="col-sm-6 col-lg-4">
							<div class="package-list-wrap">
								<?php echo get_the_post_thumbnail(get_the_ID(),'travelkit-medium', array('class' => 'img-responsive')); ?>
								<div class="package-list-content">
										<?php if ($hotel_price_min != '') { ?>
											<p class="package-list-duration"><?php esc_html_e( 'Start From ', 'travelkit' ); ?> <?php print $hotel_price_min;?> / <?php echo get_post_meta( get_the_ID(),'themeum_hotelduration',true ); ?></p>
										<?php } ?>                         
										<h3 class="package-list-title"><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
										<a class="package-list-button" href="<?php echo get_the_permalink();?>"><?php esc_html_e('Book Now','travelkit')?></a>
								</div><!--/.package-list-content-->
							</div><!-- .package-list-wrap -->
						</div><!-- .col-sm-2 col-md-4 -->

						<?php $x++; ?>
					<?php endwhile; ?>
					</div>
					<?php                                 
						$page_numb = max( 1, get_query_var('paged') );
						$max_page = $wp_query->max_num_pages;
						echo travelkit_pagination( $page_numb, $max_page ); 
					?>
				<?php else: ?>
					<div class="thm-tk-search-notfound">
						<p><?php esc_html_e( 'Sorry no Hotel found. Please try again.','travelkit'); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section> 