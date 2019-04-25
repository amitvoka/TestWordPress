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
						<div class="col-sm-6 col-lg-4">
							<div class="package-list-wrap">
										<?php echo get_the_post_thumbnail(get_the_ID(),'travelkit-medium', array('class' => 'img-responsive')); ?>
								<div class="package-list-content">
										<?php if ($packduration != '') { ?>
											<?php if(!get_theme_mod( 'currency_right' )): ?>
												<p class="package-list-duration"><?php echo sprintf('%s Start From %s', $packduration, get_theme_mod( 'package_currency', '$' ).get_post_meta(get_the_id(),'themeum_packprice',true)); ?></p>
											<?php else: ?>
												<p class="package-list-duration"><?php echo sprintf('%s Start From %s', $packduration, get_post_meta(get_the_id(),'themeum_packprice',true).get_theme_mod( 'package_currency', '$' )); ?></p>
											<?php endif; ?>
										<?php } ?>                         
										<h3 class="package-list-title"><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
										
										<!-- Book Now Button -->
										<?php 
											$btn_button = get_theme_mod('booknow_btn');
											if ( isset($btn_button) && $btn_button  != '') { ?>
												<a class="package-list-button" href="<?php echo get_the_permalink();?>"><?php echo $btn_button; ?></a>
											<?php }else { ?>
												<a class="package-list-button" href="<?php echo get_the_permalink();?>"><?php esc_html_e('Book Now','travelkit')?></a>
											<?php }
										?>
										<!-- End BTN -->
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
						<p><?php esc_html_e( 'Sorry no package found. Please try again.','travelkit' ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section> 