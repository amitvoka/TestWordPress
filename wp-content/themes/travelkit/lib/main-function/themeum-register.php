<?php 
/*-------------------------------------------*
 *      Themeum Widget Registration
 *------------------------------------------*/

if(!function_exists('travelkit_widdget_init')):

    function travelkit_widdget_init()
    {

        $bottomcolumn = get_theme_mod( 'bottom_column', '3' );

        register_sidebar(array( 'name'          => esc_html__( 'Sidebar', 'travelkit' ),
                                'id'            => 'sidebar',
                                'description'   => esc_html__( 'Widgets in this area will be shown on Sidebar.', 'travelkit' ),
                                'before_title'  => '<h3 class="widget_title">',
                                'after_title'   => '</h3>',
                                'before_widget' => '<div id="%1$s" class="widget %2$s" >',
                                'after_widget'  => '</div>'
                    )
        );


        register_sidebar(array( 
                            'name'          => esc_html__( 'Bottom', 'travelkit' ),
                            'id'            => 'bottom',
                            'description'   => esc_html__( 'Widgets in this area will be shown before Bottom.' , 'travelkit'),
                            'before_title'  => '<h3 class="widget-title">',
                            'after_title'   => '</h3>',
                            'before_widget' => '<div class="col-sm-6 col-md-'.esc_attr($bottomcolumn).' bottom-widget"><div id="%1$s" class="widget %2$s" >',
                            'after_widget'  => '</div></div>'
                            )
        );

    }
    
    add_action('widgets_init','travelkit_widdget_init');

endif;

if ( ! function_exists( 'travelkit_fonts_url' ) ) :
    function travelkit_fonts_url() {
    $fonts_url = '';

    $open_sans = _x( 'on', 'Poppins font: on or off', 'travelkit' );
     
    if ( 'off' !== $open_sans ) {
    $font_families = array();
     
    if ( 'off' !== $open_sans ) {
    $font_families[] = 'Poppins:300,400,500,600,700';
    }
     
    $query_args = array(
    'family'  => urlencode( implode( '|', $font_families ) ),
    'subset'  => urlencode( 'latin,latin-ext' ),
    );
     
    $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }
     
    return esc_url_raw( $fonts_url );
    }
endif;


/*-------------------------------------------*
 *      Themeum Style
 *------------------------------------------*/
if(!function_exists('travelkit_style')):

    function travelkit_style(){

        wp_enqueue_style( 'travelkit-font', travelkit_fonts_url(), array(), null );

        wp_enqueue_media();
        wp_enqueue_style( 'bootstrap.min', get_template_directory_uri() . '/css/bootstrap.min.css',false,'all');
        wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css',false,'all');
        wp_enqueue_style( 'travelkit-bistro', get_template_directory_uri() . '/css/bistro.css',false,'all');
        wp_enqueue_style( 'travelkit-main', get_template_directory_uri() . '/css/main.css',false,'all');
        wp_enqueue_style( 'travelkit-blog-detaills', get_template_directory_uri() . '/css/blog-details.css',false,'all');
        wp_enqueue_style( 'travelkit-responsive', get_template_directory_uri() . '/css/responsive.css',false,'all');

        wp_enqueue_style('travelkit-style',get_stylesheet_uri());
        wp_add_inline_style( 'travelkit-style', travelkit_css_generator() );
        wp_enqueue_script('bootstrap',TRAVELKIT_JS.'bootstrap.min.js',array(),false,true);
        wp_enqueue_script('loopcounter',TRAVELKIT_JS.'loopcounter.js',array(),false,true);
        wp_enqueue_script('jquery.prettySocial',TRAVELKIT_JS.'jquery.prettySocial.min.js',array(),false,true);
        wp_enqueue_script('jquery.ajax.login',TRAVELKIT_JS.'ajax-booking-btn.js',array(),false,true);
        
        if( get_theme_mod( 'custom_preset_en', true ) == 0 ) {
            wp_enqueue_style( 'themeum-preset', get_template_directory_uri(). '/css/presets/preset' . get_theme_mod( 'preset', '1' ) . '.css', array(),false,'all' );       
        }

        if ( is_singular() ) {wp_enqueue_script( 'comment-reply' );}

        wp_enqueue_script('unique-style',TRAVELKIT_JS.'main.js',array(),false,true);

    }

    add_action('wp_enqueue_scripts','travelkit_style');

endif;




if(!function_exists('travelkit_admin_style')):

    function travelkit_admin_style(){
        wp_enqueue_media();
        wp_register_script('thmpostmeta', get_template_directory_uri() .'/js/admin/post-meta.js');
        wp_enqueue_script('themeum-widget-js', get_template_directory_uri().'/js/widget-js.js', array('jquery'));
        wp_enqueue_script('thmpostmeta');
    }
    add_action('admin_enqueue_scripts','travelkit_admin_style');

endif;


/*-------------------------------------------------------
*           Include the TGM Plugin Activation class
*-------------------------------------------------------*/
add_action( 'tgmpa_register', 'travelkit_plugins_include');

if(!function_exists('travelkit_plugins_include')):

    function travelkit_plugins_include()
    {
        $plugins = array(

                array(
                    'name'                  => esc_html__( 'Themeum Core', 'travelkit' ),
                    'slug'                  => 'themeum-core',
                    'source'                => esc_url('http://demo.themeum.com/wordpress/plugins/travelkit/themeum-core.zip'),
                    'required'              => true,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ), 
                array(
                    'name'                  => esc_html__( 'Themeum demo importer', 'travelkit' ),
                    'slug'                  => 'themeum-demo-importer',
                    'source'                => esc_url('http://demo.themeum.com/wordpress/plugins/travelkit/themeum-demo-importer.zip'),
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ),                            
                array(
                    'name'                  => esc_html__( 'WPBakery Visual Composer', 'travelkit' ),
                    'slug'                  => 'js_composer',
                    'source'                => esc_url('http://demo.themeum.com/wordpress/plugins/js_composer.zip'),
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ), 
                array(
                    'name'                  => esc_html__( 'revslider', 'travelkit' ),
                    'slug'                  => 'revslider',
                    'source'                => esc_url('http://demo.themeum.com/wordpress/plugins/revslider.zip'),
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ),                                                          
                array(
                    'name'                  => esc_html__( 'MailChimp for WordPress', 'travelkit' ),
                    'slug'                  => 'mailchimp-for-wp',
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => esc_url('https://downloads.wordpress.org/plugin/mailchimp-for-wp.3.1.5.zip'),
                ),  

                array(
                    'name'                  => esc_html__( 'Widget Importer & Exporter', 'aresmurphy' ),
                    'slug'                  => 'widget-importer-exporter',
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => esc_url('https://downloads.wordpress.org/plugin/widget-importer-exporter.1.4.4.zip'),
                ),
                array(
                    'name'                  => esc_html__( 'Contact Form 7', 'travelkit' ),
                    'slug'                  => 'contact-form-7',
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => esc_url('https://downloads.wordpress.org/plugin/contact-form-7.4.4.1.zip'),
                ),
            );
    $config = array(
            'domain'            => 'travelkit',           
            'default_path'      => '',                           
            'parent_menu_slug'  => 'themes.php',                 
            'parent_url_slug'   => 'themes.php',                
            'menu'              => 'install-required-plugins',   
            'has_notices'       => true,                         
            'is_automatic'      => false,                      
            'message'           => '',                     
            'strings'           => array(
                        'page_title'                                => esc_html__( 'Install Required Plugins', 'travelkit' ),
                        'menu_title'                                => esc_html__( 'Install Plugins', 'travelkit' ),
                        'installing'                                => esc_html__( 'Installing Plugin: %s', 'travelkit' ), 
                        'oops'                                      => esc_html__( 'Something went wrong with the plugin API.', 'travelkit'),
                        'return'                                    => esc_html__( 'Return to Required Plugins Installer', 'travelkit'),
                        'plugin_activated'                          => esc_html__( 'Plugin activated successfully.','travelkit'),
                        'complete'                                  => esc_html__( 'All plugins installed and activated successfully. %s', 'travelkit' ) 
                )
    );

    tgmpa( $plugins, $config );

    }

endif;