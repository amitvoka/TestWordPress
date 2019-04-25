<?php get_header(); ?>
<section id="main">
    <?php get_template_part('lib/sub-header')?>
    <div class="container">
        <div class="row">
            <div id="content" class="site-content col-sm-12 blog-content-wrapper" role="main">
                <?php if ( have_posts() ) :  ?> 

                    <?php while ( have_posts() ) : the_post(); ?>
                
                        <?php get_template_part( 'post-format/content', get_post_format() ); ?>

 
                         <?php if ( get_theme_mod( 'post_nav_en', true ) ) { ?>
                            <div class="clearfix post-navigation">
                                <?php previous_post_link('<span class="previous-post pull-left">%link</span>',esc_html__("Previous Article",'travelkit')); ?>
                                <?php next_post_link('<span class="next-post pull-right">%link</span>',esc_html__("Next Article",'travelkit')); ?>
                            </div> <!-- .post-navigation -->
                        <?php } ?>
  
                        <?php
                            if ( get_theme_mod( 'blog_single_comment_en', true ) ) {
                                if ( comments_open() || get_comments_number() ) {
                                    comments_template();
                                }
                            }
                        ?>

                    <?php endwhile; ?>
                    
                <?php else: ?>
                    <?php get_template_part( 'post-format/content', 'none' ); ?>
                <?php endif; ?>

                <div class="clearfix"></div>
            </div> <!-- #content -->
        </div> <!-- .row -->
        </div> <!-- .container -->
    </section> <!-- #main -->

<?php get_footer();