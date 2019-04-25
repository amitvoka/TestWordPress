
    <div class="entry-header">
        <h2 class="entry-title blog-entry-title">
            <?php if ( is_single() ) {?>
                <?php the_title(); ?>
            <?php } else { ?>
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
            <?php }?>
            <?php if ( is_sticky() && is_home() && ! is_paged()) { ?>
            <span class="featured-post"><i class="fa fa-star"></i></span>
            <?php } ?>
        </h2> <!-- //.entry-title --> 
    </div>

    <?php if ( get_theme_mod( 'blog_date', true ) || get_theme_mod( 'blog_author', false ) || get_theme_mod( 'blog_category', true ) || get_theme_mod( 'blog_comment', true ) ) { ?>
        <ul class="blog-post-meta"> 
            <!-- Blog date -->
            <?php if ( get_theme_mod( 'blog_date', true ) ) { ?>
                <li class="meta-date">
                    <i class="fa fa-clock-o"></i>
                    <time datetime="<?php echo get_the_date() ?>"><?php echo get_the_date(); ?></time>
                </li>
            <?php } ?>    
            <!-- Blog date -->
            <?php if ( get_theme_mod( 'blog_author', false ) ) { ?>
                <li class="meta-author">
                    <i class="fa fa-meh-o"></i>
                    <?php if ( get_the_author_meta('first_name') != "" || get_the_author_meta('last_name') != "" ) { ?>
                        <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author_meta('first_name');?> <?php echo get_the_author_meta('last_name');?></a>
                    <?php } else { ?>
                        <?php the_author_posts_link() ?>
                    <?php }?>
                </li>
            <?php } ?>    
            <!-- Blog date -->
            <?php if ( get_theme_mod( 'blog_category', false ) ) { ?>
                <li class="meta-category">
                    <i class="fa fa-folder-open-o"></i>
                    <?php echo get_the_category_list(', '); ?>
                </li>
            <?php } ?>
            <!-- Comments section -->
            <?php if ( get_theme_mod( 'blog_comment', true ) ) { ?>
                <?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
                        <li class="meta-comment">
                            <i class="fa fa-commenting-o"></i>
                            <?php comments_popup_link( '<span class="leave-reply">' . esc_html__( '0', 'travelkit' ) . '</span>', esc_html__( '1', 'travelkit' ), esc_html__( '% comments', 'travelkit' ) ); ?>
                        </li>
                <?php endif; //.comment-link ?>
            <?php }?> 
            <!-- comments section end -->
        </ul>
    <?php }?> 
