<?php
/**
 * Admin feature for Custom Meta Box
 *
 * @author 		Themeum
 * @category 	Admin Core
 * @package 	Varsity
 *-------------------------------------------------------------*/


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Registering meta boxes
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */

add_filter( 'rwmb_meta_boxes', 'themeum_travelkit_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @return void
 */

function themeum_travelkit_register_meta_boxes( $meta_boxes )
{

	/**
	 * Prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 */

	// Better has an underscore as last sign
	$prefix = 'themeum_';
		$contact_forms = array();
	    $contact_forms = get_all_posts('wpcf7_contact_form');
	    $contact_forms['Select'] = 'Select'; 

	/**
	 * Register Post Meta for Movie Post Type
	 *
	 * @return array
	 */


	// --------------------------------------------------------------------------------------------------------------	
	// ----------------------------------------- Post Open ----------------------------------------------------------	
	// --------------------------------------------------------------------------------------------------------------
	$meta_boxes[] = array(
		'id' => 'post-meta-quote',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => esc_html__( 'Post Quote Settings', 'themeum-core' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				// Field name - Will be used as label
				'name'  => esc_html__( 'Quote Text', 'themeum-core' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}qoute",
				'desc'  => esc_html__( 'Write Your Quote Here', 'themeum-core' ),
				'type'  => 'textarea',
				// Default value (optional)
				'std'   => ''
			),
			array(
				// Field name - Will be used as label
				'name'  => esc_html__( 'Quote Author', 'themeum-core' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}qoute_author",
				'desc'  => esc_html__( 'Write Quote Author or Source', 'themeum-core' ),
				'type'  => 'text',
				// Default value (optional)
				'std'   => ''
			)
			
		)
	);



	$meta_boxes[] = array(
		'id' => 'post-meta-link',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => esc_html__( 'Post Link Settings', 'themeum-core' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				// Field name - Will be used as label
				'name'  => esc_html__( 'Link URL', 'themeum-core' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}link",
				'desc'  => esc_html__( 'Write Your Link', 'themeum-core' ),
				'type'  => 'text',
				// Default value (optional)
				'std'   => ''
			)
			
		)
	);


	$meta_boxes[] = array(
		'id' => 'post-meta-audio',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => esc_html__( 'Post Audio Settings', 'themeum-core' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				// Field name - Will be used as label
				'name'  => esc_html__( 'Audio Embed Code', 'themeum-core' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}audio_code",
				'desc'  => esc_html__( 'Write Your Audio Embed Code Here', 'themeum-core' ),
				'type'  => 'textarea',
				// Default value (optional)
				'std'   => ''
			)
			
		)
	);

	$meta_boxes[] = array(
		'id' => 'post-meta-video',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => esc_html__( 'Post Video Settings', 'themeum-core' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				// Field name - Will be used as label
				'name'  => esc_html__( 'Video Embed Code/ID', 'themeum-core' ),
				// Field ID, i.e. the meta key
				'id'    => "{$prefix}video",
				'desc'  => esc_html__( 'Write Your Vedio Embed Code/ID Here', 'themeum-core' ),
				'type'  => 'textarea',
				// Default value (optional)
				'std'   => ''
			),
			array(
				'name'  => __( 'Video Durations', 'themeum-core' ),
				'id'    => "{$prefix}video_durations",
				'type'  => 'text',
				'std'   => ''
			),			
			array(
				'name'     => esc_html__( 'Select Vedio Type/Source', 'themeum-core' ),
				'id'       => "{$prefix}video_source",
				'type'     => 'select',
				// Array of 'value' => 'Label' pairs for select box
				'options'  => array(
					'1' => esc_html__( 'Embed Code', 'themeum-core' ),
					'2' => esc_html__( 'YouTube', 'themeum-core' ),
					'3' => esc_html__( 'Vimeo', 'themeum-core' ),
				),
				// Select multiple values, optional. Default is false.
				'multiple'    => false,
				'std'         => '1'
			),
			
		)
	);


	$meta_boxes[] = array(
		'id' => 'post-meta-gallery',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => esc_html__( 'Post Gallery Settings', 'themeum-core' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'post'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// Auto save: true, false (default). Optional.
		'autosave' => true,

		// List of meta fields
		'fields' => array(
			array(
				'name'             => esc_html__( 'Gallery Image Upload', 'themeum-core' ),
				'id'               => "{$prefix}gallery_images",
				'type'             => 'image_advanced',
				'max_file_uploads' => 6,
			)			
		)
	);
	// --------------------------------------------------------------------------------------------------------------	
	// ----------------------------------------- Post Close ---------------------------------------------------------	
	// --------------------------------------------------------------------------------------------------------------


	// --------------------------------------------------------------------------------------------------------------	
	// ----------------------------------------- Page Open ----------------------------------------------------------	
	// --------------------------------------------------------------------------------------------------------------
	$meta_boxes[] = array(
		'id' => 'page-meta-settings',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' => esc_html__( 'Page Settings', 'themeum-core' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' => array( 'page'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' => 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' => 'high',

		// List of meta fields
		'fields' => array(
			array(
				'name'             => esc_html__( 'Upload Sub Title Banner Image', 'themeum-core' ),
				'id'               => $prefix."subtitle_images",
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
			),	

			array(
				'name'             => esc_html__( 'Upload Sub Title BG Color', 'themeum-core' ),
				'id'               => "{$prefix}subtitle_color",
				'type'             => 'color',
				'std' 			   => "#191919"
			),	

			array(
				'name'  => esc_html__( 'Sub title', 'themeum-core' ),
				'id'    => "{$prefix}sub_title_text",
				'desc'  => esc_html__( 'Sub Title', 'themeum-core' ),
				'type'  => 'text',
				'std'   => ''
			)		
		)
	);	
	// --------------------------------------------------------------------------------------------------------------	
	// ----------------------------------------- Page Close ---------------------------------------------------------	
	// --------------------------------------------------------------------------------------------------------------


	// --------------------------------------------------------------------------------------------------------------	
	// ----------------------------------------- Hotel Open ----------------------------------------------------------	
	// --------------------------------------------------------------------------------------------------------------
		$meta_boxes[] = array(
		'id' 		=> 'hotel-additional-info-setting',
		'title' 	=> esc_html__( 'Additional Info Settings', 'themeum-core' ),
		'pages' 	=> array('hotel'),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
		'autosave' 	=> true,
		'fields' 	=> array(	

			array(
				'name'  	=> __( 'Contact Form', 'themeum-core' ),
				'id'    	=> "{$prefix}contact_forms",
				'desc'  	=> __( 'Contact Form', 'themeum-core' ),
				'type'  	=> 'select',
				'std'   	=> '',
				'options'	=> $contact_forms
			),
			array(
				'name'  	=> esc_html__( 'Check-in time', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelcheckin",
				'desc'  	=> esc_html__( 'Check-in time', 'themeum-core' ),
				'type'  	=> 'text',
				'std'   	=> 'After 12 PM'
			),	
			array(
				'name'  	=> esc_html__( 'Check-out time', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelcheckout",
				'desc'  	=> esc_html__( 'Check-out time', 'themeum-core' ),
				'type'  	=> 'text',
				'std'   	=> 'Before 12 PM'
			),	
			array(
				'name'  	=> esc_html__( 'Duration', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelduration",
				'desc'  	=> esc_html__( 'Duration', 'themeum-core' ),
				'type'  	=> 'text',
				'std'   	=> ''
			),			
			array(
				'name'  	=> esc_html__( 'Room', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelroom",
				'desc'  	=> esc_html__( 'Room Number', 'themeum-core' ),
				'type'  	=> 'text',
				'std'   	=> '200'
			),
			array(
				'name'  	=> __( 'Help Info', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelhelp",
				'type'  	=> 'heading',
				'std'   	=> ''
			),
			array(
				'name'  	=> __( 'Help Short Information', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelhelptext",
				'desc'  	=> __( 'Short Information', 'themeum-core' ),
				'type'  	=> 'textarea',
				'std'   	=> ''
			),	
			array(
				'name'  	=> __( 'Contact Number', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelcontactnum",
				'desc'  	=> __( 'Contact Number', 'themeum-core' ),
				'type'  	=> 'text',
				'std'   	=> ''
			),	
			array(
				'name'  	=> __( 'Contact Email', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelcontactemail",
				'desc'  	=> __( 'Contact Email', 'themeum-core' ),
				'type'  	=> 'text',
				'std'   	=> ''
			),
			array(
				'name'  	=> __( 'Hotel Social Share', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelesocial",
				'type'  	=> 'heading',
				'std'   	=> ''
			),
			array(
				'name'  	=> __( 'Facebook URL', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelefb_url",
				'type'  	=> 'url',
				'std'   	=> ''
			),
			array(
				'name'  	=> __( 'Twitter URL', 'themeum-core' ),
				'id'    	=> "{$prefix}hoteletw_url",
				'type'  	=> 'url',
				'std'   	=> ''
			),				
			array(
				'name'  	=> __( 'Google Plus URL', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelegplus_url",
				'type'  	=> 'url',
				'std'   	=> ''
			),
			array(
				'name'  	=> __( 'Youtube URL', 'themeum-core' ),
				'id'    	=> "{$prefix}hoteleyoutube_url",
				'type'  	=> 'url',
				'std'   	=> ''
			),
			array(
				'name'  	=> __( 'Instagram URL', 'themeum-core' ),
				'id'    	=> "{$prefix}hoteleinstagram_url",
				'type'  	=> 'url',
				'std'   	=> ''
			),
			array(
				'name'  	=> __( 'Website URL', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelewebsite_url",
				'type'  	=> 'url',
				'std'   	=> ''
			),
			array(
				'name'  	=> __( 'Hotel short note', 'themeum-core' ),
				'id'    	=> "{$prefix}hotelshortnote",
				'type'  	=> 'heading',
				'std'   	=> ''
			),			
			array(
				'name' 		=> __( 'Hotel short note', 'themeum-core' ),
				'id'   		=> "{$prefix}hotelintro",
				'type' 		=> 'textarea',
				'std'  		=> ''
			),

		)
	);

	// --------------------------------------------------------------------------------------------------------------	
	// ----------------------------------------- Hotel room info ----------------------------------------------------------	
	// --------------------------------------------------------------------------------------------------------------
		$meta_boxes[] = array(
		'id' => 'hotel-room-info-setting',
		'title' => esc_html__( 'Hotel Room Info Settings', 'themeum-core' ),
		'pages' => array('hotel'),
		'context' => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields' => array(	
			array(
				'name'   => esc_html__( 'Room Information', 'themeum-core' ),
				'id'     => 'themeum_room_info',
				'type'   => 'group',
				'fields' => array(	

					array(
						'name'          => __( 'Room Type', 'themeum-core' ),
						'id'            => "{$prefix}hotelroomtype",
						'desc'			=> __( 'Room Type', 'themeum-core' ),
						'type'          => 'select_advanced',
						'options' 		=> array(
				            esc_html__( 'Double Room', 'themeum-core' ) =>  esc_html__( 'Double Room', 'themeum-core' ),
				            esc_html__( 'Single Room', 'themeum-core' ) => esc_html__( 'Single Room', 'themeum-core' ),
				            esc_html__( 'Luxury Room', 'themeum-core' ) => esc_html__( 'Luxury Room', 'themeum-core' ),
				            esc_html__( 'General Room', 'themeum-core' ) => esc_html__( 'General Room', 'themeum-core' ),
				            esc_html__( 'Family Room', 'themeum-core' ) => esc_html__( 'Family Room', 'themeum-core' ),
				            esc_html__( 'Deluxe Room', 'themeum-core' ) => esc_html__( 'Deluxe Room', 'themeum-core' ),
				            esc_html__( 'Presidential Suite', 'themeum-core' ) => esc_html__( 'Presidential Suite', 'themeum-core' ),
				            esc_html__( 'Royal Suite', 'themeum-core' ) => esc_html__( 'Royal Suite', 'themeum-core' ),
				            esc_html__( 'Premier Suite', 'themeum-core' ) => esc_html__( 'Premier Suite', 'themeum-core' ),
				            esc_html__( 'Honeymoon Suite', 'themeum-core' ) => esc_html__( 'Honeymoon Suite', 'themeum-core' ),
				            esc_html__( 'Executive Suite', 'themeum-core' ) => esc_html__( 'Executive Suite', 'themeum-core' ),
				            esc_html__( 'Standard Room', 'themeum-core' ) => esc_html__( 'Standard Room', 'themeum-core' ),
			       		 ),
						'multiple'    => false,
						'std'           => '',
					),								
					array(
						'name'  => esc_html__( 'Room Price', 'themeum-core' ),
						'id'    => "{$prefix}roomprice",
						'desc'  => esc_html__( 'Room Price Ex. $80', 'themeum-core' ),
						'type'  => 'text',
						'std'   => ''
					),						
					array(
						'name'  		=> __( 'Short Room Information', 'themeum-core' ),
						'id'    		=> "{$prefix}hotelroomtext",
						'desc'  		=> __( 'Short Room Information', 'themeum-core' ),
						'type'  		=> 'textarea',
						'std'   		=> ''
					),							
					array(
						'name'             => esc_html__( 'Room Gallery', 'themeum-core' ),
						'id'               => "themeum_roomgallery",
						'type'             => 'image_advanced',
						'max_file_uploads' => 20,
					),	
				),
				'clone'  => true,
			),
		)
	);			

	// --------------------------------------------------------------------------------------------------------------	
	// ----------------------------------------- Hotel Close ---------------------------------------------------------	
	// --------------------------------------------------------------------------------------------------------------

	// --------------------------------------------------------------------------------------------------------------	
	// ----------------------------------------- package Open ---------------------------------------------------------	
	// --------------------------------------------------------------------------------------------------------------

	$meta_boxes[] = array(
		'id' => 'package-information-meta-setting',
		'title' => esc_html__( 'Infomation', 'themeum-core' ),
		'pages' => array( 'package', 'hotel'),
		'context' => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields' => array(

			array(
				'name'  		=> __( 'Booking Info', 'themeum-core' ),
				'id'    		=> "{$prefix}packbooking",
				'type'  		=> 'heading',
				'std'   		=> ''
			),

			array(
				'name'  		=> __( 'Book Now Button Text', 'themeum-core' ),
				'id'    		=> "{$prefix}packbooknow",
				'desc'  		=> __( 'Book Now', 'themeum-core' ),
				'type'  		=> 'text',
				'std'   		=> ''
			),	
			array(
				'name'  		=> __( 'Disable Booking Button:', 'themeum-core' ),
				'id'    		=> "{$prefix}packbookingtrigger",
				'type'  		=> 'checkbox',
				'std'   		=> '1'
			),

			array(
				'name'  		=> __( 'Book Now External Link', 'themeum-core' ),
				'id'    		=> "{$prefix}external_link",
				'desc'  		=> __( 'Set book now button to external link. Leave it empty for default.', 'themeum-core' ),
				'type'  		=> 'text',
				'std'   		=> ''
			),	

			array(
				'name'  		=> __( 'Why Choose Info', 'themeum-core' ),
				'id'    		=> "{$prefix}packchoose",
				'type'  		=> 'heading',
				'std'   		=> ''
			),	

			array(
				'name'          => __( 'Why Choose List', 'themeum-core' ),
				'id'            => "{$prefix}packchooselist",
				'desc'			=> __( 'Add Why Choose List', 'themeum-core' ),
				'type'          => 'text',
				'std'           => '',
				'clone'			=> true
			),

			array(
				'name'  		=> __( 'Gallery Info', 'themeum-core' ),
				'id'    		=> "{$prefix}packgalleryinfo",
				'type'  		=> 'heading',
				'std'   		=> ''
			),
			array(
				'name'  		=> __( 'Package Gallery Items', 'themeum-core' ),
				'id'    		=> "{$prefix}packgallery",
				'type'  		=> 'image_advanced',
				'std'   		=> ''
			),
			
		)
	);

	// Additional Info Settings
	$meta_boxes[] = array(
		'id' 		=> 'package-additional-post-meta',
		'title' 	=> __( 'Package Additional Settings', 'themeum-core' ),
		'pages' 	=> array( 'package'),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
		'autosave' 	=> true,
		'fields' 	=> array(
			array(
				'name'       => __( 'Check-in date', 'themeum-core' ),
				'id'         => $prefix.'packcheckin',
				'desc'  	=> __( 'Check in', 'themeum-core' ),
				'type'       => 'date',
				'js_options' => array(
					'appendText'      => __( '(yyyy-mm-dd)', 'themeum-core' ),
					'dateFormat'      => __( 'yy-mm-dd', 'themeum-core' ),
					'changeMonth'     => true,
					'changeYear'      => true,
					'showButtonPanel' => true,
				),				
			),

			array(
				'name'       => __( 'Check-Out date', 'themeum-core' ),
				'id'         => $prefix.'packcheckout',
				'desc'  	=> __( 'Check Out', 'themeum-core' ),
				'type'       => 'date',
				'js_options' => array(
					'appendText'      => __( '(yyyy-mm-dd)', 'themeum-core' ),
					'dateFormat'      => __( 'yy-mm-dd', 'themeum-core' ),
					'changeMonth'     => true,
					'changeYear'      => true,
					'showButtonPanel' => true,
				),
			),	

			array(
				'name'  => esc_html__( 'Duration', 'themeum-core' ),
				'id'    => "{$prefix}packduration",
				'desc'  => esc_html__( 'Package Duration', 'themeum-core' ),
				'type'  => 'text',
				'std'   => '2 Days, 3 Nights'
			),			

			array(
				'name'  => esc_html__( 'Person', 'themeum-core' ),
				'id'    => "{$prefix}packperson",
				'desc'  => esc_html__( 'Person Number', 'themeum-core' ),
				'type'  => 'text',
				'std'   => '2 Person'
			),

			array(
				'name'  => esc_html__( 'Kids', 'themeum-core' ),
				'id'    => "{$prefix}packkids",
				'desc'  => esc_html__( 'Kids Number', 'themeum-core' ),
				'type'  => 'text',
				'std'   => ''
			),

			array(
				'name'  => esc_html__( 'Availability', 'themeum-core' ),
				'id'    => "{$prefix}packavailability",
				'desc'  => esc_html__( 'Availability Number', 'themeum-core' ),
				'type'  => 'text',
				'std'   => '40'
			),

			array(
				'name'  		=> __( 'Price', 'themeum-core' ),
				'id'    		=> "{$prefix}packprice",
				'desc'  		=> __( 'Price', 'themeum-core' ),
				'type'  		=> 'text',
				'std'   		=> '$750'
			),	

			array(
				'name'          => __( 'Location', 'themeum-core' ),
				'id'            => "{$prefix}packlocation",
				'desc'			=> __( 'Add your Location', 'themeum-core' ),
				'type'          => 'textarea',
				'std'           => __( 'Dhaka, Bangladesh', 'themeum-core' ),
			),
			array(
				'name'  		=> __( 'Help Info', 'themeum-core' ),
				'id'    		=> "{$prefix}packhelp",
				'type'  		=> 'heading',
				'std'   		=> ''
			),

			array(
				'name'  		=> __( 'Help Short Information', 'themeum-core' ),
				'id'    		=> "{$prefix}packhelptext",
				'desc'  		=> __( 'Short Information', 'themeum-core' ),
				'type'  		=> 'textarea',
				'std'   		=> ''
			),	

			array(
				'name'  		=> __( 'Contact Number', 'themeum-core' ),
				'id'    		=> "{$prefix}packcontactnum",
				'desc'  		=> __( 'Contact Number', 'themeum-core' ),
				'type'  		=> 'text',
				'std'   		=> ''
			),	
			array(
				'name'  		=> __( 'Contact Email', 'themeum-core' ),
				'id'    		=> "{$prefix}packcontactemail",
				'desc'  		=> __( 'Contact Email', 'themeum-core' ),
				'type'  		=> 'text',
				'std'   		=> ''
			),

			array(
				'name'  		=> __( 'Contact Form', 'themeum-core' ),
				'id'    		=> "{$prefix}contact_forms",
				'desc'  		=> __( 'Contact Form', 'themeum-core' ),
				'type'  		=> 'select',
				'std'   		=> '',
				'options'		=> $contact_forms
			),


	));

	// Hotel Info Settings
	$meta_boxes[] = array(
		'id' 		=> 'package-hotel-post-meta',
		'title' 	=> __( 'Package Hotel Settings', 'themeum-core' ),
		'pages' 	=> array( 'package'),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
		'autosave' 	=> true,
		'fields' 	=> array(

			array(
				'name'       => __( 'Hotel List', 'themeum-core' ),
				'id'         => 'packhotel',
				'type'       => 'post',
				'post_type'  => 'hotel',
				'field_type' => 'select_advanced',
				'query_args' => array(
					'post_status'    => 'publish',
					'posts_per_page' => '-1',
				),
				'multiple'    => true,
			),						

	));

	// Itinerary Info Settings
	$meta_boxes[] = array(
		'id' 		=> 'package-itinerary-post-meta',
		'title' 	=> __( 'Package Itinerary Settings', 'themeum-core' ),
		'pages' 	=> array( 'package'),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
		'autosave' 	=> true,
		'fields' 	=> array(

			array(
			    'name' => '', // Optional
			    'id' => 'themeum_package_itinerary_info',
			    'type'   => 'group',
			    'clone'  => true,
			    'fields' => array(
					array(
						'name'          => __( 'Day', 'themeum-core' ),
						'id'            => "{$prefix}packitineraryday",
						'type'          => 'text',
						'std'           => ''
					),
					array(
						'name'          => __( 'Place Name', 'themeum-core' ),
						'id'            => "{$prefix}packitineraryname",
						'type'          => 'text',
						'std'           => ''
					),
					array(
						'name'          => __( 'Itinerary Short Note', 'themeum-core' ),
						'id'            => "{$prefix}packitineraryintro",
						'type'          => 'textarea',
						'std'           => ''
					),
					array(
						'name'             => esc_html__( 'Itinerary Photo', 'themeum-core' ),
						'id'               => $prefix."packhotelgallery",
						'type'             => 'image_advanced',
						'max_file_uploads' => 1,
					),
					
			    ),
			),

	));

	// Tour Guide Settings
	$meta_boxes[] = array(
		'id' 		=> 'package-tour-guide-post-meta',
		'title' 	=> __( 'Package Tour Guide Settings', 'themeum-core' ),
		'pages' 	=> array( 'package'),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
		'autosave' 	=> true,
		'fields' 	=> array(

			array(
			    'name' => '', // Optional
			    'id' => 'themeum_package_tour_guide',
			    'type'   => 'group',
			    'clone'  => true,
			    'fields' => array(
					array(
						'name'          => __( 'Guide Name', 'themeum-core' ),
						'id'            => "{$prefix}packguidename",
						'type'          => 'text',
						'std'           => ''
					),
				
					array(
						'name'          => __( 'Guide Short Note', 'themeum-core' ),
						'id'            => "{$prefix}packguideintro",
						'type'          => 'textarea',
						'std'           => ''
					),
					array(
						'name'             => esc_html__( 'Guide Image', 'themeum-core' ),
						'id'               => $prefix."packguideimg",
						'type'             => 'image_advanced',
						'max_file_uploads' => 1,
					),
					array(
						'name'  		=> __( 'Guide Social Share', 'themeum-core' ),
						'id'    		=> "{$prefix}packguidesocial",
						'type'  		=> 'heading',
						'std'   		=> ''
					),
					array(
						'name'  		=> __( 'Facebook URL', 'themeum-core' ),
						'id'    		=> "{$prefix}packguidefb_url",
						'type'  		=> 'url',
						'std'   		=> ''
					),
					array(
						'name'  		=> __( 'Twitter URL', 'themeum-core' ),
						'id'    		=> "{$prefix}packguidetw_url",
						'type'  		=> 'url',
						'std'   		=> ''
					),				
					array(
						'name'  		=> __( 'Google Plus URL', 'themeum-core' ),
						'id'    		=> "{$prefix}packguidegplus_url",
						'type'  		=> 'url',
						'std'   		=> ''
					),
					array(
						'name'  		=> __( 'Youtube URL', 'themeum-core' ),
						'id'    		=> "{$prefix}packguideyoutube_url",
						'type'  		=> 'url',
						'std'   		=> ''
					),
					array(
						'name'  		=> __( 'Instagram URL', 'themeum-core' ),
						'id'    		=> "{$prefix}packguideinstagram_url",
						'type'  		=> 'url',
						'std'   		=> ''
					),
					array(
						'name'  		=> __( 'Website URL', 'themeum-core' ),
						'id'    		=> "{$prefix}packguidewebsite_url",
						'type'  		=> 'url',
						'std'   		=> ''
					),		
			    ),
			),

	));



	// Video Info Settings
	$meta_boxes[] = array(
		'id' 		=> 'package-video-post-meta',
		'title' 	=> __( 'Video Settings', 'themeum-core' ),
		'pages' 	=> array( 'package', 'hotel'),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
		'autosave' 	=> true,
		'fields' 	=> array(
			// Video Info
			array(
			    'name' => '', // Optional
			    'id' => 'themeum_package_video_info',
			    'type'   => 'group',
			    'clone'  => true,
			    'fields' => array(
					array(
						'name'          => __( 'Video Info Title', 'themeum-core' ),
						'id'            => "{$prefix}packvideotitle",
						'type'          => 'text',
						'std'           => ''
					),
					array(
						'name'     => esc_html__( 'Select Video Type/Source', 'themeum-core' ),
						'id'       => "{$prefix}packvideosource",
						'type'     => 'select',
						'options'  => array(
										'' 	=> esc_html__( 'None', 'themeum-core' ),
										'youtube' 	=> esc_html__( 'YouTube', 'themeum-core' ),
										'vimeo' 	=> esc_html__( 'Vimeo', 'themeum-core' ),
									),
						'multiple'    => false,
						'std'         => '1'
					),				
					array(
						'name'  		=> __( 'Video', 'themeum-core' ),
						'id'    		=> "{$prefix}packvideolink",
						'type'  		=> 'text',
						'std'   		=> '',
						'desc'  		=> __( 'Add Video ID for Youtube and Vimeo / Video URL', 'themeum-core' ),
					),
					array(
						'name'             => esc_html__( 'Upload Video Image', 'themeum-core' ),
						'id'               => $prefix."packvideoimg",
						'type'             => 'image_advanced',
						'max_file_uploads' => 1,
					),
					
			    ),
			),

	));

	$meta_boxes[] = array(
		'id' => 'package-map-meta-setting',
		'title' => esc_html__( 'Map Settings', 'themeum-core' ),
		'pages' => array( 'package', 'hotel'),
		'context' => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields' => array(	

			array(
				'name'  		=> __( 'Map', 'themeum-core' ),
				'id'    		=> "{$prefix}packnmap",
				'desc'  		=> '',
				'type'  		=> 'map',
				'std'           => '23.709921,90.40714300000002,16',
				'style'         => 'width: 500px; height: 260px;',
				'address_field' => "{$prefix}packlocation", 
			),
			array(
				'name'  		=> __( 'Disable Map From Single Page:', 'themeum-core' ),
				'id'    		=> "{$prefix}packmaptrigger",
				'type'  		=> 'checkbox',
				'std'   		=> '1'
			),

		)
	);


	// --------------------------------------------------------------------------------------------------------------	
	// ----------------------------------------- Vehicle Open ----------------------------------------------------------	
	// --------------------------------------------------------------------------------------------------------------

	// Additional Info Settings
	$meta_boxes[] = array(
		'id' 		=> 'vehicle-additional-post-meta',
		'title' 	=> __( 'Vehicle Additional Settings', 'themeum-core' ),
		'pages' 	=> array( 'vehicle'),
		'context' 	=> 'normal',
		'priority' 	=> 'high',
		'autosave' 	=> true,
		'fields' 	=> array(
			array(
				'name'  		=> __( 'Contact Form', 'themeum-core' ),
				'id'    		=> "{$prefix}contact_forms",
				'desc'  		=> __( 'Contact Form', 'themeum-core' ),
				'type'  		=> 'select',
				'std'   		=> '',
				'options'		=> $contact_forms
			),
			array(
				'name'  		=> __( 'Booking Info', 'themeum-core' ),
				'id'    		=> "{$prefix}vehiclebooking",
				'type'  		=> 'heading',
				'std'   		=> ''
			),

			array(
				'name'  		=> __( 'Book Now Button Text', 'themeum-core' ),
				'id'    		=> "{$prefix}vehiclebooknow",
				'desc'  		=> __( 'Book Now', 'themeum-core' ),
				'type'  		=> 'text',
				'std'   		=> ''
			),	
			array(
				'name'  		=> __( 'Disable Booking Button:', 'themeum-core' ),
				'id'    		=> "{$prefix}vehiclebookingtrigger",
				'type'  		=> 'checkbox',
				'std'   		=> '1'
			),

			array(
				'name'  		=> __( 'Book Now External Link', 'themeum-core' ),
				'id'    		=> "{$prefix}external_link",
				'desc'  		=> __( 'Set book now button to external link. Leave it empty for default.', 'themeum-core' ),
				'type'  		=> 'text',
				'std'   		=> ''
			),	

			array(
				'name'  		=> __( 'Additional Info', 'themeum-core' ),
				'id'    		=> "{$prefix}vehicleadditional",
				'type'  		=> 'heading',
				'std'   		=> ''
			),		

			array(
				'name'  => esc_html__( 'Passenger', 'themeum-core' ),
				'id'    => "{$prefix}passenger",
				'desc'  => esc_html__( 'Passenger Number', 'themeum-core' ),
				'type'  => 'text',
				'std'   => '2'
			),

			array(
				'name'  		=> __( 'Price', 'themeum-core' ),
				'id'    		=> "{$prefix}vehicleprice",
				'desc'  		=> __( 'Price', 'themeum-core' ),
				'type'  		=> 'text',
				'std'   		=> '$750'
			),	
			array(
				'name'  		=> __( 'Pricing Method', 'themeum-core' ),
				'id'    		=> "{$prefix}vehicleduration",
				'desc'  		=> __( 'Pricing Method (Per hour)', 'themeum-core' ),
				'type'  		=> 'text',
				'std'   		=> '/Per hour'
			),	
			array(
				'name'  		=> __( 'Help Info', 'themeum-core' ),
				'id'    		=> "{$prefix}vehiclehelp",
				'type'  		=> 'heading',
				'std'   		=> ''
			),

			array(
				'name'  		=> __( 'Help Short Information', 'themeum-core' ),
				'id'    		=> "{$prefix}vehiclehelptext",
				'desc'  		=> __( 'Short Information', 'themeum-core' ),
				'type'  		=> 'textarea',
				'std'   		=> ''
			),	

			array(
				'name'  		=> __( 'Contact Number', 'themeum-core' ),
				'id'    		=> "{$prefix}vehiclecontactnum",
				'desc'  		=> __( 'Contact Number', 'themeum-core' ),
				'type'  		=> 'text',
				'std'   		=> ''
			),	
			array(
				'name'  		=> __( 'Contact Email', 'themeum-core' ),
				'id'    		=> "{$prefix}vehiclecontactemail",
				'desc'  		=> __( 'Contact Email', 'themeum-core' ),
				'type'  		=> 'text',
				'std'   		=> ''
			),

			array(
				'name'  		=> __( 'Why Choose Info', 'themeum-core' ),
				'id'    		=> "{$prefix}vehiclechoose",
				'type'  		=> 'heading',
				'std'   		=> ''
			),	

			array(
				'name'          => __( 'Why Choose List', 'themeum-core' ),
				'id'            => "{$prefix}vehiclechooselist",
				'desc'			=> __( 'Add Why Choose List', 'themeum-core' ),
				'type'          => 'text',
				'std'           => '',
				'clone'			=> true
			),	

			array(
				'name'  		=> __( 'Contact Form', 'themeum-core' ),
				'id'    		=> "{$prefix}vehiclehelpcontact",
				'type'  		=> 'heading',
				'std'   		=> ''
			),

			array(
				'name'          => __( 'Contact Short Note', 'themeum-core' ),
				'id'            => "{$prefix}contactshortnote",
				'desc'			=> __( 'Add your Contact Short Note', 'themeum-core' ),
				'type'          => 'textarea',
				'std'           => __( '', 'themeum-core' ),
			),

			array(
				'name'  		=> __( 'Vehicle Schedule', 'themeum-core' ),
				'id'    		=> "{$prefix}vehicleschedule",
				'type'  		=> 'heading',
				'std'   		=> ''
			),


			

			
			// array(
			// 	'name'       => __( 'Schedule List', 'themeum-core' ),
			// 	'id'         => 'themeum_vehicleschedule',
			// 	'type'       => 'post',
			// 	'post_type'  => 'schedule',
			// 	'field_type' => 'select_advanced',
			// 	'query_args' => array(
			// 		'post_status'    => 'publish',
			// 		'posts_per_page' => '-1',
			// 	),
			// 	'multiple'    => true,
			// ),	
	

			array(
				'name'  		=> __( 'Gallery', 'themeum-core' ),
				'id'    		=> "{$prefix}vehiclehelpgallery",
				'type'  		=> 'heading',
				'std'   		=> ''
			),
			array(
				'name'  		=> __( 'Gallery Items', 'themeum-core' ),
				'id'    		=> "{$prefix}vehiclegallery",
				'type'  		=> 'image_advanced',
				'std'   		=> ''
			),			
	));

		
		$meta_boxes[] = array(
			'id' 		=> 'schedules-meta',
			'title' 	=> __( 'Schedules', 'themeum-core' ),
			'pages' 	=> array( 'schedules'),
			'context' 	=> 'normal',
			'priority' 	=> 'high',
			'autosave' 	=> true,
			'fields' 	=> array(
				array(
					'name'  		=> __( 'Pickup Time', 'themeum-core' ),
					'id'    		=> "{$prefix}schedule_pickup_time",
					'type'  		=> 'datetime',
					'std'   		=> ''
				),
				array(
					'name'  		=> __( 'Drop Time', 'themeum-core' ),
					'id'    		=> "{$prefix}schedule_drop_time",
					'type'  		=> 'datetime',
					'std'   		=> ''
				),
				array(
					'name'  		=> __( 'Vehicle', 'themeum-core' ),
					'id'    		=> "{$prefix}schedule_car",
					'type'  		=> 'select_advanced',
					'options'   	=> get_all_posts('vehicle')
				),
				array(
					'name'  		=> __( 'Booked', 'themeum-core' ),
					'id'    		=> "{$prefix}schedule_booked",
					'type'  		=> 'checkbox',
					'std'   		=> 0
				),
			)
		);



	// --------------------------------------------------------------------------------------------------------------	
	// ----------------------------------------- Photo Gallery Open ----------------------------------------------------------	
	// --------------------------------------------------------------------------------------------------------------

		$meta_boxes[] = array(
			'id' 		=> 'gallery-meta',
			'title' 	=> __( 'Gallery Items', 'themeum-core' ),
			'pages' 	=> array( 'gallery'),
			'context' 	=> 'normal',
			'priority' 	=> 'high',
			'autosave' 	=> true,

			'fields' 	=> array(
				array(
					'name'  		=> __( 'Gallery Items', 'themeum-core' ),
					'id'    		=> "{$prefix}travelkit_photo_gallery",
					'type'  		=> 'image_advanced',
					'std'   		=> ''
				),		
			)
		);


		/**
	 * Register Post Meta for Order Post Type
	 *
	 * @return array
	 */
	
	$courses_title = get_all_posts('package');

	$meta_boxes[] = array(
		'id' 		=> 'order-post-meta',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' 	=> __( 'Order Item Settings', 'themeum-lms' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' 	=> array( 'thmorder'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' 	=> 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' 	=> 'high',

		// Auto save: true, false (default). Optional.
		'autosave' 	=> true,

		// List of meta fields
		'fields' 	=> array(

			array(
				'name'  		=> __( 'Package', 'themeum-lms' ),
				'id'    		=> "{$prefix}order_course_id",
				'desc'  		=> '',
				'type'     		=> 'select_advanced',
				'options'  		=> $courses_title,
				'multiple'    	=> false,
				'placeholder' 	=> __( 'Select Course', 'themeum-lms' ),
			),


			array(
				'name'          => __( 'Order ID', 'themeum-lms' ),
				'id'            => "{$prefix}order_id",
				'desc'			=> __( 'Order Course ID Ex. 3', 'themeum-lms' ),
				'type'          => 'number',
				'std'           => ''
			),	


			array(
				'name'          => __( 'User ID', 'themeum-lms' ),
				'id'            => "{$prefix}order_user_id",
				'desc'			=> __( 'Order User ID Ex. 3', 'themeum-lms' ),
				'type'          => 'number',
				'std'           => ''
			),	
	
			


			array(
				'name'          => __( 'Order Price', 'themeum-lms' ),
				'id'            => "{$prefix}order_price",
				'desc'			=> __( 'Order Price', 'themeum-lms' ),
				'type'          => 'number',
				'std'           => ''
			),	

			array(
				'name'          => __( 'Payment ID', 'themeum-lms' ),
				'id'            => "{$prefix}payment_id",
				'desc'			=> __( 'Payment ID Ex. 3', 'themeum-lms' ),
				'type'          => 'number',
				'std'           => ''
			),	

			array(
				'name'          => __( 'Payment Method', 'themeum-lms' ),
				'id'            => "{$prefix}payment_method",
				'desc'			=> __( 'Add Payment Method', 'themeum-lms' ),
				'type'          => 'text',
				'std'           => ''
			),		

			array(
				'name'          => __( 'Order Created', 'themeum-lms' ),
				'id'            => "{$prefix}order_created",
				'desc'			=> __( 'Order created', 'themeum-lms' ),
				'type'          => 'datetime',
				'std'           => ''
			),

			array(
				'name'  		=> __( 'Comments', 'themeum-lms' ),
				'id'    		=> "{$prefix}order_comments",
				'desc'  		=> __( 'Add Your Order Comments Here', 'themeum-lms' ),
				'type'  		=> 'textarea',
				'std'   		=> ''
			),

			array(
				'name'          => __( 'Order Status', 'themeum-lms' ),
				'id'            => "{$prefix}status_all",
				'desc'			=> __( 'Select Order Status', 'themeum-lms' ),
				'type'          => 'select',
				'std'           => 'pending',
				'options' 		=> array(
						            'pending '   => 'Pending',
						            'complete'   => 'Complete',
						            'refund'     => 'Refund',
						       		 )
			),	
					
		)
	);

	$courses_title = get_all_posts('vehicle');

	$meta_boxes[] = array(
		'id' 		=> 'order-post-meta',

		// Meta box title - Will appear at the drag and drop handle bar. Required.
		'title' 	=> __( 'Order Item Settings', 'themeum-lms' ),

		// Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
		'pages' 	=> array( 'thm_vcl_order'),

		// Where the meta box appear: normal (default), advanced, side. Optional.
		'context' 	=> 'normal',

		// Order of meta box: high (default), low. Optional.
		'priority' 	=> 'high',

		// Auto save: true, false (default). Optional.
		'autosave' 	=> true,

		// List of meta fields
		'fields' 	=> array(

			array(
				'name'  		=> __( 'Vehicle', 'themeum-lms' ),
				'id'    		=> "{$prefix}order_course_id",
				'desc'  		=> '',
				'type'     		=> 'select_advanced',
				'options'  		=> $courses_title,
				'multiple'    	=> false,
				'placeholder' 	=> __( 'Select Course', 'themeum-lms' ),
			),


			array(
				'name'          => __( 'Order ID', 'themeum-lms' ),
				'id'            => "{$prefix}order_id",
				'desc'			=> __( 'Order Course ID Ex. 3', 'themeum-lms' ),
				'type'          => 'number',
				'std'           => ''
			),	


			array(
				'name'          => __( 'User ID', 'themeum-lms' ),
				'id'            => "{$prefix}order_user_id",
				'desc'			=> __( 'Order User ID Ex. 3', 'themeum-lms' ),
				'type'          => 'number',
				'std'           => ''
			),	
	
			


			array(
				'name'          => __( 'Order Price', 'themeum-lms' ),
				'id'            => "{$prefix}order_price",
				'desc'			=> __( 'Order Price', 'themeum-lms' ),
				'type'          => 'number',
				'std'           => ''
			),	

			array(
				'name'          => __( 'Payment ID', 'themeum-lms' ),
				'id'            => "{$prefix}payment_id",
				'desc'			=> __( 'Payment ID Ex. 3', 'themeum-lms' ),
				'type'          => 'number',
				'std'           => ''
			),	

			array(
				'name'          => __( 'Payment Method', 'themeum-lms' ),
				'id'            => "{$prefix}payment_method",
				'desc'			=> __( 'Add Payment Method', 'themeum-lms' ),
				'type'          => 'text',
				'std'           => ''
			),		

			array(
				'name'          => __( 'Order Created', 'themeum-lms' ),
				'id'            => "{$prefix}order_created",
				'desc'			=> __( 'Order created', 'themeum-lms' ),
				'type'          => 'datetime',
				'std'           => ''
			),

			array(
				'name'  		=> __( 'Comments', 'themeum-lms' ),
				'id'    		=> "{$prefix}order_comments",
				'desc'  		=> __( 'Add Your Order Comments Here', 'themeum-lms' ),
				'type'  		=> 'textarea',
				'std'   		=> ''
			),

			array(
				'name'          => __( 'Order Status', 'themeum-lms' ),
				'id'            => "{$prefix}status_all",
				'desc'			=> __( 'Select Order Status', 'themeum-lms' ),
				'type'          => 'select',
				'std'           => 'pending',
				'options' 		=> array(
						            'pending '   => 'Pending',
						            'complete'   => 'Complete',
						            'refund'     => 'Refund',
						       		 )
			),	
					
		)
	);



	return $meta_boxes;
}


/**
 * Get list of post from any post type
 *
 * @return array
 */

function get_all_posts($post_type)
{
	$args = array(
			'post_type' => $post_type,  // post type name
			'posts_per_page' => -1,   //-1 for all post
		);

	$posts = get_posts($args);

	$post_list = array();

	if (!empty( $posts ))
	{
		foreach ($posts as $post)
		{
			setup_postdata($post);
			$post_list[$post->ID] = $post->post_title;
		}
		wp_reset_postdata();
		return $post_list;
	}
	else
	{
		return $post_list;
	}	
}



