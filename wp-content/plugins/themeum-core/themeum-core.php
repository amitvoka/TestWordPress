<?php
/*
* Plugin Name: Themeum Core
* Plugin URI: http://www.themeum.com/item/core
* Author: Themeum
* Author URI: http://www.themeum.com
* License - GNU/GPL V2 or Later
* Description: Themeum Core is a required plugin for this theme.
* Version: 1.4
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// language
add_action( 'init', 'themeum_core_language_load' );
function themeum_core_language_load(){
    $plugin_dir = basename(dirname(__FILE__))."/languages/";
    load_plugin_textdomain( 'themeum-core', false, $plugin_dir );
}

if( !function_exists("themeum_cat_list") ){
    // List of Group
    function themeum_cat_list( $category ){
        global $wpdb;
        $sql = "SELECT * FROM `".$wpdb->prefix."term_taxonomy` INNER JOIN `".$wpdb->prefix."terms` ON `".$wpdb->prefix."term_taxonomy`.`term_taxonomy_id`=`".$wpdb->prefix."terms`.`term_id` AND `".$wpdb->prefix."term_taxonomy`.`taxonomy`='".$category."'";
        $results = $wpdb->get_results( $sql );

        $cat_list = array();
        $cat_list['All'] = 'themeumall';
        if(is_array($results)){
            foreach ($results as $value) {
                $cat_list[$value->name] = $value->slug;
            }
        }
        return $cat_list;
    }
}

function themeum_get_movie_release_year(){
    global $wpdb;
    $sql = "SELECT DISTINCT `meta_value` FROM `".$wpdb->prefix."postmeta` WHERE `meta_key`='themeum_movie_release_year'";
    $results = $wpdb->get_results( $sql );
    return $results;
}

include_once( 'themeum-flight/themeum-flight.php' );

//post type
include_once( 'post-type/hotel.php' );
//include_once( 'post-type/room-type.php' );
//include_once( 'post-type/schedule.php' );
include_once( 'post-type/vehicle.php' );
include_once( 'post-type/package.php' );
include_once( 'post-type/order.php' );
include_once( 'post-type/photo-gallery.php' );

// Metabox Include
include_once( 'post-type/meta_box.php' );
include_once( 'post-type/meta-box/meta-box.php' );
include_once( 'post-type/meta-box-group/meta-box-group.php' );
//include_once( 'schedules-meta/schedules-meta.php' );

//widget
require_once('widgets/themeum_about_widget.php');
require_once('widgets/image_widget.php');
require_once('widgets/themeum_social_share.php');


// Custom Customizer
include_once( 'customizer/libs/googlefonts.php' );
include_once( 'customizer/customizer.php' );

// shortcode lists
include_once( 'vc-addons/fontawesome-helper.php' );
include_once( 'vc-addons/fontbistro-helper.php' );
include_once( 'vc-addons/animation-list.php' );

include_once( 'vc-addons/themeum-flight-result.php' );




include_once( 'vc-addons/themeum-action.php' );
include_once( 'vc-addons/themeum-popup-video.php' );
include_once( 'vc-addons/feature-carousel.php' );
include_once( 'vc-addons/themeum-gallery-list.php' );
include_once( 'vc-addons/themeum-title.php' );
include_once( 'vc-addons/themeum-latest-post.php' );
include_once( 'vc-addons/themeum-partner.php' );
include_once( 'vc-addons/popular-package.php' );
include_once( 'vc-addons/location-list.php' );
include_once( 'vc-addons/hotel-listing.php' );
include_once( 'vc-addons/vehicle-listing.php' );
include_once( 'vc-addons/popular-package-listing.php' );
include_once( 'vc-addons/themeum-package-search.php' );
//include_once( 'vc-addons/themeum-package-search2.php' );
include_once( 'vc-addons/themeum-persons.php' );
include_once( 'vc-addons/themeum-icon-listing.php' );
include_once( 'vc-addons/themeum-button.php' );


// Paypal Payment
include_once( 'payment/vendor/autoload.php' );
include_once( 'payment/themeum_payment.php' );

//Admin Menu
include_once( 'admin/menus.php' );

// Add shortcode to contact form 7 plugin
include_once( 'payment/paypal-button.php' );


add_action( 'plugins_loaded', 'load_themeum_slider2' );
function load_themeum_slider2(){
    include_once( 'vc-addons/themeum-slider2.php' );
}

/**
 * Package Single Template
 *
 * @return string
 */

/*function themeum_core_package_template($single_template) {
    global $post;
    if ($post->post_type == 'package') {
        $single_template = dirname( __FILE__ ) . '/templates/package-single.php';
    }
    return $single_template;
}
function themeum_core_vehicle_template($single_template) {
    global $post;
    if ($post->post_type == 'vehicle') {
        $single_template = dirname( __FILE__ ) . '/templates/vehicle-single.php';
    }
    return $single_template;
}
add_filter( "single_template", "themeum_core_vehicle_template" ) ;*/

// package taxonomy template
// add_filter('template_include', 'themeum_taxonomy_package_template');
function themeum_taxonomy_package_template( $template ){
    if( is_tax('package-category') ){
        $template = dirname( __FILE__ ).'/templates/package-category.php';
    }
    return $template;
}

// Add CSS for Frontend
add_action( 'wp_enqueue_scripts', 'themeum_core_style' );
if(!function_exists('themeum_core_style')):
    function themeum_core_style(){
        # CSS
        wp_enqueue_style('animate',plugins_url('assets/css/animate.css',__FILE__));
        wp_enqueue_style('select2',plugins_url('customizer/assets/select2/css/select2.min.css',__FILE__));
        wp_enqueue_style('magnific-popup',plugins_url('assets/css/magnific-popup.css',__FILE__));
        wp_enqueue_style('owl-carousel',plugins_url('assets/css/owl-carousel.css',__FILE__));
         wp_enqueue_style('themeum-core',plugins_url('assets/css/themeum-core.css',__FILE__));


        # JS
        wp_enqueue_script('checkout-stripe','https://checkout.stripe.com/checkout.js',array(),false,true);
        wp_dequeue_script( 'jquery-ui-datepicker');
        wp_enqueue_script( 'jquery-datepicker', plugins_url('assets/js/jquery-ui.js',__FILE__), array('jquery'), false );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script('wow',plugins_url('assets/js/wow.js',__FILE__), array('jquery'));
        wp_enqueue_script('select2',plugins_url('customizer/assets/select2/js/select2.js',__FILE__), array('jquery'));
        wp_enqueue_script('timepicker',plugins_url('assets/js/jquery-ui-timepicker-addon.js',__FILE__), array('jquery', 'jquery-ui-datepicker', 'jquery-ui-slider'));
        wp_enqueue_script('main',plugins_url('assets/js/main.js',__FILE__), array('jquery'));
        wp_localize_script( 'main', 'thm_flight', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        wp_enqueue_script('owl-carousel-min',plugins_url('assets/js/owl-carousel-min.js',__FILE__), array('jquery'));
        wp_enqueue_script('jquery.magnific-popup.min',plugins_url('assets/js/jquery.magnific-popup.min.js',__FILE__), array('jquery'));
    }
endif;

function themeum_load_admin_assets() {
    wp_enqueue_script( 'themeum-admin', plugins_url('assets/js/admin.js', __FILE__), array('jquery') );
}
add_action( 'admin_enqueue_scripts', 'themeum_load_admin_assets' );





/*-----------------------------------------------------
 *              Custom Excerpt Length
 *----------------------------------------------------*/

if(!function_exists('themeum_get_video_id')){
    function themeum_get_video_id($url){
        $video = parse_url($url);

        switch($video['host']) {
            case 'youtu.be':
            $id = trim($video['path'],'/');
            $src = 'https://www.youtube.com/embed/' . $id;
            break;

            case 'www.youtube.com':
            case 'youtube.com':
            parse_str($video['query'], $query);
            $id = $query['v'];
            $src = 'https://www.youtube.com/embed/' . $id;
            break;

            case 'vimeo.com':
            case 'www.vimeo.com':
            $id = trim($video['path'],'/');
            $src = "http://player.vimeo.com/video/{$id}";
        }

        return $src;
    }
}

/** Add Custom Field To Category Form */
add_action( 'package-location_add_form_fields', 'package_location_form_custom_field_add', 10 );
add_action( 'package-location_edit_form_fields', 'package_location_form_custom_field_edit', 10, 2 );


function package_location_form_custom_field_add( $taxonomy ) {
?>
<div class="form-field">
  <label for="category_custom_order"><?php _e('Location image url','themeum-core');?></label>
  <input name="category_custom_order" id="category_custom_order" type="text" value="" size="40" aria-required="true" />
</div>
<?php
}

function package_location_form_custom_field_edit( $tag, $taxonomy ) {

    $option_name = 'package-location_custom_order_' . $tag->term_id;
    $package_location_custom_order = get_option( $option_name );
?>
<tr class="form-field">
  <th scope="row" valign="top"><label for="package-location_custom_order"><?php _e('Location image url','themeum-core');?></label></th>
  <td>
    <input type="text" name="package-location_custom_order" id="package-location_custom_order" value="<?php echo esc_attr( $package_location_custom_order ) ? esc_attr( $package_location_custom_order ) : ''; ?>" size="40" aria-required="true" />
  </td>
</tr>
<?php
}

/** Save Custom Field Of location Form */
add_action( 'created_package-location', 'location_form_custom_field_save', 10, 2 );
add_action( 'edited_package-location', 'location_form_custom_field_save', 10, 2 );

function location_form_custom_field_save( $term_id, $tt_id ) {

    if ( isset( $_POST['package-location_custom_order'] ) ) {
        $option_name = 'package-location_custom_order_' . $term_id;
        update_option( $option_name, sanitize_text_field( $_POST['package-location_custom_order'] ) );


    }

}

