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

							$packduration  = get_post_meta(get_the_ID(),'themeum_packduration',true);
						?>
						<?php
							$vehicleprice  = get_post_meta(get_the_ID(),'themeum_vehicleprice',true);
							$vehicleduration  = get_post_meta(get_the_ID(),'themeum_vehicleduration',true);
							if ($vehicleduration) {
								$vehicleduration = '/'.$vehicleduration;
							}


							$the_permalink = get_the_permalink();
							$the_permalink = add_query_arg(
								array(
									'booking' => 1,
									'pickuptime' => isset($_GET['pickup']) ? $_GET['pickup'] : '',
									'droptime' => isset($_GET['droptime']) ? $_GET['droptime'] : '',
									'pickup_location' => isset($_GET['pickup_location']) ? $_GET['pickup_location'] : '',
									'drop_location' => isset($_GET['drop_location']) ? $_GET['drop_location'] : '',
								),
								$the_permalink
							);

						?>
						<div class="col-sm-6 col-lg-4">
							<div class="package-list-wrap">
										<?php echo get_the_post_thumbnail(get_the_ID(),'travelkit-medium', array('class' => 'img-responsive')); ?>
								<div class="package-list-content">
										<?php if ($vehicleprice != '') { ?>
											<?php if (!get_theme_mod( 'currency_right' )): ?>
												<p class="package-list-duration"><?php esc_html_e('Start from','travelkit')?> <?php echo get_theme_mod( 'other_currency', '$' ).$vehicleprice.$vehicleduration;?></p>
											<?php else: ?>
												<p class="package-list-duration"><?php esc_html_e('Start from','travelkit')?> <?php echo $vehicleprice.get_theme_mod( 'other_currency', '$' ).$vehicleduration;?></p>
											<?php endif; ?>
										<?php } ?>                         
										<h3 class="package-list-title"><a href="<?php echo esc_url($the_permalink); ?>"><?php echo get_the_title(); ?></a></h3>
										<a class="package-list-button" href="<?php echo esc_url($the_permalink);?>"><?php esc_html_e('Book Now','travelkit')?></a>
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
						<p><?php esc_html_e( 'Sorry no Vehicle found. Please try again.','travelkit' ); ?></p>
					</div>
					
				<?php endif; ?>
			</div>
		</div>
	</div>
</section> 