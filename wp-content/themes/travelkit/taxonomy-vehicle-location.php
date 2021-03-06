<?php get_header(); ?>

<?php
	get_template_part('lib/sub-header');

	global $wp_query;

	$wp_query->set('posts_per_page', (int) get_theme_mod( 'package_cat_num', 9 ));

	$new_query = new WP_Query($wp_query->query_vars);
?>
<section id="main" class="clearfix">
	<div class="container">
		<div class="row">
			<div id="content" class="courses col-md-12" role="main">
				<?php
					if ( $new_query->have_posts() ) :

						$x = 0;
				?>
					<div class="row">
					<?php while ( $new_query->have_posts() ) : $new_query->the_post(); ?>
						<?php
							$vehicleprice  = get_post_meta(get_the_ID(),'themeum_vehicleprice',true);
							$vehicleduration  = get_post_meta(get_the_ID(),'themeum_vehicleduration',true);
							if ($vehicleduration) {
								$vehicleduration = '/'.$vehicleduration;
							}
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
									<h3 class="package-list-title"><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>

									<!-- View Details -->
									<?php $view_details = get_theme_mod('vehicle_booknow_btn');
									if (isset($view_details) && $view_details != '') {  ?>
										<a class="package-list-button" href="<?php the_permalink(); ?>"><?php echo $view_details; ?> </a>
									<?php }else { ?>
										<a class="package-list-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Book Now','travelkit' ); ?> </a>
									<?php } ?>
									<!-- View Details End -->

								</div><!--/.package-list-content-->
							</div><!-- .package-list-wrap -->
						</div><!-- .col-sm-2 col-md-4 -->

						<?php $x++; ?>
					<?php endwhile; ?>
					</div>
					<?php
						$page_numb = max( 1, get_query_var('paged') );
						$max_page = $new_query->max_num_pages;
						echo travelkit_pagination( $page_numb, $max_page ); 
					?>
				<?php else: ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>