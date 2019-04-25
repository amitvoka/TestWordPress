<?php

define('TRAVELKIT_NAME', wp_get_theme()->get( 'Name' ));
define('TRAVELKIT_CSS', get_template_directory_uri().'/css/');
define('TRAVELKIT_JS', get_template_directory_uri().'/js/');




//TGM Plugin
require_once( get_template_directory()  . '/lib/class-tgm-plugin-activation.php');

/*-------------------------------------------*
 *				Register Navigation
 *------------------------------------------*/
register_nav_menus( array(
	'mainmenu' => esc_html__( 'Main Menu', 'travelkit' ),
) );

/*-------------------------------------------*
 *				title tag
 *------------------------------------------*/


add_action( 'after_setup_theme', 'travelkit_slug_setup' );
if(!function_exists( 'travelkit_slug_setup' )):
    function travelkit_slug_setup() {
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-formats', array( 'link', 'quote' ) );
    }
endif;

/*-------------------------------------------*
 *				navwalker
 *------------------------------------------*/
//Main Navigation
require_once( get_template_directory()  . '/lib/menu/admin-megamenu-walker.php');
require_once( get_template_directory()  . '/lib/menu/meagmenu-walker.php');
require_once( get_template_directory()  . '/lib/menu/mobile-navwalker.php');
//Admin mega menu
add_filter( 'wp_edit_nav_menu_walker', function( $class, $menu_id ){
	return 'Themeum_Megamenu_Walker';
}, 10, 2 );


/*-------------------------------------------*
 *				Startup Register
 *------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/themeum-register.php');



/*-------------------------------------------------------
 *			Themeum Core
 *-------------------------------------------------------*/
require_once( get_template_directory()  . '/lib/main-function/themeum-core.php');




/*-----------------------------------------------------
 * 				Custom Excerpt Length
 *----------------------------------------------------*/

if(!function_exists('travelkit_excerpt_max_charlength')):
	function travelkit_excerpt_max_charlength($charlength) {
		$excerpt = get_the_excerpt();
		$charlength++;

		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex = mb_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				return mb_substr( $subex, 0, $excut );
			} else {
				return $subex;
			}

		} else {
			return $excerpt;
		}
	}
endif;


function travelkit_room_types( $post_id )
{
	if (get_post_type($post_id) == 'hotel') {
		$rooms = get_post_meta($post_id,'themeum_room_info',true);
		if ($rooms && is_array($rooms)) {
			foreach ($rooms as $room) {
				if (isset($room['themeum_hotelroomtype']) && !empty($room['themeum_hotelroomtype'])) {
					add_post_meta($post_id,'_room_type',$room['themeum_hotelroomtype']);
				}
			}
		}

	}

}
add_action( 'save_post', 'travelkit_room_types' );

function travelkit_search_title($title)
{
	$search   = get_query_var( 's' );

	if ( is_search() && (get_post_type() == 'hotel' || get_post_type() == 'package' || get_post_type() == 'vehicle') ) {
        /* translators: 1: separator, 2: search phrase */
        $title['title'] = sprintf( esc_html__( '%1$s Search Results : "%2$s"', 'travelkit' ), ucwords(get_post_type()), strip_tags( $search ) );

    }

	return $title;
}
add_filter( 'document_title_parts', 'travelkit_search_title' );


if (!function_exists('travelkit_get_currency_symbol')) {
	function travelkit_get_currency_symbol($currency)
	{
		$symols = array(
			'AUD' =>  '$',
			'BRL' =>  'R$',
			'CAD' =>  '$',
			'CZK' =>  'Kč',
			'DKK' =>  'kr.',
			'EUR' =>  '€',
			'HKD' =>  'HK$',
			'HUF' =>  'Ft',
			'ILS' =>  '₪',
			'JPY' =>  '¥',
			'MYR' =>  'RM',
			'MXN' =>  'Mex$',
			'NOK' =>  'kr',
			'NZD' =>  '$',
			'PHP' =>  '₱',
			'PLN' =>  'zł',
			'GBP' =>  '£',
			'RUB' =>  '₽',
			'SGD' =>  '$',
			'SEK' =>  'kr',
			'CHF' =>  'CHF',
			'TWD' =>  '角',
			'THB' =>  '฿',
			'TRY' =>  'TRY',
			'USD' =>  '$',
		);

		if (isset($symols[$currency])) {
			return $symols[$currency];
		} else {
			return '$';
		}
	}
}




/*-------------------------------------------*
 *				woocommerce support
 *------------------------------------------*/

add_action( 'after_setup_theme', 'travelkit_woocommerce_support' );
function travelkit_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

/*-----------------------------------------------------
 * 				Custom body class
 *----------------------------------------------------*/
add_filter( 'body_class', 'travelkit_body_class' );
function travelkit_body_class( $classes ) {
    $layout = get_theme_mod( 'boxfull_en', 'fullwidth' );
    $classes[] = $layout.'-bg';
	return $classes;
}



function travelkit_customize_control_js() {
	wp_enqueue_script( 'color-preset-control', get_template_directory_uri() . '/js/color-scheme-control.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '20141216', true );
}
add_action( 'customize_controls_enqueue_scripts', 'travelkit_customize_control_js' );
