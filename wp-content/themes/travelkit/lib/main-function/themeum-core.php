<?php

if(!function_exists('travelkit_setup')):

    function travelkit_setup()
    {
        //Textdomain
        load_theme_textdomain( 'travelkit', get_template_directory() . '/languages' );
        add_theme_support( 'post-thumbnails' );
        add_image_size( 'travelkit-large', 1170, 750, true ); // Used Top Celebrities
        add_image_size( 'travelkit-medium', 570, 400, true ); // Used Top Celebrities
        add_theme_support( 'post-formats', array( 'audio','gallery','image','link','quote','video' ) );
        add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form' ) );
        add_theme_support( 'automatic-feed-links' );

        if ( ! isset( $content_width ) )
        $content_width = 660;
    }

    add_action('after_setup_theme','travelkit_setup');

endif;

if(!function_exists('travelkit_pagination')):

    function travelkit_pagination( $page_numb , $max_page )
    {
        $big = 999999999; // need an unlikely integer
        echo '<div class="themeum-pagination">';
        echo paginate_links( array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => $page_numb,
            'total' => $max_page,
            'type'  => 'list',
        ) );
        echo '</div>';
    }
endif;


/*-------------------------------------------------------
 *              Themeum Comment
 *-------------------------------------------------------*/

if(!function_exists('travelkit_comment')):

    function travelkit_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
            case 'pingback' :
            case 'trackback' :
            // Display trackbacks differently than normal comments.
        ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

        <?php
            break;
            default :
            global $post;
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
            <div id="comment-<?php comment_ID(); ?>" class="comment-body media">

                    <div class="comment-avartar pull-left">
                        <?php
                            echo get_avatar( $comment, $args['avatar_size'] );
                        ?>
                    </div>
                    <div class="comment-context media-body">
                        <div class="comment-head">
                            <?php
                                printf( '<span class="comment-author">%1$s</span>',
                                    get_comment_author_link());
                            ?>
                            <span class="comment-date"><?php echo get_comment_date('d / m / Y') ?></span>

                            <?php edit_comment_link( esc_html__( 'Edit', 'travelkit' ), '<span class="edit-link">', '</span>' ); ?>

                        </div>

                        <?php if ( '0' == $comment->comment_approved ) : ?>
                        <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'travelkit' ); ?></p>
                        <?php endif; ?>

                        <div class="comment-content">
                            <?php comment_text(); ?>
                        </div>

                        <span class="comment-reply">
                            <?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'travelkit' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                        </span>
                    </div>

            </div>
        <?php
            break;
        endswitch;
    }

endif;


/*-------------------------------------------------------
*           Themeum Breadcrumb
*-------------------------------------------------------*/
if(!function_exists('travelkit_breadcrumbs')):
function travelkit_breadcrumbs(){ ?>
    <ol class="breadcrumb">
        <li><a href="<?php echo esc_url(site_url()); ?>" class="breadcrumb_home"><?php esc_html_e('Home', 'travelkit') ?></a></li>
        <?php
            if(function_exists('is_product')){
                $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
                if(is_product()){
                    echo "<li><a href='".$shop_page_url."'>shop</a></li>";
                }
            }
        ?>
        <li class="active">

                    <?php if(function_exists('is_shop')){ if(is_shop()){ _e('shop','travelkit'); } } ?>

                    <?php if( is_tag() ) { ?>
                    <?php esc_html_e('Posts Tagged ', 'travelkit') ?><span class="raquo">/</span><?php single_tag_title(); echo('/'); ?>
                    <?php } elseif (is_day()) { ?>
                    <?php esc_html_e('Posts made in', 'travelkit') ?> <?php the_time('F jS, Y'); ?>
                    <?php } elseif (is_month()) { ?>
                    <?php esc_html_e('Posts made in', 'travelkit') ?> <?php the_time('F, Y'); ?>
                    <?php } elseif (is_year()) { ?>
                    <?php esc_html_e('Posts made in', 'travelkit') ?> <?php the_time('Y'); ?>
                    <?php } elseif (is_search()) { ?>
                    <?php esc_html_e('Search results for', 'travelkit') ?> <?php the_search_query() ?>
                    <?php } elseif (is_single()) { ?>
                    <?php $category = get_the_category();
                    if ( $category ) {
                        $catlink = get_category_link( $category[0]->cat_ID );
                        echo ('<a href="'.esc_url($catlink).'">'.esc_html($category[0]->cat_name).'</a> '.'<span class="raquo"> /</span> ');
                    }
                    echo get_the_title(); ?>
                    <?php } elseif (is_category()) { ?>
                    <?php single_cat_title(); ?>
                    <?php } elseif (is_tax()) { ?>
                    <?php
                    $themeum_taxonomy_links = array();
                    $themeum_term = get_queried_object();
                    $themeum_term_parent_id = $themeum_term->parent;
                    $themeum_term_taxonomy = $themeum_term->taxonomy;

                    while ( $themeum_term_parent_id ) {
                        $themeum_current_term = get_term( $themeum_term_parent_id, $themeum_term_taxonomy );
                        $themeum_taxonomy_links[] = '<a href="' . esc_url( get_term_link( $themeum_current_term, $themeum_term_taxonomy ) ) . '" title="' . esc_attr( $themeum_current_term->name ) . '">' . esc_html( $themeum_current_term->name ) . '</a>';
                        $themeum_term_parent_id = $themeum_current_term->parent;
                    }

                    if ( !empty( $themeum_taxonomy_links ) ) echo implode( ' <span class="raquo">/</span> ', array_reverse( $themeum_taxonomy_links ) ) . ' <span class="raquo">/</span> ';

                    echo esc_html( $themeum_term->name );
                } elseif (is_author()) {
                    global $wp_query;
                    $curauth = $wp_query->get_queried_object();

                    esc_html_e('Posts by ', 'travelkit'); echo ' ',$curauth->nickname;
                } elseif (is_page()) {
                    echo get_the_title();
                } elseif (is_home()) {
                    esc_html_e('Blog', 'travelkit');
                } ?>
            </li>
    </ol>

<?php
}
endif;


/*-----------------------------------------------------
 *              travelkit_Hex2RGB
 *----------------------------------------------------*/
if(!function_exists('travelkit_hex2rgb')):
    function travelkit_hex2rgb($hex) {
       $hex = str_replace("#", "", $hex);

       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgb = array($r, $g, $b);

       return $rgb[0].','.$rgb[1].','.$rgb[2];
    }
endif;
/*-----------------------------------------------------
 *              Coming Soon Page Settings
 *----------------------------------------------------*/
if ( get_theme_mod( 'comingsoon_en', false ) ) {
    if(!function_exists('travelkit_my_page_template_redirect')):
        function travelkit_my_page_template_redirect()
        {
            if( is_page() || is_home() || is_category() || is_single() )
            {
                if( !is_super_admin( get_current_user_id() ) ){
                    get_template_part( 'coming','soon');
                    exit();
                }
            }
        }
        add_action( 'template_redirect', 'travelkit_my_page_template_redirect' );
    endif;

    if(!function_exists('travelkit_cooming_soon_wp_title')):
        function travelkit_cooming_soon_wp_title(){
            return 'Coming Soon';
        }
        add_filter( 'wp_title', 'travelkit_cooming_soon_wp_title' );
    endif;
}



if(!function_exists('travelkit_css_generator')){
    function travelkit_css_generator(){

        $output = '';

        /* *******************************
        **********      Theme Options   **********
        ********************************** */
        $preset = get_theme_mod( 'preset', '1' );
        if( $preset ){

            if( get_theme_mod( 'custom_preset_en', true ) ) {
                $major_color = get_theme_mod( 'major_color', '#00aeef' );
                if($major_color){
                    $output .= 'a,.bottom-widget .contact-info i,.bottom-widget .widget ul li a:hover, .package-sidebar a:hover { color: '. esc_attr($major_color) .'; }';
                }


                if($major_color){
                    $output .= 'input:focus, textarea:focus, keygen:focus, select:focus{ border-color: '. esc_attr($major_color) .'; }';
                }

                if($major_color){
                    $output .= '.package-list-content .package-list-title a:hover, .themeum-latest-post-content .entry-title a:hover, .thm-tk-search .thm-tk-search-nav ul li a:hover, .thm-tk-search .thm-tk-search-nav ul li a.active i, .thm-tk-search .thm-tk-search-nav ul li a.active, .ui-datepicker .ui-datepicker-calendar td:hover a, .package-sidebar .need-help p i, .package-share li a:hover { color: '. esc_attr($major_color) .'; }';
                }

                if($major_color){
                    $output .= '.thm-tk-search .thm-tk-search-nav ul li a i:after, .select2-container--default .select2-results__option--highlighted[aria-selected] ,.select2-dropdown .select2-results .select2-results__options .select2-results__option:hover, .ui-slider .ui-slider-handle, .ui-datepicker .ui-datepicker-current-day a, .themeum-pagination ul li .page-numbers.current, .themeum-pagination ul li a:hover, .package-nav-tab.nav-tabs>li.active>a, .package-nav-tab.nav-tabs>li.active>a:focus, .package-nav-tab.nav-tabs>li.active>a:hover, .package-nav-tab.nav-tabs>li>a:hover { background: '. esc_attr($major_color) .'; }';
                }
            }

            if($major_color){
                $output .= '.btn.btn-slider:hover, .btn.btn-slider:focus { background-color: '. esc_attr($major_color) .'; border-color: '. esc_attr($major_color) .'; }';
            }


            // .select2-container .select2-dropdown .select2-results ul li

            if( get_theme_mod( 'custom_preset_en', true ) ) {
                $hover_color = get_theme_mod( 'hover_color', '#2695BC' );
                if( $hover_color ){
                    $output .= 'a:hover, .widget.widget_rss ul li a{ color: '.esc_attr( $hover_color ) .'; }';
                    $output .= '.error-page-inner a.btn.btn-primary.btn-lg:hover,.btn.btn-primary:hover,input[type=button]:hover,
                    .widget.widget_search #searchform .btn-search:hover{ background-color: '.esc_attr( $hover_color ) .'; }';
                    $output .= '.woocommerce a.button:hover{ border-color: '.esc_attr( $hover_color ) .'; }';
                }
            }

        } else {
        $output ='a,a:focus,.sub-title .breadcrumb>.active,.modal-content .lost-pass:hover,#mobile-menu ul li:hover > a,#mobile-menu ul li.active > a,
#sidebar .widget ul li a:hover,
.themeum-pagination ul li .current,.themeum-pagination ul li a:hover,.sub-title-inner h2.leading,.header-transparent .topbar a:hover,
.topbar-contact strong,.menu-social .social-share ul li a:hover,.video-section .video-caption i:hover,
.addon-classic-content >div:hover h4, .addon-classic-content >div:hover .menu-price,
.btn.btn-style:hover,.widget .themeum-social-share li a:hover,.header-solid .common-menu-wrap .nav>li>a:hover,.woocommerce .star-rating span,
.woocommerce-tabs .nav-tabs>li.active>a, .woocommerce-tabs .nav-tabs>li.active>a:focus, .woocommerce-tabs .nav-tabs>li.active>a:hover,
.woocommerce-tabs .nav>li>a:focus, .woocommerce-tabs .nav>li>a:hover,.cuisine-chef-designation{ color: #e7272d; }
.error-page-inner a.btn.btn-primary.btn-lg,.btn.btn-primary,
.widget .tagcloud a,.carousel-left:hover, .carousel-right:hover,input[type=button],.navbar-toggle:hover .icon-bar,
.woocommerce a.button:hover,article.post .entry-blog .blog-date,.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover,
.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover{background-color: #e7272d; }
.common-menu-wrap .nav>li ul{ background-color: rgba(231,39,45,.8); }
input:focus, textarea:focus, keygen:focus, select:focus{ border-color: #e7272d; }
a:hover, .widget.widget_rss ul li a{ color: #e8141b; }
.error-page-inner a.btn.btn-primary.btn-lg:hover,.btn.btn-primary:hover,input[type=button]:hover,
.widget.widget_search #searchform .btn-search:hover{ background-color: #e8141b; }
.btn.btn-primary,.woocommerce a.button:hover{ border-color: #e8141b; }';
        }



        /* *******************************
        **********  Quick Style **********
        ********************************** */

        $bstyle = $mstyle = $h1style = $h2style = $h3style = $h4style = $h5style = $sticky_color = '';
        //body
        if ( get_theme_mod( 'body_font_size', '14' ) ) {
            $bstyle .= 'font-size:'.get_theme_mod( 'body_font_size', '14' ).'px;';
        }
        if ( get_theme_mod( 'body_google_font', 'Poppins' ) ) {
            $bstyle .= 'font-family:'.get_theme_mod( 'body_google_font', 'Poppins' ).';';
        }
        if ( get_theme_mod( 'body_font_weight', '400' ) ) {
            $bstyle .= 'font-weight: '.get_theme_mod( 'body_font_weight', '400' ).';';
        }
        if ( get_theme_mod('body_font_height', '24') ) {
            $bstyle .= 'line-height: '.get_theme_mod('body_font_height', '24').'px;';
        }
        if ( get_theme_mod('body_font_color', '#777') ) {
            $bstyle .= 'color: '.get_theme_mod('body_font_color', '#777').';';
        }


        /*=======================================
        * ========== Sticky Menu ================
        =========================================  */ 
         if ( get_theme_mod('sticky_header_text_color', '#fff') ) {
            $sticky_color .= 'color: '.get_theme_mod('sticky_header_text_color', '#fff').';';
        }

        
        //menu
        $mstyle = '';
        if ( get_theme_mod( 'menu_font_size', '14' ) ) {
            $mstyle .= 'font-size:'.get_theme_mod( 'menu_font_size', '14' ).'px;';
        }
        if ( get_theme_mod( 'menu_google_font', 'Poppins' ) ) {
            $mstyle .= 'font-family:'.get_theme_mod( 'menu_google_font', 'Poppins' ).';';
        }
        if ( get_theme_mod( 'menu_font_weight', '400' ) ) {
            $mstyle .= 'font-weight: '.get_theme_mod( 'menu_font_weight', '400' ).';';
        }
        if ( get_theme_mod('menu_font_height', '24') ) {
            $mstyle .= 'line-height: '.get_theme_mod('menu_font_height', '24').'px;';
        }
        if ( get_theme_mod('menu_font_color', '#fff') ) {
            $mstyle .= 'color: '.get_theme_mod('menu_font_color', '#fff').';';
        }

        //heading1
        $h1style = '';
        if ( get_theme_mod( 'h1_font_size', '14' ) ) {
            $h1style .= 'font-size:'.get_theme_mod( 'h1_font_size', '46' ).'px;';
        }
        if ( get_theme_mod( 'h1_google_font', 'Poppins' ) ) {
            $h1style .= 'font-family:'.get_theme_mod( 'h1_google_font', 'Poppins' ).';';
        }
        if ( get_theme_mod( 'h1_font_weight', '400' ) ) {
            $h1style .= 'font-weight: '.get_theme_mod( 'h1_font_weight', '400' ).';';
        }
        if ( get_theme_mod('h1_font_height', '24') ) {
            $h1style .= 'line-height: '.get_theme_mod('h1_font_height', '24').'px;';
        }
        if ( get_theme_mod('h1_font_color', '#777') ) {
            $h1style .= 'color: '.get_theme_mod('h1_font_color', '#777').';';
        }

        //heading2
        $h2style = '';
        if ( get_theme_mod( 'h2_font_size', '14' ) ) {
            $h2style .= 'font-size:'.get_theme_mod( 'h2_font_size', '36' ).'px;';
        }
        if ( get_theme_mod( 'h2_google_font', 'Poppins' ) ) {
            $h2style .= 'font-family:'.get_theme_mod( 'h2_google_font', 'Poppins' ).';';
        }
        if ( get_theme_mod( 'h2_font_weight', '400' ) ) {
            $h2style .= 'font-weight: '.get_theme_mod( 'h2_font_weight', '400' ).';';
        }
        if ( get_theme_mod('h2_font_height', '24') ) {
            $h2style .= 'line-height: '.get_theme_mod('h2_font_height', '24').'px;';
        }
        if ( get_theme_mod('h2_font_color', '#777') ) {
            $h2style .= 'color: '.get_theme_mod('h2_font_color', '#777').';';
        }

        //heading3
        $h3style = '';
        if ( get_theme_mod( 'h3_font_size', '14' ) ) {
            $h3style .= 'font-size:'.get_theme_mod( 'h3_font_size', '26' ).'px;';
        }
        if ( get_theme_mod( 'h3_google_font', 'Poppins' ) ) {
            $h3style .= 'font-family:'.get_theme_mod( 'h3_google_font', 'Poppins' ).';';
        }
        if ( get_theme_mod( 'h3_font_weight', '400' ) ) {
            $h3style .= 'font-weight: '.get_theme_mod( 'h3_font_weight', '400' ).';';
        }
        if ( get_theme_mod('h3_font_height', '24') ) {
            $h3style .= 'line-height: '.get_theme_mod('h3_font_height', '24').'px;';
        }
        if ( get_theme_mod('h3_font_color', '#777') ) {
            $h3style .= 'color: '.get_theme_mod('h3_font_color', '#777').';';
        }

        //heading4
        $h4style = '';
        if ( get_theme_mod( 'h4_font_size', '14' ) ) {
            $h4style .= 'font-size:'.get_theme_mod( 'h4_font_size', '18' ).'px;';
        }
        if ( get_theme_mod( 'h4_google_font', 'Poppins' ) ) {
            $h4style .= 'font-family:'.get_theme_mod( 'h4_google_font', 'Poppins' ).';';
        }
        if ( get_theme_mod( 'h4_font_weight', '400' ) ) {
            $h4style .= 'font-weight: '.get_theme_mod( 'h4_font_weight', '400' ).';';
        }
        if ( get_theme_mod('h4_font_height', '24') ) {
            $h4style .= 'line-height: '.get_theme_mod('h4_font_height', '24').'px;';
        }
        if ( get_theme_mod('h4_font_color', '#777') ) {
            $h4style .= 'color: '.get_theme_mod('h4_font_color', '#777').';';
        }

        //heading5
        $h5style = '';
        if ( get_theme_mod( 'h5_font_size', '14' ) ) {
            $h5style .= 'font-size:'.get_theme_mod( 'h5_font_size', '14' ).'px;';
        }
        if ( get_theme_mod( 'h5_google_font', 'Poppins' ) ) {
            $h5style .= 'font-family:'.get_theme_mod( 'h5_google_font', 'Poppins' ).';';
        }
        if ( get_theme_mod( 'h5_font_weight', '400' ) ) {
            $h5style .= 'font-weight: '.get_theme_mod( 'h5_font_weight', '400' ).';';
        }
        if ( get_theme_mod('h5_font_height', '24') ) {
            $h5style .= 'line-height: '.get_theme_mod('h5_font_height', '24').'px;';
        }
        if ( get_theme_mod('h5_font_color', '#777') ) {
            $h5style .= 'color: '.get_theme_mod('h5_font_color', '#777').';';
        }

        $output .= 'body{'.$bstyle.'}';
        $output .= '.common-menu-wrap .nav>li>a, .common-menu-wrap .nav>li.menu-item-has-children:after{'.$mstyle.'}';
        $output .= '.sticky .common-menu-wrap .nav>li>a, .sticky .common-menu-wrap .nav>li.menu-item-has-children:after{'.$sticky_color.'}';
        $output .= 'h1{'.$h1style.'}';
        $output .= 'h2{'.$h2style.'}';
        $output .= 'h3{'.$h3style.'}';
        $output .= 'h4{'.$h4style.'}';
        $output .= 'h5{'.$h5style.'}';

        if ( get_theme_mod( 'header_fixed', false ) ){
            $output .= '.site-header.sticky{ position:fixed;top:0;left:auto; z-index:1000;margin:0 auto 30px; width:100%; background-color:rgba(0,0,0,0.3);}';
            $output .= '.site-header.sticky.header-transparent .main-menu-wrap{ margin-top: 0;}';
            $output .= '.site-header.sticky.header-solid .common-menu-wrap .nav>li>a{ color: #9d9d9d;}';
            if ( get_theme_mod( 'sticky_header_color', '#fff' ) ){
                $sticybg = get_theme_mod( 'sticky_header_color', '#fff');
                $output .= '.site-header.sticky{ background-color: '.$sticybg.';}';
            }
        }

        $output .= '.site-header{ padding-top: '. (int) esc_attr( get_theme_mod( 'header_padding_top', '0' ) ) .'px; }';
        $output .= '.site-header{ padding-bottom: '. (int) esc_attr( get_theme_mod( 'header_padding_bottom', '0' ) ) .'px; }';
        if (get_theme_mod( 'body_bg_img')) {
            $output .= 'body{ background-image: url("'.esc_attr( get_theme_mod( 'body_bg_img' ) ) .'");background-size: '.esc_attr( get_theme_mod( 'body_bg_size', 'cover' ) ) .';    background-position: '.esc_attr( get_theme_mod( 'body_bg_position', 'left top' ) ) .';background-repeat: '.esc_attr( get_theme_mod( 'body_bg_repeat', 'no-repeat' ) ) .';background-attachment: '.esc_attr( get_theme_mod( 'body_bg_attachment', 'fixed' ) ) .'; }';
        }
        $output .= 'body{ background-color: '.esc_attr( get_theme_mod( 'body_bg_color', '#fff' ) ) .'; }';

        if (get_theme_mod( 'topbar_color' ) ) {
            $output .= '.topbar{ background-color: '.esc_attr( get_theme_mod( 'topbar_color' ) ) .'; }';
        }
        if (get_theme_mod( 'topbar_text_color' ) ) {
            $output .= '.topbar,.topbar a{ color: '.esc_attr( get_theme_mod( 'topbar_text_color' ) ) .'; }';
        }
        if ( get_theme_mod( 'topbar_border_color' ) ) {
            $output .= '.header-transparent .topbar{ border-bottom: 1px solid '.esc_attr( get_theme_mod( 'topbar_border_color' ) ) .'; }';
        }
        if ( get_theme_mod( 'header_color' ) ) {
            $output .= '.site-header{ background-color: '.esc_attr( get_theme_mod( 'header_color' ) ) .'; }';
        }
        if ( get_theme_mod( 'header_border_color' ) ) {
            $output .= '.site-header.header-transparent{ border-bottom: 1px solid '.esc_attr( get_theme_mod( 'header_border_color' ) ) .'; }';
        }
        if ( get_theme_mod( 'bottom_color' ) ) {
            $output .= '.bottom{ background-color: '.esc_attr( get_theme_mod( 'bottom_color' ) ) .'; }';
        }

        if ( get_theme_mod( 'button_bg_color', '#32aad6' ) ) {
            $output .= '.mc4wp-form-fields input[type=submit], .common-menu-wrap .nav>li.online-booking-button a, .error-page-inner a.btn.btn-primary.btn-lg,.btn.btn-primary, .package-list-button{ background-color: '.esc_attr( get_theme_mod( 'button_bg_color', '#32aad6' ) ) .'; border-color: '.esc_attr( get_theme_mod( 'button_bg_color', '#32aad6' ) ) .'; color: '.esc_attr( get_theme_mod( 'button_text_color', '#fff' ) ) .' !important; }';
        }

        if ( get_theme_mod( 'button_hover_bg_color', '#2695BC' ) ) {
            $output .= '.mc4wp-form-fields input[type=submit]:hover, .common-menu-wrap .nav>li.online-booking-button a:hover, .error-page-inner a.btn.btn-primary.btn-lg:hover,.btn.btn-primary:hover, .package-list-button:hover{ background-color: '.esc_attr( get_theme_mod( 'button_hover_bg_color', '#2695BC' ) ) .'; border-color: '.esc_attr( get_theme_mod( 'button_hover_bg_color', '#2695BC' ) ) .'; color: '.esc_attr( get_theme_mod( 'button_hover_text_color', '#fff' ) ) .' !important; }';
        }

        if ( get_theme_mod( 'navbar_text_color' ) ) {
            $output .= '.header-solid .common-menu-wrap .nav>li.menu-item-has-children:after, .header-borderimage .common-menu-wrap .nav>li.menu-item-has-children:after, .header-solid .common-menu-wrap .nav>li>a, .header-borderimage .common-menu-wrap .nav>li>a{ color: '.esc_attr( get_theme_mod( 'navbar_text_color' ) ) .'; }';
        }

        if ( get_theme_mod( 'navbar_bracket_color', '#00aeef' ) ) {
            $output .= 'header.header-solid .common-menu-wrap .nav>li>a:before, header.header-solid .common-menu-wrap .nav>li>a:after, header.header-borderimage .common-menu-wrap .nav>li>a:before, header.header-borderimage .common-menu-wrap .nav>li>a:after{ color: '.esc_attr( get_theme_mod( 'navbar_bracket_color', '#00aeef' ) ) .'; }';
        }

        $output .= '.footer-wrap{ background-color: '.esc_attr( get_theme_mod( 'footer_color', '#202134' ) ) .'; padding-top: '.esc_attr( get_theme_mod( 'footer_copyright_top_padding', '26' ) ) .'px; padding-bottom: '.esc_attr( get_theme_mod( 'footer_copyright_bottom_padding', '26' ) ) .'px; }';

        $output .= '.footer-wrap{ color: '.esc_attr( get_theme_mod( 'footer_copyright_text_color', '#fff' ) ) .'; }';
        $output .= '.footer-wrap a{ color: '.esc_attr( get_theme_mod( 'footer_copyright_link_color', '#00aeef' ) ) .'; }';
        $output .= '.footer-wrap a:hover{ color: '.esc_attr( get_theme_mod( 'footer_copyright_link_color_hvr', '#2695BC' ) ) .'; }';
        $output .= '.footer-wrap .social-share li a{ color: '.esc_attr( get_theme_mod( 'footer_icon_color', '#fff' ) ) .'; }';
        $output .= '.footer-wrap .social-share li a:hover{ color: '.esc_attr( get_theme_mod( 'footer_icon_color_hvr', '#00aeef' ) ) .'; }';

        if (get_theme_mod( 'footer_widget_bg_color' )) {
            $output .= '.bottom{ background-color: '.esc_attr( get_theme_mod( 'footer_widget_bg_color' ) ) .'; }';
        }

        $output .= '.bottom{ border-top-color: '.esc_attr( get_theme_mod( 'footer_widget_top_border_color', '#eaeaea' ) ) .'; padding-top: '.esc_attr( get_theme_mod( 'footer_widget_top_padding', '85' ) ) .'px; padding-bottom: '.esc_attr( get_theme_mod( 'footer_widget_bottom_padding', '85' ) ) .'px; }';
        $output .= '.bottom, .bottom-widget .contact-info p{ color: '.esc_attr( get_theme_mod( 'footer_widget_text_color', '#000' ) ) .'; }';
        $output .= '.bottom-widget .widget-title{ color: '.esc_attr( get_theme_mod( 'footer_widget_title_color', '#000' ) ) .'; }';
        $output .= '.bottom a, .widget ul li a{ color: '.esc_attr( get_theme_mod( 'footer_widget_link_color', '#000' ) ) .'; }';
        $output .= '.bottom a:hover, .widget ul li a:hover{ color: '.esc_attr( get_theme_mod( 'footer_widget_link_color_hvr', '#00aeef' ) ) .'; }';

        $output .= '.common-menu-wrap .nav>li ul{ background-color: '.esc_attr( get_theme_mod( 'sub_menu_bg', '#fff' ) ) .'; }';
        $output .= '.common-menu-wrap .nav>li>ul li a{ color: '.esc_attr( get_theme_mod( 'sub_menu_text_color', '#000' ) ) .'; border-color: '.esc_attr( get_theme_mod( 'sub_menu_border', '#eef0f2' ) ) .'; }';
        $output .= '.common-menu-wrap .nav>li>ul li a:hover{ color: '.esc_attr( get_theme_mod( 'sub_menu_text_color_hover', '#000' ) ) .'; background-color: '.esc_attr( get_theme_mod( 'sub_menu_bg_hover', '#fbfbfc' ) ) .'; }';
        $output .= '.common-menu-wrap .nav>li > ul::after{ border-color: transparent transparent '.esc_attr( get_theme_mod( 'sub_menu_bg', '#fff' ) ) .' transparent; }';

        if (get_theme_mod( 'logo_width' )) {
            $output .= '.travelkit-navbar-header .travelkit-navbar-brand img{width:'.get_theme_mod( 'logo_width' ).'px;}';
        }

        if (get_theme_mod( 'logo_height' )) {
            $output .= '.travelkit-navbar-header .travelkit-navbar-brand img{height:'.get_theme_mod( 'logo_height' ).'px;}';
        }

        $output .= '.subtitle-cover:before{background:'.get_theme_mod( 'sub_header_overlayer_color', 'rgba(0, 0, 0, 0.5)' ).';}';
        $output .= '.subtitle-cover h2{font-size:'.get_theme_mod( 'sub_header_title_size', '42' ).'px;color:'.get_theme_mod( 'sub_header_title_color', '#fff' ).';}';

        $output .= '.breadcrumb>li+li:before, .subtitle-cover .breadcrumb, .subtitle-cover .breadcrumb>.active{color:'.get_theme_mod( 'breadcrumb_text_color', '#fff' ).';}';
        $output .= '.subtitle-cover .breadcrumb a{color:'.get_theme_mod( 'breadcrumb_link_color', '#fff' ).';}';
        $output .= '.subtitle-cover .breadcrumb a:hover{color:'.get_theme_mod( 'breadcrumb_link_color_hvr', '#fff' ).';}';

        $output .= '.subtitle-cover{padding:'.get_theme_mod( 'sub_header_padding_top', '142' ).'px 0 '.get_theme_mod( 'sub_header_padding_bottom', '92' ).'px; margin-bottom: '.get_theme_mod( 'sub_header_margin_bottom', '100' ).'px;}';


        $output .= "body.error404,body.page-template-404{
            width: 100%;
            height: 100%;
            min-height: 100%;
            background: #333 url(".esc_url( get_theme_mod( 'errorbg', false ) ).") no-repeat 100% 0;
        }";

        return $output;
    }
}
