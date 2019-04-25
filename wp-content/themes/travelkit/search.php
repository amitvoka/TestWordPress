<?php get_header(); ?>

<?php get_template_part('lib/sub-header')?>

<?php

global $wp_query;

$args = array();

if (isset($_GET['s']) && !empty($_GET['s'])) {
    $args['s'] = $_GET['s'];
}

if (isset($_GET['post_type']) && !empty($_GET['post_type'])) {
    $args['post_type'] = $_GET['post_type'];
    $args['meta_query'] = array();
    $args['posts_per_page'] = get_theme_mod( 'package_cat_num', 9 );
}



if ( isset($_GET['post_type']) && !empty($_GET['post_type']) && $_GET['post_type'] == 'vehicle' ) {
    $sedual_query_args = array(
        'post_type' => 'schedules',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key'     => 'themeum_schedule_booked',
                'value'   => '0',
                'compare' => '=',
            ),
            array(
                'key'     => 'themeum_schedule_pickup_time',
                'value'   => $_GET['pickup'],
                'compare' => '=',
                'type' => 'DATETIME'
            )
        ),
    );



    $schedules = get_posts($sedual_query_args);

    $cars = array(0);

    foreach ($schedules as $schedule) {
        $cars[] = get_post_meta( $schedule->ID, 'themeum_schedule_car', true );
    }

    $args['post__in'] = $cars;
}

// Guest Meta
if (isset($_GET['guest']) && !empty($_GET['guest']) && isset($args['meta_query'])) {

    $args['meta_query'][] = array(
        'key'     => 'themeum_packperson',
        'value'   => $_GET['guest'],
        'compare' => '=',
    );
}

// Room Type meta
if (isset($_GET['room_type']) && !empty($_GET['room_type']) && isset($args['meta_query'])) {

    $args['meta_query'][] = array(
        'key'     => '_room_type',
        'value'   => $_GET['room_type'],
        'compare' => '=',
    );
}

// CheckIn Meta
if (isset($_GET['checkin']) && !empty($_GET['checkin']) && isset($args['meta_query'])) {
    $args['meta_query'][] = array(
        'key'     => 'themeum_packcheckin',
        'value'   => $_GET['checkin'],
        'compare' => '=',
    );
}




if (isset($_GET['location']) && !empty($_GET['location'])) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'package-location',
            'field'    => 'slug',
            'terms'    => $_GET['location'],
        )
    );
}

if (isset($_GET['hotel_location']) && !empty($_GET['hotel_location'])) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'hotel-location',
            'field'    => 'slug',
            'terms'    => $_GET['hotel_location'],
        )
    );
}

if (isset($_GET['hotel_category']) && !empty($_GET['hotel_category'])) {
    if (count($args['tax_query'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'hotel-category',
            'field'    => 'slug',
            'terms'    => $_GET['hotel_category'],
        );
    } else {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'hotel-category',
                'field'    => 'slug',
                'terms'    => $_GET['hotel_category'],
            )
        );
    }
    
}


if (isset($args['meta_query']) && count($args['meta_query']) >= 2) {
    $args['meta_query']['relation'] = 'AND';
}

if (isset($args['tax_query']) && count($args['tax_query']) >= 2) {
    $args['tax_query']['relation'] = 'AND';
}

if (isset($wp_query->query['paged'])) {
    $args['paged'] = $wp_query->query['paged'];
}
if (!empty($args)) {
    query_posts($args);
}




if ( isset($_GET['post_type']) && ($_GET['post_type'] == 'package' || $_GET['post_type'] == 'hotel' || $_GET['post_type'] == 'vehicle') ) {
	?>
	<div class="thm-tk-search-page">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php echo do_shortcode( '[themeum_package_search]' ); ?>
				</div>
			</div>
		</div>
	</div>
    <div class="thm-tk-flight-spage-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php echo do_shortcode( '[themeum_flight_result]' ); ?>
                </div>
            </div>
        </div>
    </div>
	<?php
}

if ( isset($_GET['post_type']) && !empty($_GET['post_type']) && $_GET['post_type'] == 'package' ) {
    get_template_part( 'search', 'result-package' );
} elseif ( isset($_GET['post_type']) && !empty($_GET['post_type']) && $_GET['post_type'] == 'hotel' ) {
    get_template_part( 'search', 'result-hotel' );
} elseif ( isset($_GET['post_type']) && !empty($_GET['post_type']) && $_GET['post_type'] == 'vehicle' ) {
    get_template_part( 'search', 'result-vehicles' );
} else {
    get_template_part( 'search', 'result' );
}


get_footer();
