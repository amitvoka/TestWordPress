<?php
function themeum_package_search_cb2( $atts ) {
	$atts = shortcode_atts( array(
		'holidays' => 1,
		'hotel' => 1,
		'flights' => 1,
		'vehicles' => 1,
	), $atts );

	$active_total = 0;

	$atts['holidays'] = (int) $atts['holidays'];
	$atts['hotel'] = (int) $atts['hotel'];
	$atts['flights'] = (int) $atts['flights'];
	$atts['vehicles'] = (int) $atts['vehicles'];


	if ($atts['holidays']) {
		$active_total++;
	}

	if ($atts['hotel']) {
		$active_total++;
	}

	if ($atts['flights']) {
		$active_total++;
	}

	if ($atts['vehicles']) {
		$active_total++;
	}

	$checkin 			= isset($_GET['checkin']) ? $_GET['checkin'] : '';
	$guest 				= isset($_GET['guest']) ? $_GET['guest'] : '';
	$get_location 		= isset($_GET['location']) ? $_GET['location'] : '';
	$vehicle_location 	= isset($_GET['vehicle_location']) ? $_GET['vehicle_location'] : '';
	$vehicle_category 	= isset($_GET['vehicle_category']) ? $_GET['vehicle_category'] : '';
	$hotel_location 	= isset($_GET['hotel_location']) ? $_GET['hotel_location'] : '';
	$hotel_category 	= isset($_GET['hotel_category']) ? $_GET['hotel_category'] : '';
	$room_type 			= isset($_GET['room_type']) ? $_GET['room_type'] : '';

	$sterm = isset($_GET['s']) ? $_GET['s'] : '';

	$holidays_active = '';
	$hotel_active = '';
	$vehicles_active = '';

	if (get_post_type() == 'hotel') {
		$hotel_active = 'active';
	} else if (get_post_type() == 'vehicle'){
		$vehicles_active = 'active';
	} else {
		$holidays_active = 'active';
	}

	ob_start();
	?>

	<div class="thm-tk-search thm-tk-search-v2">
		<?php if($active_total >= 2): ?>
		<div class="thm-tk-search-nav">
			<ul>
				<?php if($atts['holidays']): ?>
					<li><a href="#holidays" class="<?php echo esc_attr($holidays_active); ?>"><i class="icon-location"></i> <span><?php esc_html_e('Holidays', 'themeum-core'); ?></span></a></li>
				<?php endif; ?>
				<?php if($atts['hotel']): ?>
					<li><a href="#hotel" class="<?php echo esc_attr($hotel_active); ?>"><i class="icon-hotel"></i> <span><?php esc_html_e('Hotels &amp; Resorts', 'themeum-core'); ?></span></a></li>
				<?php endif; ?>
				<?php if($atts['flights']): ?>
					<li><a href="#flights"><i class="icon-flight"></i> <span><?php esc_html_e('Flights', 'themeum-core'); ?></span></a></li>
				<?php endif; ?>
				<?php if($atts['vehicles']): ?>
					<li><a href="#vehicles" class="<?php echo esc_attr($vehicles_active); ?>"><i class="icon-vehicles"></i> <span><?php esc_html_e('Vehicles', 'themeum-core'); ?></span></a></li>
				<?php endif; ?>
			</ul>
		</div>
		<?php endif; ?>
		<div class="thm-tk-search-form">
			<?php if($atts['holidays']): ?>
			<div class="thm-tk-tab <?php echo esc_attr($holidays_active); ?>" id="holidays">
				<div class="thm-tk-tab-inner clearfix">
					<form id="thm-tk-advancedsearch-form" class="clearfix" action="<?php echo esc_url(home_url( '/' )); ?>" method="GET">
						<div class="thm-tk-input-2-1">
							<label><?php esc_html_e('Search', 'themeum-core'); ?></label>
							<input type="text" name="s" id="search-keyword" class="thm-tk-input-first" placeholder="<?php esc_html_e('Type keyword', 'themeum-core'); ?>" value="<?php echo esc_attr( $sterm ); ?>">
						</div>

						<?php
							$locations = get_terms( 'package-location', array( 'hide_empty' => false, ) );
						?>
						<div class="thm-tk-input-2-1">
							<label><?php esc_html_e('Destination', 'themeum-core'); ?></label>
							<select name="location" class="select2">
								<option value=""><?php esc_html_e('Select a destination', 'themeum-core'); ?></option>
								<?php if (!empty($locations)): ?>
									<?php foreach ($locations as $location): ?>
										<option value="<?php echo esc_attr($location->slug); ?>" <?php selected( $get_location, $location->slug); ?>><?php echo esc_html($location->name); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
						<div class="thm-tk-input-2-1">
							<label><?php esc_html_e('Check-in date', 'themeum-core'); ?></label>
							<input type="text" name="checkin" class="thm-date-picker" value="<?php echo esc_attr( $checkin ); ?>" placeholder="<?php esc_html_e('Check-in date', 'themeum-core'); ?>" >
						</div>
						<div class="thm-tk-input-2-1">
							<label><?php esc_html_e('Guest', 'themeum-core'); ?></label>
							<input type="number" name="guest" class="" value="<?php echo esc_attr( $guest ); ?>" placeholder="<?php esc_html_e('Number of guests', 'themeum-core'); ?>" >
						</div>
						<input type="hidden" name="post_type" value="package">
						<button class="btn btn-primary thm-tk-search-btn" type="submit"><?php esc_html_e('Search', 'themeum-core'); ?></button>
					</form>
				</div>
			</div>
			<?php endif; ?>
			<?php if($atts['hotel']): ?>
			<div class="thm-tk-tab <?php echo esc_attr($hotel_active); ?>" id="hotel">
				<div class="thm-tk-tab-inner">
					<form id="thm-tk-advancedsearch-form" class="clearfix" action="<?php echo esc_url(home_url( '/' )); ?>" method="GET">
						<div class="thm-tk-input-2-1">
							<label><?php esc_html_e('Search', 'themeum-core'); ?></label>
							<input type="text" name="s" id="search-keyword" class="thm-tk-input-first" placeholder="<?php esc_html_e('Type keyword', 'themeum-core'); ?>" value="<?php echo esc_attr( $sterm ); ?>">
						</div>

						<?php
							$locations = get_terms( 'hotel-location', array( 'hide_empty' => false, ) );
						?>
						<div class="thm-tk-input-2-1">
							<label><?php esc_html_e('Location', 'themeum-core'); ?></label>
							<select name="hotel_location" class="select2">
								<option value=""><?php esc_html_e('Select a location', 'themeum-core'); ?></option>
								<?php if (!empty($locations)): ?>
									<?php foreach ($locations as $location): ?>
										<option value="<?php echo esc_attr($location->slug); ?>" <?php selected( $hotel_location, $location->slug); ?>><?php echo esc_html($location->name); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
						<?php
							$categories = get_terms( 'hotel-category', array( 'hide_empty' => false, ) );
						?>
						<div class="thm-tk-input-2-1">
							<label><?php esc_html_e('Hotel Type', 'themeum-core'); ?></label>
							<select name="hotel_category" class="select2">
								<option value=""><?php esc_html_e('Hotel Type', 'themeum-core'); ?></option>
								<?php if (!empty($categories)): ?>
									<?php foreach ($categories as $category): ?>
										<option value="<?php echo esc_attr($category->slug); ?>" <?php selected( $hotel_category, $category->slug); ?>><?php echo esc_html($category->name); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
						<?php
							$rooms = array(
								'doubleroom'   		=>  esc_html__( 'Double Room', 'themeum-core' ),
								'singleroom'   	=> esc_html__( 'Single Room', 'themeum-core' ),
								'luxuryroom'     	=> esc_html__( 'Luxury Room', 'themeum-core' ),
								'generalroom'     	=> esc_html__( 'General Room', 'themeum-core' ),
								'familyroom'     	=> esc_html__( 'Family Room', 'themeum-core' ),
								'deluxeroom'     => esc_html__( 'Deluxe Room', 'themeum-core' ),
							);
						?>
						<div class="thm-tk-input-2-1">
							<label><?php esc_html_e('Room Type', 'themeum-core'); ?></label>
							<select name="room_type" class="select2">
								<option value=""><?php esc_html_e('Room Type', 'themeum-core'); ?></option>
								<?php if (!empty($rooms)): ?>
									<?php foreach ($rooms as $room_id => $room_name): ?>
										<option value="<?php echo esc_attr($room_id); ?>" <?php selected( $room_type, $room_id); ?>><?php echo esc_html($room_name); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
						<input type="hidden" name="post_type" value="hotel">
						<button class="btn btn-primary thm-tk-search-btn" type="submit"><?php esc_html_e('Search', 'themeum-core'); ?></button>
					</form>
				</div>
			</div>
			<?php endif; ?>
			<?php if($atts['flights']): ?>
			<div class="thm-tk-tab" id="flights">
				<div class="thm-tk-tab-inner">
					<?php
						$supported_locals = array(
							"ar-AE",
							"az-AZ",
							"bg-BG",
							"ca-ES",
							"cs-CZ",
							"da-DK",
							"de-DE",
							"el-GR",
							"en-GB",
							"en-GG",
							"en-US",
							"es-ES",
							"es-MX",
							"et-EE",
							"fi-FI",
							"fr-FR",
							"hr-HR",
							"hu-HU",
							"id-ID",
							"it-IT",
							"ja-JP",
							"ko-KR",
							"lt-LT",
							"lv-LV",
							"ms-MY",
							"nb-NO",
							"nl-NL",
							"pl-PL",
							"pt-BR",
							"pt-PT",
							"ro-RO",
							"ru-RU",
							"sk-SK",
							"sv-SE",
							"th-TH",
							"tl-PH",
							"tr-TR",
							"uk-UA",
							"vi-VN",
							"zh-CN",
							"zh-HK",
							"zh-SG",
							"zh-TW",
						);

						$current_local = str_replace('_', '-', get_locale());

					?>
					<form id="thm-tk-flights-search-form" class="clearfix">
						<div class="thm-tk-input-3-1 thm-tk-first-select">
							<label><?php esc_html_e('From', 'themeum-core'); ?></label>
							<select name="originplace" class="thm-fs-place" data-placeholder="<?php esc_html_e('Origin city or airport', 'themeum-core'); ?>"></select>
						</div>
						<div class="thm-tk-input-3-1">
							<label><?php esc_html_e('To', 'themeum-core'); ?></label>
							<select name="destinationplace" class="thm-fs-place" data-placeholder="<?php esc_html_e('Destination city or airport', 'themeum-core'); ?>"></select>
						</div>
						<div class="thm-tk-input-3-1">
							<label><?php esc_html_e('Departure', 'themeum-core'); ?></label>
							<input type="text" name="outbounddate" class="thm-date-picker" placeholder="<?php esc_html_e('Departure date', 'themeum-core'); ?>">
						</div>
						<div class="thm-tk-input-3-1">
							<label><?php esc_html_e('Return', 'themeum-core'); ?></label>
							<input type="text" name="inbounddate" class="thm-date-picker" placeholder="<?php esc_html_e('Return date', 'themeum-core'); ?>">
						</div>
						<div class="thm-tk-input-3-1">
							<label><?php esc_html_e('Class Type', 'themeum-core'); ?></label>
							<select name="cabinclass" class="select2">
								<option value="Economy"><?php esc_html_e('Economy', 'themeum-core'); ?></option>
								<option value="PremiumEconomy"><?php esc_html_e('Premium Economy', 'themeum-core'); ?></option>
								<option value="Business"><?php esc_html_e('Business', 'themeum-core'); ?></option>
								<option value="First"><?php esc_html_e('First', 'themeum-core'); ?></option>
							</select>
						</div>
						<div class="thm-tk-input-3-1">
							<div class="thm-tk-input-2-1">
								<label><?php esc_html_e('Adults', 'themeum-core'); ?></label>
								<select name="adults" class="select2">
									<option value="1"><?php esc_html_e('1', 'themeum-core'); ?></option>
									<option value="2"><?php esc_html_e('2', 'themeum-core'); ?></option>
									<option value="3"><?php esc_html_e('3', 'themeum-core'); ?></option>
									<option value="4"><?php esc_html_e('4', 'themeum-core'); ?></option>
									<option value="5"><?php esc_html_e('5', 'themeum-core'); ?></option>
									<option value="6"><?php esc_html_e('6', 'themeum-core'); ?></option>
									<option value="7"><?php esc_html_e('7', 'themeum-core'); ?></option>
									<option value="8"><?php esc_html_e('8', 'themeum-core'); ?></option>
								</select>
							</div>
							<div class="thm-tk-input-2-1">
								<label><?php esc_html_e('Kids', 'themeum-core'); ?></label>
								<select name="children" class="select2">
									<option value="0"><?php esc_html_e('0', 'themeum-core'); ?></option>
									<option value="1"><?php esc_html_e('1', 'themeum-core'); ?></option>
									<option value="2"><?php esc_html_e('2', 'themeum-core'); ?></option>
									<option value="3"><?php esc_html_e('3', 'themeum-core'); ?></option>
									<option value="4"><?php esc_html_e('4', 'themeum-core'); ?></option>
									<option value="5"><?php esc_html_e('5', 'themeum-core'); ?></option>
									<option value="6"><?php esc_html_e('6', 'themeum-core'); ?></option>
									<option value="7"><?php esc_html_e('7', 'themeum-core'); ?></option>
									<option value="8"><?php esc_html_e('8', 'themeum-core'); ?></option>
								</select>
							</div>
						</div>
						<input type="hidden" name="country" value="<?php echo get_theme_mod( 'flight_market', 'US' ); ?>">
						<input type="hidden" name="currency" value="<?php echo get_theme_mod( 'flight_currency', 'USD' ); ?>">
						<?php if(in_array($current_local, $supported_locals)): ?>
							<input type="hidden" name="locale" value="<?php echo esc_attr( $current_local ); ?>">
						<?php else: ?>
							<input type="hidden" name="locale" value="en-US">
						<?php endif; ?>
						<button class="btn btn-primary thm-tk-search-btn" id="flight-search" type="submit"><?php esc_html_e('Search', 'themeum-core'); ?></button>
					</form>
				</div>
			</div>
			<?php endif; ?>
			<?php if($atts['vehicles']): ?>
			<div class="thm-tk-tab <?php echo esc_attr($vehicles_active); ?>" id="vehicles">
				<div class="thm-tk-tab-inner">
					<form id="thm-tk-advancedsearch-form" class="clearfix" action="<?php echo esc_url(home_url( '/' )); ?>" method="GET">
						<div class="thm-tk-input-2-1">
							<label for="search-keyword"><?php esc_html_e('Search', 'themeum-core'); ?></label>
							<input type="text" name="s" id="search-keyword" class="thm-tk-input-first" placeholder="<?php esc_html_e('Type keyword', 'themeum-core'); ?>" value="<?php echo esc_attr( $sterm ); ?>">
						</div>

						<div class="thm-tk-input-2-1">
							<label for="search-keyword"><?php esc_html_e('Pickup Time', 'themeum-core'); ?></label>
							<input type="text" name="pickup" id="search-keyword" class="thm-date-time-picker" placeholder="<?php esc_html_e('Type keyword', 'themeum-core'); ?>" value="<?php echo esc_attr( $sterm ); ?>">
						</div>

						<?php
							$locations = get_terms( 'vehicle-location', array( 'hide_empty' => false, ) );
						?>
						<div class="thm-tk-input-2-1">
							<label><?php esc_html_e('Location', 'themeum-core'); ?></label>
							<select name="vehicle_location" class="select2">
								<option value=""><?php esc_html_e('Select a location', 'themeum-core'); ?></option>
								<?php if (!empty($locations)): ?>
									<?php foreach ($locations as $location): ?>
										<option value="<?php echo esc_attr($location->slug); ?>" <?php selected( $vehicle_location, $location->slug); ?>><?php echo esc_html($location->name); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
						<?php
							$categories = get_terms( 'vehicle-category', array( 'hide_empty' => false, ) );
						?>
						<div class="thm-tk-input-2-1">
							<label><?php esc_html_e('Vehicle Type', 'themeum-core'); ?></label>
							<select name="vehicle_category" class="select2">
								<option value=""><?php esc_html_e('Vehicle Type', 'themeum-core'); ?></option>
								<?php if (!empty($categories)): ?>
									<?php foreach ($categories as $category): ?>
										<option value="<?php echo esc_attr($category->slug); ?>" <?php selected( $vehicle_category, $category->slug); ?>><?php echo esc_html($category->name); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
						<input type="hidden" name="post_type" value="vehicle">
						<button class="btn btn-primary thm-tk-search-btn" type="submit"><?php esc_html_e('Search', 'themeum-core'); ?></button>
					</form>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<?php
	return ob_get_clean();
}
add_shortcode( 'themeum_package_search2','themeum_package_search_cb2' );


# Visual Composer
if (class_exists('WPBakeryVisualComposerAbstract')) {
	vc_map(array(
		"name" => esc_html__("Themeum Advance Search 2", 'themeum-core'),
		"base" => "themeum_package_search2",
		'icon' => 'icon-thm-latest-post',
		"class" => "",
		"description" => esc_html__("Widget Title Heading", 'themeum-core'),
		"category" => esc_html__('Travelkit', 'themeum-core'),
		"params" => array(

			array(
				"type" => "dropdown",
				"heading" => esc_html__("Holidays", 'themeum-core'),
				"param_name" => "holidays",
				"value" => array(
					__('Enable', 'themeum-core') => 1,
					__('Disable', 'themeum-core') => 0,
				),
			),
			array(
				"type" => "dropdown",
				"heading" => esc_html__("Hotel", 'themeum-core'),
				"param_name" => "hotel",
				"value" => array(
					__('Enable', 'themeum-core') => 1,
					__('Disable', 'themeum-core') => 0,
				),
			),
			array(
				"type" => "dropdown",
				"heading" => esc_html__("Flights", 'themeum-core'),
				"param_name" => "flights",
				"value" => array(
					__('Enable', 'themeum-core') => 1,
					__('Disable', 'themeum-core') => 0,
				),
			),
			array(
				"type" => "dropdown",
				"heading" => esc_html__("Vehicles", 'themeum-core'),
				"param_name" => "vehicles",
				"value" => array(
					__('Enable', 'themeum-core') => 1,
					__('Disable', 'themeum-core') => 0,
				),
			)
		)

	));
}
