<!-- start footer -->
    <?php
    $bottomstyle = get_theme_mod( 'bottom_style', 'solid');?>

    <?php if( is_active_sidebar('bottom') ){ ?>
        <div class="bottom <?php echo esc_attr($bottomstyle);?>">
            <div class="container">
                <div class="row clearfix">
                    <?php if (is_active_sidebar('bottom')) {?>
                        <?php dynamic_sidebar('bottom'); ?>
                    <?php } ?>
                </div>
            </div>
        </div><!--/#footer-->
    <?php } ?>

    <footer id="footer" class="footer-wrap">
        <?php if ( get_theme_mod( 'copyright_en', true ) || get_theme_mod( 'footer_share', true ) ) { ?>
            <div class="container">
                <div class="row">
                    <?php if( get_theme_mod( 'copyright_en', true ) ){ ?>
                        <div class="col-sm-6">
                            <?php echo wp_kses_post(balanceTags( get_theme_mod( 'copyright_text', '© 2016 Your Company.  Designed By <a href="http://themeum.com/" target="_blank"> THEMEUM</a>') )); ?>
                        </div> <!-- end row -->
                    <?php } ?>
                    <?php if( get_theme_mod( 'footer_share', true ) ){ ?>
                        <div class="col-sm-6 text-right">
                            <?php get_template_part('lib/social-link')?>
                        </div> <!-- end row -->
                    <?php } ?>
                </div> <!-- end row -->
            </div>  <!-- end container -->
        <?php } ?>
    </footer>
</div> <!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
