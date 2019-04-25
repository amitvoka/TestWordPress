<?php

/**
 * Themeum Customizer
 */


if (!class_exists('THMC_Framework')):

	class THMC_Framework
	{
		/**
		 * Instance of WP_Customize_Manager class
		 */
		public $wp_customize;


		private $fields_class = array();

		private $google_fonts = array();

		/**
		 * Constructor of 'THMC_Framework' class
		 *
		 * @wp_customize (WP_Customize_Manager) Instance of 'WP_Customize_Manager' class
		 */
		function __construct( $wp_customize )
		{
			$this->wp_customize = $wp_customize;

			$this->fields_class = array(
				'text'            => 'WP_Customize_Control',
				'checkbox'        => 'WP_Customize_Control',
				'textarea'        => 'WP_Customize_Control',
				'radio'           => 'WP_Customize_Control',
				'select'          => 'WP_Customize_Control',
				'email'           => 'WP_Customize_Control',
				'url'             => 'WP_Customize_Control',
				'number'          => 'WP_Customize_Control',
				'range'           => 'WP_Customize_Control',
				'hidden'          => 'WP_Customize_Control',
				'date'            => 'THMC_Date_Control',
				'color'           => 'WP_Customize_Color_Control',
				'upload'          => 'WP_Customize_Upload_Control',
				'image'           => 'WP_Customize_Image_Control',
				'radio_button'    => 'THMC_Radio_Button_Control',
				'checkbox_button' => 'THMC_Checkbox_Button_Control',
				'switch'          => 'THMC_Switch_Button_Control',
				'multi_select'    => 'THMC_Multi_Select_Control',
				'radio_image'     => 'THMC_Radio_Image_Control',
				'checkbox_image'  => 'THMC_Checkbox_Image_Control',
				'color_palette'   => 'THMC_Color_Palette_Control',
				'rgba'            => 'THMC_Rgba_Color_Picker_Control',
				'title'           => 'THMC_Switch_Title_Control',
			);

			$this->load_custom_controls();

			add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_scripts' ), 100 );
		}

		public function customizer_scripts()
		{
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'thmc-select2', plugin_dir_url( __FILE__ ).'assets/select2/css/select2.min.css' );
			wp_enqueue_style( 'thmc-customizer', plugin_dir_url( __FILE__ ).'assets/css/customizer.css' );

			// Load all js
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'thmc-select2', plugin_dir_url( __FILE__ ).'assets/select2/js/select2.min.js', array('jquery'), '1.0', true );
			wp_enqueue_script( 'thmc-rgba-colorpicker', plugin_dir_url( __FILE__ ).'assets/js/thmc-rgba-colorpicker.js', array('jquery', 'wp-color-picker'), '1.0', true );
			wp_enqueue_script( 'thmc-customizer', plugin_dir_url( __FILE__ ).'assets/js/customizer.js', array('jquery', 'jquery-ui-datepicker'), '1.0', true );
			wp_localize_script( 'thmc-customizer', 'thm_customizer', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'import_success' => esc_html__('Success! Your theme data successfully imported. Page will be reloaded within 2 sec.', 'themeum-core'),
				'import_error' => esc_html__('Error! Your theme data importing failed.', 'themeum-core'),
				'file_error' => esc_html__('Error! Please upload a file.', 'themeum-core')
			) );
		}

		private function load_custom_controls()
		{
			include_once( 'controls/radio-button.php' );
			include_once( 'controls/radio-image.php' );
			include_once( 'controls/checkbox-button.php' );
			include_once( 'controls/checkbox-image.php' );
			include_once( 'controls/switch.php' );
			include_once( 'controls/date.php' );
			include_once( 'controls/multi-select.php' );
			include_once( 'controls/color-palette.php' );
			include_once( 'controls/rgba-colorpicker.php' );
			include_once( 'controls/title.php' );

			// Load Sanitize class
			include_once( 'libs/sanitize.php' );
		}

		public function add_option( $options )
		{
			if (isset($options['sections'])) {
				$this->panel_to_section($options);
			} elseif (isset($options['options'])){

			}



		}



		private function panel_to_section( $options )
		{
			$panel = $options;
			$panel_id = $options['id'];

			unset($panel['sections']);
			unset($panel['id']);

			// Register this panel
			$this->add_panel($panel, $panel_id);

			$sections = $options['sections'];

			if (!empty($sections)) {
				foreach ($sections as $section) {
					$fields = $section['fields'];
					$section_id = $section['id'];

					unset($section['fields']);
					unset($section['id']);

					$section['panel'] = $panel_id;

					$this->add_section($section, $section_id);

					if (!empty($fields)) {
						foreach ($fields as $field) {
							$field_id = $field['settings'];

							$this->add_field($field, $field_id, $section_id);
						}
					}
				}
			}
		}

		private function add_panel($panel, $panel_id){
			$this->wp_customize->add_panel( $panel_id, $panel );
		}

		private function add_section($section, $section_id)
		{
			$this->wp_customize->add_section( $section_id, $section );
		}

		private function add_field($field, $field_id, $section_id){



			$setting_args = array(
				'default'        => isset($field['default']) ? $field['default'] : '',
				'type'           => isset($field['setting_type']) ? $field['setting_type'] : 'theme_mod',
				'transport'     => isset($field['transport']) ? $field['transport'] : 'refresh',
				'capability'     => isset($field['capability']) ? $field['capability'] : 'edit_theme_options',
			);

			if (isset($field['type']) && $field['type'] == 'switch') {
				$setting_args['sanitize_callback'] = array('THMC_Sanitize', 'switch_sntz');
			} elseif (isset($field['type']) && ($field['type'] == 'checkbox_button' || $field['type'] == 'checkbox_image')) {
				$setting_args['sanitize_callback'] = array('THMC_Sanitize', 'multi_checkbox');
			} elseif (isset($field['type']) && $field['type'] == 'multi_select') {
				$setting_args['sanitize_callback'] = array('THMC_Sanitize', 'multi_select');
				$setting_args['sanitize_js_callback'] = array('THMC_Sanitize', 'multi_select_js');
			}

			$control_args = array(
				'label'       => isset($field['label']) ? $field['label'] : '',
				'section'     => $section_id,
				'settings'    => $field_id,
				'type'        => isset($field['type']) ? $field['type'] : 'text',
				'priority'    => isset($field['priority']) ? $field['priority'] : 10,
			);

			if (isset($field['choices'])) {
				$control_args['choices'] = $field['choices'];
			}

			// Register the settings
			$this->wp_customize->add_setting( $field_id, $setting_args );

			$control_class = isset($this->fields_class[$field['type']]) ? $this->fields_class[$field['type']] : 'WP_Customize_Control';

			// Add the controls
			$this->wp_customize->add_control( new $control_class( $this->wp_customize, $field_id, $control_args ) );
		}
	}

endif;

/**
*
*/
class THM_Customize
{
	public $google_fonts = array();

	function __construct( $options )
	{
		$this->options = $options;

		add_action('customize_register', array($this, 'customize_register'));
		add_action('wp_enqueue_scripts', array($this, 'get_google_fonts_data'));

		add_action('wp_ajax_thm_export_data', array($this, 'export_data_cb'));
		add_action('wp_ajax_thm_import_data', array($this, 'import_data_cb'));
	}

	public function customize_register( $wp_customize )
	{
		$framework = new THMC_Framework( $wp_customize );

		$framework->add_option( $this->options );

		$this->import_export_ui( $wp_customize );
	}

	public function import_export_ui( $wp_customize )
	{
		include_once( 'controls/export.php' );
		include_once( 'controls/import.php' );

		$wp_customize->add_section( 'thm_import_export', array(
			'title'           => esc_html__( 'Import/Export', 'themeum-core' ),
			'description'     => esc_html__( 'Import Export Option Data', 'themeum-core' ),
			'priority'        => 1000,
		) );

		$wp_customize->add_setting( 'thm_export', array(
			'default'        => '',
			'transport'      => 'postMessage',
			'capability'     => 'edit_theme_options',
		) );

		$wp_customize->add_control( new THMC_Export_Control( $wp_customize, 'thm_export_ctrl', array(
			'label'       => 'Export Theme Data',
			'section'     => 'thm_import_export',
			'settings'    => 'thm_export',
			'type'        => 'export',
			'priority'    => 10,
		) ) );

		$wp_customize->add_setting( 'thm_import', array(
			'default'        => '',
			'transport'      => 'postMessage',
			'capability'     => 'edit_theme_options',
		) );

		$wp_customize->add_control( new THMC_Import_Control( $wp_customize, 'thm_import_ctrl', array(
			'label'       => 'Import Theme Data',
			'section'     => 'thm_import_export',
			'settings'    => 'thm_import',
			'type'        => 'export',
			'priority'    => 10,
		) ) );
	}

	public function export_data_cb()
	{
		$theme_slug = get_option( 'stylesheet' );
		$mods = get_option( "theme_mods_$theme_slug" );

		header( "Content-Description: File Transfer" );
		header( "Content-Disposition: attachment; filename=theme_data.json" );
		header( "Content-Type: application/octet-stream" );
		echo json_encode($mods);
		exit;
	}

	public function import_data_cb()
	{
		$theme_data = file_get_contents($_FILES['file']['tmp_name']);

		if (empty($theme_data)) {
			echo 0;
			exit();
		}

		$theme_data = json_decode($theme_data, true);

		if (empty($theme_data)) {
			echo 0;
			exit();
		}

		unset($theme_data['nav_menu_locations']);

		$theme_slug = get_option( 'stylesheet' );
		$mods = get_option( "theme_mods_$theme_slug" );

		if ($mods  === false) {
			$status = add_option( "theme_mods_$theme_slug", $theme_data );
			if ($status) {
				echo 1;
			} else {
				echo 0;
			}
		} else {
			$theme_data['nav_menu_locations'] = $mods['nav_menu_locations'];
			$status = update_option( "theme_mods_$theme_slug", $theme_data );

			if ($status) {
				echo 1;
			} else {
				echo 0;
			}
		}

		exit();
	}

	public function get_google_fonts_data()
	{
		if (isset($this->options['sections']) && !empty($this->options['sections'])) {
			foreach ($this->options['sections'] as $section) {
				if (isset($section['fields']) && !empty($section['fields'])) {
					foreach ($section['fields'] as $field) {
						if (isset($field['google_font']) && $field['google_font'] == true) {
							$this->google_fonts[$field['settings']] = array();

							if (isset($field['default']) && !empty($field['default'])) {
								$this->google_fonts[$field['settings']]["default"] = $field['default'];
							}

							if (isset($field['google_font_weight']) && !empty($field['google_font_weight'])) {
								$this->google_fonts[$field['settings']]["weight"] = $field['google_font_weight'];
							}

							if (isset($field['google_font_weight_default']) && !empty($field['google_font_weight_default'])) {
								$this->google_fonts[$field['settings']]["weight_default"] = $field['google_font_weight_default'];
							}
						}
					}
				}
			}
		}

		$all_fonts = array();

		if (!empty($this->google_fonts)) {
			foreach ($this->google_fonts as $font_id => $font_data) {
				$font_family_default = isset($font_data['default']) ? $font_data['default'] : '';
				$font_family = get_theme_mod( $font_id, $font_family_default );

				if (!isset($all_fonts[$font_family])) {
					$all_fonts[$font_family] = array();
				}

				if (isset($font_data['weight']) && !empty($font_data['weight'])) {
					$font_weight_default = isset($font_data['weight_default']) ? $font_data['weight_default'] : '';

					$font_weight = get_theme_mod( $font_data['weight'], $font_weight_default );

					$all_fonts[$font_family][] = $font_weight;
				}

			}
		}

		$font_url = "//fonts.googleapis.com/css?family=";

		if (!empty($all_fonts)) {

			$i = 0;

			foreach ($all_fonts as $font => $weights) {

				if ($i) {
					$font_url .= "|";
				}

				$font_url .= str_replace(" ", "+", $font);

				if (!empty($weights)) {
					$font_url .= ":";
					$font_url .= implode(",", $weights);
				}

				$i++;
			}

			wp_enqueue_style( "tm-google-font", $font_url );
		}
	}
}




/*
 *
 * Current Fields: text, checkbox, textarea, radio, select, email, url, number, hidden, color, upload, image, Range
 *
 * Required Fields: [Done] RGBA Color Picker, [Done] Palette Color, [Done] Button Set (Radio & Checkbox), [Done] Switch, [Done] Multi-Select, [Done] Image Select, [Done] Date Picker
 */

/**
 * Example Data
 *
 * These data will be removed later
 */



$panel_to_section = array(
	'id'           => 'travelkit_panel_options',
	'title'        => esc_html( 'Travelkit Options', 'themeum-core' ),
	'description'  => esc_html__( 'Travelkit Theme Options', 'themeum-core' ),
	'priority'     => 10,
	'sections'     => array(

		array(
			'id'              => 'topbar_setting',
			'title'           => esc_html__( 'Topbar Settings', 'themeum-core' ),
			'description'     => esc_html__( 'Topbar Settings', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'topbar_en',
					'label'    => esc_html__( 'Topbar Enable/Disable', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => false,
				),
				array(
					'settings' => 'topbar_email',
					'label'    => esc_html__( 'Topbar Email', 'themeum-core' ),
					'type'     => 'email',
					'priority' => 10,
					'default'  => 'support@themeum.com',
				),
				array(
					'settings' => 'topbar_phone',
					'label'    => esc_html__( 'Topbar Phone Number', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '+00 44 123 456 78910',
				),
				array(
					'settings' => 'topbar_social',
					'label'    => esc_html__( 'Topbar With Social', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => false,
				),
				array(
					'settings' => 'topbar_color',
					'label'    => esc_html__( 'Topbar background color', 'themeum-core' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => 'rgba(0,0,0,0.3)',
				),
				array(
					'settings' => 'topbar_text_color',
					'label'    => esc_html__( 'Topbar text color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'topbar_border_color',
					'label'    => esc_html__( 'Topber border bottom color', 'themeum-core' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => 'rgba(255, 255, 255, 0.3)',
				),
			)//fields
		),//topbar_setting

		array(
			'id'              => 'header_setting',
			'title'           => esc_html__( 'Header Settings', 'themeum-core' ),
			'description'     => esc_html__( 'Header Settings', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'head_style',
					'label'    => esc_html__( 'Select Header Style', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'solid',
					'choices'  => array(
						'transparent' => esc_html( 'Transparent Header', 'themeum-core' ),
						'solid' => esc_html( 'Solid Header', 'themeum-core' ),
						'borderimage' => esc_html( 'Border Header', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'header_color',
					'label'    => esc_html__( 'Header background Color', 'themeum-core' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'header_border_color',
					'label'    => esc_html__( 'Header border bottom color', 'themeum-core' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => 'rgba(255, 255, 255, 0.3)',
				),
				array(
					'settings' => 'header_padding_top',
					'label'    => esc_html__( 'Header Top Padding', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 0,
				),
				array(
					'settings' => 'header_padding_bottom',
					'label'    => esc_html__( 'Header Bottom Padding', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 0,
				),
				array(
					'settings' => 'header_fixed',
					'label'    => esc_html__( 'Sticky Header', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => false,
				),
				array(
					'settings' => 'sticky_header_color',
					'label'    => esc_html__( 'Sticky background Color', 'themeum-core' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'sticky_header_text_color',
					'label'    => esc_html__( 'Sticky Header Text Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),

			)//fields
		),//header_setting

		array(
			'id'              => 'logo_setting',
			'title'           => esc_html__( 'All Logo & favicon', 'themeum-core' ),
			'description'     => esc_html__( 'All Logo & favicon', 'themeum-core' ),
			'priority'        => 10,
			'fields'         => array(

				array(
					'settings' => 'favicon',
					'label'    => esc_html__( 'Upload Favicon Icon', 'themeum-core' ),
					'type'     => 'upload',
					'priority' => 10,
					'default' => get_template_directory_uri().'/images/logo.png',
				),
				array(
					'settings' => 'logo_style',
					'label'    => esc_html__( 'Select Header Style', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'logotext',
					'choices'  => array(
						'logoimg' => esc_html( 'Logo image', 'themeum-core' ),
						'logotext' => esc_html( 'Logo text', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'logo',
					'label'    => esc_html__( 'Upload Logo', 'themeum-core' ),
					'type'     => 'upload',
					'priority' => 10,
					'default' => get_template_directory_uri().'/images/logo.png',
				),
				array(
					'settings' => 'logo_width',
					'label'    => esc_html__( 'Logo Width', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
				),
				array(
					'settings' => 'logo_height',
					'label'    => esc_html__( 'Logo Height', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
				),
				array(
					'settings' => 'logo_text',
					'label'    => esc_html__( 'Use your Custom logo text', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => 'Travelkit',
				),
				

			)//fields
		),//logo_setting
		
		array(
			'id'              => 'sub_header_banner',
			'title'           => esc_html__( 'Sub Header Banner', 'themeum-core' ),
			'description'     => esc_html__( 'sub header banner', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'sub_header_padding_top',
					'label'    => esc_html__( 'Sub-Header Padding Top', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 142,
				),
				array(
					'settings' => 'sub_header_padding_bottom',
					'label'    => esc_html__( 'Sub-Header Padding Bottom', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 92,
				),
				array(
					'settings' => 'sub_header_margin_bottom',
					'label'    => esc_html__( 'Sub-Header Margin Bottom', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => 100,
				),
				array(
					'settings' => 'sub_header_banner_img',
					'label'    => esc_html__( 'Sub-Header Background Image', 'themeum-core' ),
					'type'     => 'image',
					'priority' => 10,
				),
				array(
					'settings' => 'sub_header_banner_color',
					'label'    => esc_html__( 'Sub-Header Background Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#333',
				),
				array(
					'settings' => 'sub_header_overlayer_color',
					'label'    => esc_html__( 'Sub-Header Overlayer Color', 'themeum-core' ),
					'type'     => 'rgba',
					'priority' => 10,
					'default'  => 'rgba(0, 0, 0, 0.5)',
				),
				array(
					'settings' => 'sub_header_title',
					'label'    => esc_html__( 'Title Settings', 'themeum-core' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'sub_header_title_enable',
					'label'    => esc_html__( 'Header Title Enable', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'sub_header_title_size',
					'label'    => esc_html__( 'Header Title Font Size', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => '42',
				),
				array(
					'settings' => 'sub_header_title_color',
					'label'    => esc_html__( 'Header Title Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),
				array(
					'settings' => 'breadcrumb_title',
					'label'    => esc_html__( 'Breadcrumb Settings', 'themeum-core' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'breadcrumb_enable',
					'label'    => esc_html__( 'Breadcrumb Enable', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'breadcrumb_text_color',
					'label'    => esc_html__( 'Breadcrumb Text Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),
				array(
					'settings' => 'breadcrumb_link_color',
					'label'    => esc_html__( 'Breadcrumb Link Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),
				array(
					'settings' => 'breadcrumb_link_color_hvr',
					'label'    => esc_html__( 'Breadcrumb Link Hover Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),
			)//fields
		),//sub_header_banner

		array(
			'id'              => 'currency_settings',
			'title'           => esc_html__( 'Currency Setting', 'themeum-core' ),
			'description'     => esc_html__( 'Currency Setting', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'currency_right',
					'label'    => esc_html__( 'Show Currency at Right', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => false,
				),
				array(
					'settings' => 'package_currency',
					'label'    => esc_html__( 'Package Currency', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '$',
				),
				array(
					'settings' => 'other_currency',
					'label'    => esc_html__( 'Other Currency', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '$',
				),
			)
		),

		array(
			'id'              => 'flight_settings',
			'title'           => esc_html__( 'Flight Setting', 'themeum-core' ),
			'description'     => esc_html__( 'Flight Setting', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'tp_api_key_title',
					'label'    => esc_html__( 'Travelpayout Settings', 'themeum-core' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'tp_api',
					'label'    => esc_html__( 'Travelpayout API', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => false,
				),
				array(
					'settings' => 'tp_api_key',
					'label'    => esc_html__( 'Travelpayout API Key', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'tp_api_marker',
					'label'    => esc_html__( 'Travelpayout API Marker', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'skyscanner_api_key_title',
					'label'    => esc_html__( 'Skyscanner Settings (Deprecated)', 'themeum-core' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'flight_api',
					'label'    => esc_html__( 'Flight API', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'flight_market',
					'label'    => esc_html__( 'Flight Market', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'US',
					'choices'  => array(
						'' => esc_html( 'Select', 'themeum-core' ),
						"AD" => "Andorra",
						"AE" => "United Arab Emirates",
						"AF" => "Afghanistan",
						"AG" => "Antigua and Barbuda",
						"AI" => "Anguilla",
						"AL" => "Albania",
						"AM" => "Armenia",
						"AN" => "Netherlands Antilles",
						"AO" => "Angola",
						"AQ" => "Antarctica",
						"AR" => "Argentina",
						"AS" => "American Samoa",
						"AT" => "Austria",
						"AU" => "Australia",
						"AW" => "Aruba",
						"AZ" => "Azerbaijan",
						"BA" => "Bosnia and Herzegovina",
						"BB" => "Barbados",
						"BD" => "Bangladesh",
						"BE" => "Belgium",
						"BF" => "Burkina Faso",
						"BG" => "Bulgaria",
						"BH" => "Bahrain",
						"BI" => "Burundi",
						"BJ" => "Benin",
						"BL" => "Saint Barthelemy",
						"BM" => "Bermuda",
						"BN" => "Brunei",
						"BO" => "Bolivia",
						"BQ" => "Caribbean Netherlands",
						"BR" => "Brazil",
						"BS" => "Bahamas",
						"BT" => "Bhutan",
						"BW" => "Botswana",
						"BY" => "Belarus",
						"BZ" => "Belize",
						"CA" => "Canada",
						"CC" => "Cocos (Keeling) Islands",
						"CD" => "DR Congo",
						"CF" => "Central African Republic",
						"CG" => "Congo",
						"CH" => "Switzerland",
						"CI" => "Ivory Coast",
						"CK" => "Cook Islands",
						"CL" => "Chile",
						"CM" => "Cameroon",
						"CN" => "China",
						"CO" => "Colombia",
						"CR" => "Costa Rica",
						"CU" => "Cuba",
						"CV" => "Cape Verde",
						"CW" => "Curacao",
						"CX" => "Christmas Island",
						"CY" => "Cyprus",
						"CZ" => "Czech Republic",
						"DE" => "Germany",
						"DJ" => "Djibouti",
						"DK" => "Denmark",
						"DM" => "Dominica",
						"DO" => "Dominican Republic",
						"DZ" => "Algeria",
						"EC" => "Ecuador",
						"EE" => "Estonia",
						"EG" => "Egypt",
						"ER" => "Eritrea",
						"ES" => "Spain",
						"ET" => "Ethiopia",
						"FI" => "Finland",
						"FJ" => "Fiji",
						"FK" => "Falkland Islands",
						"FM" => "Micronesia",
						"FO" => "Faroe Islands",
						"FR" => "France",
						"GA" => "Gabon",
						"GD" => "Grenada",
						"GE" => "Georgia",
						"GF" => "French Guiana",
						"GG" => "Guernsey",
						"GH" => "Ghana",
						"GI" => "Gibraltar",
						"GL" => "Greenland",
						"GM" => "Gambia",
						"GN" => "Guinea",
						"GP" => "Guadeloupe",
						"GQ" => "Equatorial Guinea",
						"GR" => "Greece",
						"GS" => "South Georgia & South Sandwich Islands",
						"GT" => "Guatemala",
						"GU" => "Guam",
						"GW" => "Guinea-Bissau",
						"GY" => "Guyana",
						"HK" => "Hong Kong",
						"HN" => "Honduras",
						"HR" => "Croatia",
						"HT" => "Haiti",
						"HU" => "Hungary",
						"ID" => "Indonesia",
						"IE" => "Ireland",
						"IL" => "Israel",
						"IN" => "India",
						"IQ" => "Iraq",
						"IR" => "Iran",
						"IS" => "Iceland",
						"IT" => "Italy",
						"JM" => "Jamaica",
						"JO" => "Jordan",
						"JP" => "Japan",
						"KE" => "Kenya",
						"KG" => "Kyrgyzstan",
						"KH" => "Cambodia",
						"KI" => "Kiribati",
						"KM" => "Comoros",
						"KN" => "Saint Kitts and Nevis",
						"KO" => "Kosovo",
						"KP" => "North Korea",
						"KR" => "South Korea",
						"KW" => "Kuwait",
						"KY" => "Cayman Islands",
						"KZ" => "Kazakhstan",
						"LA" => "Laos",
						"LB" => "Lebanon",
						"LC" => "Saint Lucia",
						"LI" => "Liechtenstein",
						"LK" => "Sri Lanka",
						"LR" => "Liberia",
						"LS" => "Lesotho",
						"LT" => "Lithuania",
						"LU" => "Luxembourg",
						"LV" => "Latvia",
						"LY" => "Libya",
						"MA" => "Morocco",
						"MC" => "Monaco",
						"MD" => "Moldova",
						"ME" => "Montenegro",
						"MG" => "Madagascar",
						"MH" => "Marshall Islands",
						"MK" => "Republic of Macedonia",
						"ML" => "Mali",
						"MM" => "Myanmar",
						"MN" => "Mongolia",
						"MO" => "Macau",
						"MP" => "Northern Mariana Islands",
						"MQ" => "Martinique",
						"MR" => "Mauritania",
						"MS" => "Poppins",
						"MT" => "Malta",
						"MU" => "Mauritius",
						"MV" => "Maldives",
						"MW" => "Malawi",
						"MX" => "Mexico",
						"MY" => "Malaysia",
						"MZ" => "Mozambique",
						"NA" => "Namibia",
						"NC" => "New Caledonia",
						"NE" => "Niger",
						"NG" => "Nigeria",
						"NI" => "Nicaragua",
						"NL" => "Netherlands",
						"NO" => "Norway",
						"NP" => "Nepal",
						"NR" => "Nauru",
						"NU" => "Niue",
						"NZ" => "New Zealand",
						"OM" => "Oman",
						"PA" => "Panama",
						"PE" => "Peru",
						"PF" => "French Polynesia",
						"PG" => "Papua New Guinea",
						"PH" => "Philippines",
						"PK" => "Pakistan",
						"PL" => "Poland",
						"PM" => "St. Pierre and Miquelon",
						"PR" => "Puerto Rico",
						"PT" => "Portugal",
						"PW" => "Palau",
						"PY" => "Paraguay",
						"QA" => "Qatar",
						"RE" => "Reunion",
						"RO" => "Romania",
						"RS" => "Serbia",
						"RU" => "Russia",
						"RW" => "Rwanda",
						"SA" => "Saudi Arabia",
						"SB" => "Solomon Islands",
						"SC" => "Seychelles",
						"SD" => "Sudan",
						"SE" => "Sweden",
						"SG" => "Singapore",
						"SI" => "Slovenia",
						"SK" => "Slovakia",
						"SL" => "Sierra Leone",
						"SN" => "Senegal",
						"SO" => "Somalia",
						"SR" => "Suriname",
						"SS" => "South Sudan",
						"ST" => "Sao Tome and Principe",
						"SV" => "El Salvador",
						"SX" => "St Maarten",
						"SY" => "Syria",
						"SZ" => "Swaziland",
						"TC" => "Turks and Caicos Islands",
						"TD" => "Chad",
						"TG" => "Togo",
						"TH" => "Thailand",
						"TJ" => "Tajikistan",
						"TL" => "East Timor",
						"TM" => "Turkmenistan",
						"TN" => "Tunisia",
						"TO" => "Tonga",
						"TR" => "Turkey",
						"TT" => "Trinidad and Tobago",
						"TV" => "Tuvalu",
						"TW" => "Taiwan",
						"TZ" => "Tanzania",
						"UA" => "Ukraine",
						"UG" => "Uganda",
						"UK" => "United Kingdom",
						"US" => "United States",
						"UY" => "Uruguay",
						"UZ" => "Uzbekistan",
						"VA" => "Vatican City",
						"VC" => "Saint Vincent and the Grenadines",
						"VE" => "Venezuela",
						"VG" => "British Virgin Islands",
						"VI" => "US Virgin Islands",
						"VN" => "Vietnam",
						"VU" => "Vanuatu",
						"WF" => "Wallis and Futuna Islands",
						"WS" => "Samoa",
						"YE" => "Yemen",
						"YT" => "Mayotte",
						"ZA" => "South Africa",
						"ZM" => "Zambia",
						"ZW" => "Zimbabwe",
					)
				),
				array(
					'settings' => 'flight_currency',
					'label'    => esc_html__( 'Flight Currency', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'USD',
					'choices'  => array(
						'' => esc_html( 'Select', 'themeum-core' ),
						'AED' => 'AED - د.إ.‏',
						'AFN' => 'AFN - AFN',
						'ALL' => 'ALL - Lek',
						'AMD' => 'AMD - դր.',
						'ANG' => 'ANG - NAf.',
						'AOA' => 'AOA - Kz',
						'ARS' => 'ARS - $',
						'AUD' => 'AUD - $',
						'AWG' => 'AWG - Afl.',
						'AZN' => 'AZN - ₼',
						'BAM' => 'BAM - КМ',
						'BBD' => 'BBD - $',
						'BDT' => 'BDT - BDT',
						'BGN' => 'BGN - лв.',
						'BHD' => 'BHD - د.ب.‏',
						'BIF' => 'BIF - FBu',
						'BMD' => 'BMD - $',
						'BND' => 'BND - $',
						'BOB' => 'BOB - Bs',
						'BRL' => 'BRL - R$',
						'BSD' => 'BSD - $',
						'BTN' => 'BTN - Nu.',
						'BWP' => 'BWP - P',
						'BYR' => 'BYR - р.',
						'BZD' => 'BZD - BZ$',
						'CAD' => 'CAD - $',
						'CDF' => 'CDF - FC',
						'CHF' => 'CHF - CHF',
						'CLP' => 'CLP - $',
						'CNY' => 'CNY - ¥',
						'COP' => 'COP - $',
						'CRC' => 'CRC - ₡',
						'CUC' => 'CUC - CUC',
						'CUP' => 'CUP - $MN',
						'CVE' => 'CVE - $',
						'CZK' => 'CZK - Kč',
						'DJF' => 'DJF - Fdj',
						'DKK' => 'DKK - kr.',
						'DOP' => 'DOP - RD$',
						'DZD' => 'DZD - د.ج.‏',
						'EGP' => 'EGP - ج.م.‏',
						'ERN' => 'ERN - Nfk',
						'ETB' => 'ETB - ETB',
						'EUR' => 'EUR - €',
						'FJD' => 'FJD - $',
						'GBP' => 'GBP - £',
						'GEL' => 'GEL - ₾',
						'GHS' => 'GHS - GH¢',
						'GIP' => 'GIP - £',
						'GMD' => 'GMD - D',
						'GNF' => 'GNF - FG',
						'GTQ' => 'GTQ - Q',
						'GYD' => 'GYD - $',
						'HKD' => 'HKD - HK$',
						'HNL' => 'HNL - L.',
						'HRK' => 'HRK - kn',
						'HTG' => 'HTG - G',
						'HUF' => 'HUF - Ft',
						'IDR' => 'IDR - Rp',
						'ILS' => 'ILS - ₪',
						'INR' => 'INR - ₹',
						'IQD' => 'IQD - د.ع.‏',
						'IRR' => 'IRR - ريال',
						'ISK' => 'ISK - kr.',
						'JMD' => 'JMD - J$',
						'JOD' => 'JOD - د.ا.‏',
						'JPY' => 'JPY - ¥',
						'KES' => 'KES - S',
						'KGS' => 'KGS - сом',
						'KHR' => 'KHR - KHR',
						'KMF' => 'KMF - CF',
						'KPW' => 'KPW - ₩',
						'KRW' => 'KRW - ₩',
						'KWD' => 'KWD - د.ك.‏',
						'KYD' => 'KYD - $',
						'KZT' => 'KZT - Т',
						'LAK' => 'LAK - ₭',
						'LBP' => 'LBP - ل.ل.‏',
						'LKR' => 'LKR - Rp',
						'LRD' => 'LRD - $',
						'LSL' => 'LSL - M',
						'LYD' => 'LYD - د.ل.‏',
						'MAD' => 'MAD - د.م.‏',
						'MDL' => 'MDL - lei',
						'MGA' => 'MGA - Ar',
						'MKD' => 'MKD - ден.',
						'MMK' => 'MMK - K',
						'MNT' => 'MNT - ₮',
						'MOP' => 'MOP - MOP$',
						'MRO' => 'MRO - UM',
						'MUR' => 'MUR - Rs',
						'MVR' => 'MVR - MVR',
						'MWK' => 'MWK - MK',
						'MXN' => 'MXN - $',
						'MYR' => 'MYR - RM',
						'MZN' => 'MZN - MT',
						'NAD' => 'NAD - $',
						'NGN' => 'NGN - ₦',
						'NIO' => 'NIO - C$',
						'NOK' => 'NOK - kr',
						'NPR' => 'NPR - रु',
						'NZD' => 'NZD - $',
						'OMR' => 'OMR - ر.ع.‏',
						'PAB' => 'PAB - B/.',
						'PEN' => 'PEN - S/.',
						'PGK' => 'PGK - K',
						'PHP' => 'PHP - P',
						'PKR' => 'PKR - Rs',
						'PLN' => 'PLN - zł',
						'PYG' => 'PYG - Gs',
						'QAR' => 'QAR - ر.ق.‏',
						'RON' => 'RON - lei',
						'RSD' => 'RSD - Дин.',
						'RUB' => 'RUB - p.',
						'RWF' => 'RWF - RWF',
						'SAR' => 'SAR - ر.س.‏',
						'SBD' => 'SBD - $',
						'SCR' => 'SCR - Rs',
						'SDG' => 'SDG - ج.س.‏',
						'SEK' => 'SEK - kr',
						'SGD' => 'SGD - $',
						'SHP' => 'SHP - £',
						'SLL' => 'SLL - Le',
						'SOS' => 'SOS - S',
						'SRD' => 'SRD - $',
						'STD' => 'STD - Db',
						'SYP' => 'SYP - ل.س.‏',
						'SZL' => 'SZL - E',
						'THB' => 'THB - ฿',
						'TJS' => 'TJS - TJS',
						'TMT' => 'TMT - m',
						'TND' => 'TND - د.ت.‏',
						'TOP' => 'TOP - T$',
						'TRY' => 'TRY - TL',
						'TTD' => 'TTD - TT$',
						'TWD' => 'TWD - NT$',
						'TZS' => 'TZS - TSh',
						'UAH' => 'UAH - грн.',
						'UGX' => 'UGX - USh',
						'USD' => 'USD - $',
						'UYU' => 'UYU - $U',
						'UZS' => 'UZS - сўм',
						'VEF' => 'VEF - Bs. F.',
						'VND' => 'VND - ₫',
						'VUV' => 'VUV - VT',
						'WST' => 'WST - WS$',
						'XAF' => 'XAF - F',
						'XCD' => 'XCD - $',
						'XOF' => 'XOF - F',
						'XPF' => 'XPF - F',
						'YER' => 'YER - ر.ي.‏',
						'ZAR' => 'ZAR - R',
						'ZMW' => 'ZMW - ZK',
					)
				),

			)
		),

		array(
			'id'              => 'typo_setting',
			'title'           => esc_html__( 'Typography Setting', 'themeum-core' ),
			'description'     => esc_html__( 'Typography Setting', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(

				//body font
				array(
					'settings' => 'body_google_font',
					'label'    => esc_html__( 'Select Google Font', 'themeum-core' ),
					'type'     => 'select',
					'default'  => 'Poppins',
					'choices'  => get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'body_font_weight',
					'google_font_weight_default' => '400'
				),
				array(
					'settings' => 'body_font_size',
					'label'    => esc_html__( 'Body Font Size', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '14',
				),
				array(
					'settings' => 'body_font_height',
					'label'    => esc_html__( 'Body Font Line Height', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '24',
				),
				array(
					'settings' => 'body_font_weight',
					'label'    => esc_html__( 'Body Font Weight', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '400',
					'choices'  => array(
						'' => esc_html( 'Select', 'themeum-core' ),
						'100' => esc_html( '100', 'themeum-core' ),
						'200' => esc_html( '200', 'themeum-core' ),
						'300' => esc_html( '300', 'themeum-core' ),
						'400' => esc_html( '400', 'themeum-core' ),
						'500' => esc_html( '500', 'themeum-core' ),
						'600' => esc_html( '600', 'themeum-core' ),
						'700' => esc_html( '700', 'themeum-core' ),
						'800' => esc_html( '800', 'themeum-core' ),
						'900' => esc_html( '900', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'body_font_color',
					'label'    => esc_html__( 'Body Font Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#777777',
				),

				//Menu font
				array(
					'settings' => 'menu_google_font',
					'label'    => esc_html__( 'Select Google Font', 'themeum-core' ),
					'type'     => 'select',
					'default'  => 'Poppins',
					'choices'  => get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '400'
				),
				array(
					'settings' => 'menu_font_size',
					'label'    => esc_html__( 'Menu Font Size', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '14',
				),
				array(
					'settings' => 'menu_font_height',
					'label'    => esc_html__( 'Menu Font Line Height', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '24',
				),
				array(
					'settings' => 'menu_font_weight',
					'label'    => esc_html__( 'Menu Font Weight', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '400',
					'choices'  => array(
						'' => esc_html( 'Select', 'themeum-core' ),
						'100' => esc_html( '100', 'themeum-core' ),
						'200' => esc_html( '200', 'themeum-core' ),
						'300' => esc_html( '300', 'themeum-core' ),
						'400' => esc_html( '400', 'themeum-core' ),
						'500' => esc_html( '500', 'themeum-core' ),
						'600' => esc_html( '600', 'themeum-core' ),
						'700' => esc_html( '700', 'themeum-core' ),
						'800' => esc_html( '800', 'themeum-core' ),
						'900' => esc_html( '900', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'menu_font_color',
					'label'    => esc_html__( 'Menu Font Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#777777',
				),


				//Heading 1
				array(
					'settings' => 'h1_google_font',
					'label'    => esc_html__( 'Heading1 Google Font', 'themeum-core' ),
					'type'     => 'select',
					'default'  => 'Poppins',
					'choices'  => get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '400'
				),
				array(
					'settings' => 'h1_font_size',
					'label'    => esc_html__( 'Heading1 Font Size', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '46',
				),
				array(
					'settings' => 'h1_font_height',
					'label'    => esc_html__( 'Heading1 Font Line Height', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '24',
				),
				array(
					'settings' => 'h1_font_weight',
					'label'    => esc_html__( 'Heading1 Font Weight', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '400',
					'choices'  => array(
						'' => esc_html( 'Select', 'themeum-core' ),
						'100' => esc_html( '100', 'themeum-core' ),
						'200' => esc_html( '200', 'themeum-core' ),
						'300' => esc_html( '300', 'themeum-core' ),
						'400' => esc_html( '400', 'themeum-core' ),
						'500' => esc_html( '500', 'themeum-core' ),
						'600' => esc_html( '600', 'themeum-core' ),
						'700' => esc_html( '700', 'themeum-core' ),
						'800' => esc_html( '800', 'themeum-core' ),
						'900' => esc_html( '900', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'h1_font_color',
					'label'    => esc_html__( 'Heading1 Font Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#777777',
				),

				//Heading 2
				array(
					'settings' => 'h2_google_font',
					'label'    => esc_html__( 'Heading2 Google Font', 'themeum-core' ),
					'type'     => 'select',
					'default'  => 'Poppins',
					'choices'  => get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '400'
				),
				array(
					'settings' => 'h2_font_size',
					'label'    => esc_html__( 'Heading2 Font Size', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '36',
				),
				array(
					'settings' => 'h2_font_height',
					'label'    => esc_html__( 'Heading2 Font Line Height', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '24',
				),
				array(
					'settings' => 'h2_font_weight',
					'label'    => esc_html__( 'Heading2 Font Weight', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '400',
					'choices'  => array(
						'' => esc_html( 'Select', 'themeum-core' ),
						'100' => esc_html( '100', 'themeum-core' ),
						'200' => esc_html( '200', 'themeum-core' ),
						'300' => esc_html( '300', 'themeum-core' ),
						'400' => esc_html( '400', 'themeum-core' ),
						'500' => esc_html( '500', 'themeum-core' ),
						'600' => esc_html( '600', 'themeum-core' ),
						'700' => esc_html( '700', 'themeum-core' ),
						'800' => esc_html( '800', 'themeum-core' ),
						'900' => esc_html( '900', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'h2_font_color',
					'label'    => esc_html__( 'Heading2 Font Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#777777',
				),

				//Heading 3
				array(
					'settings' => 'h3_google_font',
					'label'    => esc_html__( 'Heading3 Google Font', 'themeum-core' ),
					'type'     => 'select',
					'default'  => 'Poppins',
					'choices'  => get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '400'
				),
				array(
					'settings' => 'h3_font_size',
					'label'    => esc_html__( 'Heading3 Font Size', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '26',
				),
				array(
					'settings' => 'h3_font_height',
					'label'    => esc_html__( 'Heading3 Font Line Height', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '24',
				),
				array(
					'settings' => 'h3_font_weight',
					'label'    => esc_html__( 'Heading3 Font Weight', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '400',
					'choices'  => array(
						'' => esc_html( 'Select', 'themeum-core' ),
						'100' => esc_html( '100', 'themeum-core' ),
						'200' => esc_html( '200', 'themeum-core' ),
						'300' => esc_html( '300', 'themeum-core' ),
						'400' => esc_html( '400', 'themeum-core' ),
						'500' => esc_html( '500', 'themeum-core' ),
						'600' => esc_html( '600', 'themeum-core' ),
						'700' => esc_html( '700', 'themeum-core' ),
						'800' => esc_html( '800', 'themeum-core' ),
						'900' => esc_html( '900', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'h3_font_color',
					'label'    => esc_html__( 'Heading3 Font Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#777777',
				),

				//Heading 4
				array(
					'settings' => 'h4_google_font',
					'label'    => esc_html__( 'Heading4 Google Font', 'themeum-core' ),
					'type'     => 'select',
					'default'  => 'Poppins',
					'choices'  => get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '400'
				),
				array(
					'settings' => 'h4_font_size',
					'label'    => esc_html__( 'Heading4 Font Size', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '18',
				),
				array(
					'settings' => 'h4_font_height',
					'label'    => esc_html__( 'Heading4 Font Line Height', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '24',
				),
				array(
					'settings' => 'h4_font_weight',
					'label'    => esc_html__( 'Heading4 Font Weight', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '400',
					'choices'  => array(
						'' => esc_html( 'Select', 'themeum-core' ),
						'100' => esc_html( '100', 'themeum-core' ),
						'200' => esc_html( '200', 'themeum-core' ),
						'300' => esc_html( '300', 'themeum-core' ),
						'400' => esc_html( '400', 'themeum-core' ),
						'500' => esc_html( '500', 'themeum-core' ),
						'600' => esc_html( '600', 'themeum-core' ),
						'700' => esc_html( '700', 'themeum-core' ),
						'800' => esc_html( '800', 'themeum-core' ),
						'900' => esc_html( '900', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'h4_font_color',
					'label'    => esc_html__( 'Heading4 Font Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#777777',
				),

				//Heading 5
				array(
					'settings' => 'h5_google_font',
					'label'    => esc_html__( 'Heading5 Google Font', 'themeum-core' ),
					'type'     => 'select',
					'default'  => 'Poppins',
					'choices'  => get_google_fonts(),
					'google_font' => true,
					'google_font_weight' => 'menu_font_weight',
					'google_font_weight_default' => '400'
				),
				array(
					'settings' => 'h5_font_size',
					'label'    => esc_html__( 'Heading5 Font Size', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '14',
				),
				array(
					'settings' => 'h5_font_height',
					'label'    => esc_html__( 'Heading5 Font Line Height', 'themeum-core' ),
					'type'     => 'number',
					'default'  => '24',
				),
				array(
					'settings' => 'h5_font_weight',
					'label'    => esc_html__( 'Heading5 Font Weight', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '400',
					'choices'  => array(
						'' => esc_html( 'Select', 'themeum-core' ),
						'100' => esc_html( '100', 'themeum-core' ),
						'200' => esc_html( '200', 'themeum-core' ),
						'300' => esc_html( '300', 'themeum-core' ),
						'400' => esc_html( '400', 'themeum-core' ),
						'500' => esc_html( '500', 'themeum-core' ),
						'600' => esc_html( '600', 'themeum-core' ),
						'700' => esc_html( '700', 'themeum-core' ),
						'800' => esc_html( '800', 'themeum-core' ),
						'900' => esc_html( '900', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'h5_font_color',
					'label'    => esc_html__( 'Heading5 Font Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#777777',
				),

			)//fields
		),//typo_setting

		array(
			'id'              => 'package_setting',
			'title'           => esc_html__( 'Package Setting', 'themeum-core' ),
			'description'     => esc_html__( 'Package Setting', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'package_cat_num',
					'label'    => esc_html__( 'Number of Post Show on the Category Listing Page', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => '3',
				),
				array(
					'settings' => 'booknow_btn',
					'label'    => esc_html__( 'Package Book Now Button Text', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => 'Book Now',
				),
				array(
					'settings' => 'hotel_setting_content',
					'label'    => esc_html__( 'Hotel Settings', 'themeum-core' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'hotel_booknow_btn',
					'label'    => esc_html__( 'Hotel Book Now Button Text', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => 'View Details',
				),
				array(
					'settings' => 'vehicle_setting_content',
					'label'    => esc_html__( 'Vehicle Settings', 'themeum-core' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'vehicle_booknow_btn',
					'label'    => esc_html__( 'Vehicle View Details Button Text', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => 'View Details',
				),
			) # Fields
		), # package_setting

		array(
			'id'              => 'layout_styling',
			'title'           => esc_html__( 'Layout & Styling', 'themeum-core' ),
			'description'     => esc_html__( 'Layout & Styling', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'boxfull_en',
					'label'    => esc_html__( 'Select BoxWidth of FullWidth', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'fullwidth',
					'choices'  => array(
						'boxwidth' => esc_html__( 'BoxWidth', 'themeum-core' ),
						'fullwidth' => esc_html__( 'FullWidth', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'body_bg_color',
					'label'    => esc_html__( 'Body background Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),
				array(
					'settings' => 'body_bg_img',
					'label'    => esc_html__( 'Body background image', 'themeum-core' ),
					'type'     => 'image',
					'priority' => 10,
				),
				array(
					'settings' => 'body_bg_attachment',
					'label'    => esc_html__( 'Body Background Attachment', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'fixed',
					'choices'  => array(
						'scroll' => esc_html__( 'Scroll', 'themeum-core' ),
						'fixed' => esc_html__( 'Fixed', 'themeum-core' ),
						'inherit' => esc_html__( 'Inherit', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'body_bg_repeat',
					'label'    => esc_html__( 'Body Background Repeat', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'no-repeat',
					'choices'  => array(
						'repeat' => esc_html__( 'Repeat', 'themeum-core' ),
						'repeat-x' => esc_html__( 'Repeat Horizontally', 'themeum-core' ),
						'repeat-y' => esc_html__( 'Repeat Vertically', 'themeum-core' ),
						'no-repeat' => esc_html__( 'No Repeat', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'body_bg_size',
					'label'    => esc_html__( 'Body Background Size', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'cover',
					'choices'  => array(
						'cover' => esc_html__( 'Cover', 'themeum-core' ),
						'contain' => esc_html__( 'Contain', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'body_bg_position',
					'label'    => esc_html__( 'Body Background Position', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'left top',
					'choices'  => array(
						'left top' => esc_html__('left top', 'themeum-core'),
						'left center' => esc_html__('left center', 'themeum-core'),
						'left bottom' => esc_html__('left bottom', 'themeum-core'),
						'right top' => esc_html__('right top', 'themeum-core'),
						'right center' => esc_html__('right center', 'themeum-core'),
						'right bottom' => esc_html__('right bottom', 'themeum-core'),
						'center top' => esc_html__('center top', 'themeum-core'),
						'center center' => esc_html__('center center', 'themeum-core'),
						'center bottom' => esc_html__('center bottom', 'themeum-core'),
					)
				),
				array(
					'settings' => 'preset',
					'label'    => esc_html__( 'Color Preset', 'themeum-core' ),
					'type'     => 'radio_image',
					'priority' => 10,
					'transport'=> 'postMessage',
					'default'  => '#32aad6',
					'choices'  => array(
						'#32aad6' => plugin_dir_url( __FILE__ ).'assets/presets/1.png',
						'#f7941d' => plugin_dir_url( __FILE__ ).'assets/presets/2.png',
						'#88cb2c' => plugin_dir_url( __FILE__ ).'assets/presets/3.png',
						'#8177db' => plugin_dir_url( __FILE__ ).'assets/presets/4.png',
						'#22c5be' => plugin_dir_url( __FILE__ ).'assets/presets/5.png',
						'#e2b278' => plugin_dir_url( __FILE__ ).'assets/presets/6.png',
					)
				),
				array(
					'settings' => 'custom_preset_en',
					'label'    => esc_html__( 'Set Custom Color', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'major_color',
					'label'    => esc_html__( 'Major Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#32aad6',
				),
				array(
					'settings' => 'hover_color',
					'label'    => esc_html__( 'Hover Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#2695BC',
				),

				array(
					'settings' => 'bottom_color',
					'label'    => esc_html__( 'Bottom background Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '',
				),
				# button color section(new)
				array(
					'settings' => 'button_color_title',
					'label'    => esc_html__( 'Button Color Settings', 'themeum-core' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'button_bg_color',
					'label'    => esc_html__( 'Background Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#32aad6',
				),

				array(
					'settings' => 'button_hover_bg_color',
					'label'    => esc_html__( 'Hover Background Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#2695BC',
				),
				array(
					'settings' => 'button_text_color',
					'label'    => esc_html__( 'Text Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),
				array(
					'settings' => 'button_hover_text_color',
					'label'    => esc_html__( 'Hover Text Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),
				# end button color section.

				# navbar color section start.
				array(
					'settings' => 'menu_color_title',
					'label'    => esc_html__( 'Menu Color Settings', 'themeum-core' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'navbar_text_color',
					'label'    => esc_html__( 'Text Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '',
				),

				/*array(
					'settings' => 'navbar_hover_text_color',
					'label'    => esc_html__( 'Hover Text Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '',
				),*/
				array(
					'settings' => 'navbar_bracket_color',
					'label'    => esc_html__( 'Bracket Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#00aeef',
				),
				array(
					'settings' => 'sub_menu_color_title',
					'label'    => esc_html__( 'Sub-Menu Color Settings', 'themeum-core' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'sub_menu_bg',
					'label'    => esc_html__( 'Background Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),
				array(
					'settings' => 'sub_menu_text_color',
					'label'    => esc_html__( 'Text Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#000',
				),
				array(
					'settings' => 'sub_menu_border',
					'label'    => esc_html__( 'Border Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#eef0f2',
				),
				array(
					'settings' => 'sub_menu_bg_hover',
					'label'    => esc_html__( 'Hover Background Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fbfbfc',
				),
				array(
					'settings' => 'sub_menu_text_color_hover',
					'label'    => esc_html__( 'Hover Text Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#000',
				),
				// End of the navbar color section
				array(
					'settings' => 'sub_footer_widget_color_title',
					'label'    => esc_html__( 'Footer Widget Area Color Settings', 'themeum-core' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'footer_widget_bg_color',
					'label'    => esc_html__( 'Background Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'footer_widget_top_border_color',
					'label'    => esc_html__( 'Top Border Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#eaeaea',
				),
				array(
					'settings' => 'footer_widget_title_color',
					'label'    => esc_html__( 'Widget Title Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#000',
				),
				array(
					'settings' => 'footer_widget_text_color',
					'label'    => esc_html__( 'Text Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#000',
				),
				array(
					'settings' => 'footer_widget_link_color',
					'label'    => esc_html__( 'Link Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#000',
				),
				array(
					'settings' => 'footer_widget_link_color_hvr',
					'label'    => esc_html__( 'Link Hover Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#00aeef',
				),
				// Copyright styling
				array(
					'settings' => 'footer_copyright_color_title',
					'label'    => esc_html__( 'Footer Copyright Area Color Settings', 'themeum-core' ),
					'type'     => 'title',
					'priority' => 10,
				),
				array(
					'settings' => 'footer_color',
					'label'    => esc_html__( 'Background Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#202134',
				),
				array(
					'settings' => 'footer_copyright_text_color',
					'label'    => esc_html__( 'Text Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),
				array(
					'settings' => 'footer_copyright_link_color',
					'label'    => esc_html__( 'Link Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#00aeef',
				),
				array(
					'settings' => 'footer_copyright_link_color_hvr',
					'label'    => esc_html__( 'Link Hover Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#2695BC',
				),

				array(
					'settings' => 'footer_icon_color',
					'label'    => esc_html__( 'Social Icon Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#fff',
				),
				array(
					'settings' => 'footer_icon_color_hvr',
					'label'    => esc_html__( 'Social Icon Hover Color', 'themeum-core' ),
					'type'     => 'color',
					'priority' => 10,
					'default'  => '#00aeef',
				),
				


			)//fields
		),//Layout & Styling

		array(
			'id'              => 'social_media_settings',
			'title'           => esc_html__( 'Social Media', 'themeum-core' ),
			'description'     => esc_html__( 'Social Media', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'wp_facebook',
					'label'    => esc_html__( 'Add Facebook URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_twitter',
					'label'    => esc_html__( 'Add Twitter URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_google_plus',
					'label'    => esc_html__( 'Add Goole Plus URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_pinterest',
					'label'    => esc_html__( 'Add Pinterest URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_youtube',
					'label'    => esc_html__( 'Add Youtube URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_linkedin',
					'label'    => esc_html__( 'Add Linkedin URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_instagram',
					'label'    => esc_html__( 'Add Instagram URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_dribbble',
					'label'    => esc_html__( 'Add Dribbble URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_behance',
					'label'    => esc_html__( 'Add Behance URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_flickr',
					'label'    => esc_html__( 'Add Flickr URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_vk',
					'label'    => esc_html__( 'Add Vk URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'wp_skype',
					'label'    => esc_html__( 'Add Skype URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
			)//fields
		),//social_media

		array(
			'id'              => 'coming_soon',
			'title'           => esc_html__( 'Coming Soon', 'themeum-core' ),
			'description'     => esc_html__( 'Coming Soon', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(

				array(
					'settings' => 'comingsoon_en',
					'label'    => esc_html__( 'Enable Coming Soon', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => false,
				),
				array(
					'settings' => 'comingsoonbg',
					'label'    => esc_html__( 'Upload Coming Soon Page Background', 'themeum-core' ),
					'type'     => 'image',
					'priority' => 10,
				),
				array(
					'settings' => 'comingsoon_date',
					'label'    => esc_html__( 'Coming Soon date', 'themeum-core' ),
					'type'     => 'date',
					'priority' => 10,
					'default'  => '2018-08-09',
				),
				array(
					'settings' => 'newsletter',
					'label'    => esc_html__( 'Add mailchimp Form Shortcode Here', 'themeum-core' ),
					'type'     => 'textarea',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'comingsoon_facebook',
					'label'    => esc_html__( 'Add Facebook URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'comingsoon_twitter',
					'label'    => esc_html__( 'Add Twitter URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'comingsoon_google_plus',
					'label'    => esc_html__( 'Add Google Plus URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'comingsoon_pinterest',
					'label'    => esc_html__( 'Add Pinterest URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'comingsoon_youtube',
					'label'    => esc_html__( 'Add Youtube URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'comingsoon_linkedin',
					'label'    => esc_html__( 'Add Linkedin URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'comingsoon_dribbble',
					'label'    => esc_html__( 'Add Dribbble URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'comingsoon_instagram',
					'label'    => esc_html__( 'Add Instagram URL', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
			)//fields
		),//coming_soon
		array(
			'id'              => '404_settings',
			'title'           => esc_html__( '404 Page', 'themeum-core' ),
			'description'     => esc_html__( '404 page background and text settings', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'errorbg',
					'label'    => esc_html__( 'Upload 404 Page Background Image', 'themeum-core' ),
					'type'     => 'image',
					'priority' => 10,
				),
				array(
					'settings' => '404_title',
					'label'    => esc_html__( '404 Page Title', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => esc_html__('Page not Found.', 'themeum-core')
				),
				array(
					'settings' => '404_description',
					'label'    => esc_html__( '404 Page Description', 'themeum-core' ),
					'type'     => 'textarea',
					'priority' => 10,
					'default'  => esc_html__('The page you are looking for was moved, removed, renamed or might never existed..', 'themeum-core')
				),
				array(
					'settings' => '404_btn_text',
					'label'    => esc_html__( '404 Button Text', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => esc_html__('Go Back Home', 'themeum-core')
				),
			)
		),
		array(
			'id'              => 'blog_setting',
			'title'           => esc_html__( 'Blog Setting', 'themeum-core' ),
			'description'     => esc_html__( 'Blog Setting', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'blog_column',
					'label'    => esc_html__( 'Select Blog Column', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '12',
					'choices'  => array(
						'12' => esc_html( 'Column 1', 'themeum-core' ),
						'6' => esc_html( 'Column 2', 'themeum-core' ),
						'4' => esc_html( 'Column 3', 'themeum-core' ),
						'3' => esc_html( 'Column 4', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'blog_date',
					'label'    => esc_html__( 'Enable Blog Date', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'blog_author',
					'label'    => esc_html__( 'Enable Blog Author', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => false,
				),
				array(
					'settings' => 'blog_category',
					'label'    => esc_html__( 'Enable Blog Category', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => false,
				),
				array(
					'settings' => 'blog_comment',
					'label'    => esc_html__( 'Enable Comment', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'blog_single_comment_en',
					'label'    => esc_html__( 'Enable Single post comment', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'blog_tags',
					'label'    => esc_html__( 'Enable single post tags', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'post_nav_en',
					'label'    => esc_html__( 'Enable Post navigation', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'blog_intro_en',
					'label'    => esc_html__( 'Enable post content', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'blog_post_text_limit',
					'label'    => esc_html__( 'Post character Limit', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '150',
				),
				array(
					'settings' => 'blog_continue_en',
					'label'    => esc_html__( 'Enable Blog Readmore', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'blog_continue',
					'label'    => esc_html__( 'Continue Reading', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => 'Read More',
				),
			)//fields
		),//blog_setting

		array(
			'id'              => 'footer_setting',
			'title'           => esc_html__( 'Footer Setting', 'themeum-core' ),
			'description'     => esc_html__( 'Footer Setting', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				array(
					'settings' => 'bottom_style',
					'label'    => esc_html__( 'Select Bottom Style', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => 'solid',
					'choices'  => array(
						'solid' => esc_html( 'Solid Bottom', 'themeum-core' ),
						'borderimage' => esc_html( 'Border Bottom', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'bottom_column',
					'label'    => esc_html__( 'Select Bottom Column', 'themeum-core' ),
					'type'     => 'select',
					'priority' => 10,
					'default'  => '3',
					'choices'  => array(
						'12' => esc_html( 'Column 1', 'themeum-core' ),
						'6' => esc_html( 'Column 2', 'themeum-core' ),
						'4' => esc_html( 'Column 3', 'themeum-core' ),
						'3' => esc_html( 'Column 4', 'themeum-core' ),
					)
				),
				array(
					'settings' => 'footer_share',
					'label'    => esc_html__( 'Enable Footer Share', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'copyright_en',
					'label'    => esc_html__( 'Enable Copyright Text', 'themeum-core' ),
					'type'     => 'switch',
					'priority' => 10,
					'default'  => true,
				),
				array(
					'settings' => 'copyright_text',
					'label'    => esc_html__( 'Copyright Text', 'themeum-core' ),
					'type'     => 'textarea',
					'priority' => 10,
					'default'  => '',
				),
				array(
					'settings' => 'footer_widget_top_padding',
					'label'    => esc_html__( 'Widget Area Top Padding (px)', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => '85',
				),
				array(
					'settings' => 'footer_widget_bottom_padding',
					'label'    => esc_html__( 'Widget Area Bottom Padding (px)', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => '85',
				),

				array(
					'settings' => 'footer_copyright_top_padding',
					'label'    => esc_html__( 'Copyright Top Padding (px)', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => '26',
				),
				array(
					'settings' => 'footer_copyright_bottom_padding',
					'label'    => esc_html__( 'Copyright Bottom Padding (px)', 'themeum-core' ),
					'type'     => 'number',
					'priority' => 10,
					'default'  => '26',
				),
			)//fields
		),//footer_setting

		array(
			'id'              => 'google_map',
			'title'           => esc_html__( 'Google Map Setting', 'themeum-core' ),
			'description'     => esc_html__( 'Google Map Setting', 'themeum-core' ),
			'priority'        => 10,
			// 'active_callback' => 'is_front_page',
			'fields'         => array(
				
				array(
					'settings' => 'google_map_api',
					'label'    => esc_html__( 'Google Map API', 'themeum-core' ),
					'type'     => 'text',
					'priority' => 10,
					'default'  => '',
				),
				
			)//fields
		),//footer_setting
		
	),
);//travelkit_panel_options


$framework = new THM_Customize( $panel_to_section );
