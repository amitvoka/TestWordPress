<?php get_header('alternative');
/*
*Template Name: 404 Page Template
*/
?>

<section class="error-page-inner" style="background-image: url(<?php echo esc_url( get_theme_mod('errorbg','bg')); ?>);">
        <div class="error-msg">
            <div class="info-wrapper">
            	<h2 class="error-message-title"><?php  echo esc_html(get_theme_mod( '404_title', esc_html__('Page not Found.', 'travelkit') )); ?></h2>
            	<p class="error-message"><?php  echo esc_html(get_theme_mod( '404_description', esc_html__('The page you are looking for was moved, removed, renamed or might never existed..', 'travelkit') )); ?></p>
            	<a class="btn btn-primary white" href="<?php echo esc_url( home_url('/') ); ?>" title="<?php  esc_html_e( 'HOME', 'travelkit' ); ?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;<?php  echo esc_html(get_theme_mod( '404_btn_text', esc_html__('Go Back Home', 'travelkit') )); ?></a>

            </div>
        </div>

</section>

<?php get_footer('alternative'); ?>
