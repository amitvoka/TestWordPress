<?php

function themeum_lms_dashboard_widget_init() {

	wp_add_dashboard_widget(
		'themeum_lms_dashboard_widget',
		'<span class="dashicons dashicons-welcome-learn-more"></span>' . ' ' . __('Themeum LMS Status', 'themeum-ls'),
		'themeum_lms_dashboard_widget_function'
		);	
}
// add_action( 'wp_dashboard_setup', 'themeum_lms_dashboard_widget_init' );

function themeum_lms_dashboard_widget_function() {
	?>
	<ul class="themeum-lms-data clearfix">
		<li class="total-sales">
			<a href="edit.php?post_type=lmsorder">
				<span class="dashicons dashicons-chart-bar"></span> <?php echo get_option( 'currrency_type', '$' ) . ' ' . themeum_lms_get_total_sales(); ?>
			</a>
		</li>
		<li class="total-orders">
			<a href="edit.php?post_type=lmsorder">
				<span class="dashicons dashicons-cart"></span> <?php echo themeum_lms_get_count('lmsorder', 'themeum_status_all', 'complete') . ' ' . __('Total Orders', 'themeum-lms'); ?>
			</a>
		</li>
		<li class="pending-orders">
			<a href="edit.php?post_type=lmsorder">
				<span class="dashicons dashicons-info"></span> <?php echo themeum_lms_get_count('lmsorder', 'themeum_status_all', 'pending') . ' ' . __('Pending Orders', 'themeum-lms'); ?>
			</a>
		</li>
		<li class="courses-count">
			<a href="edit.php?post_type=course">
				<span class="dashicons dashicons-welcome-learn-more"></span> <?php echo wp_count_posts('course')->publish . ' ' . __('Courses', 'themeum-lms'); ?>
			</a>
		</li>
		<li class="lessons-count">
			<a href="edit.php?post_type=lesson">
				<span class="dashicons dashicons-book-alt"></span> <?php echo wp_count_posts('lesson')->publish . ' ' . __('Lessons', 'themeum-lms'); ?>
			</a>
		</li>
		<li class="teachers-count">
			<a href="edit.php?post_type=teacher">
				<span class="dashicons dashicons-groups"></span> <?php echo wp_count_posts('teacher')->publish . ' ' . __('Teachers', 'themeum-lms'); ?>
			</a>
		</li>
		<li class="events-count">
			<a href="edit.php?post_type=event">
				<span class="dashicons dashicons-megaphone"></span> <?php echo wp_count_posts('event')->publish . ' ' . __('Events', 'themeum-lms'); ?>
			</a>
		</li>
	</ul>
	<?php

	$days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
	$data = '';

	for ($i=1; $i<=$days; $i++) { 
		$data .= '"' . themeum_lms_get_sales($i, date('m'), date('Y')) . '",';
	}

	$data = rtrim($data, ',');

	$labels = '';
	$month = date('M');

	for ($i=1; $i<=$days; $i++) { 
		$labels .= '"' . $month .' - ' . $i . '",';
	}

	$labels = rtrim($labels, ',');

	?>

	<div class="themeum-lms-chart">
		<div>
			<canvas id="themeum-lms-canvas" height="400" width="600"></canvas>
		</div>
	</div>

	<script>
	var barChartData = {
		labels : [<?php echo $labels; ?>],
		datasets : [
			{
				fillColor : "#48b0f7",
				strokeColor : "#48b0f7",
				highlightFill: "#2690d8",
				highlightStroke: "#2690d8",
				data : [<?php echo $data; ?>]
			}
		]

	}
	window.onload = function(){
		var ctx = document.getElementById("themeum-lms-canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive : true
		});
	}

	</script>
	<?php

}

function themeum_lms_get_sales($day, $month, $year) {

	global $wpdb;
	$result = $wpdb->get_row("SELECT SUM(pt.meta_value) as total_price FROM " . $wpdb->prefix . "posts AS p RIGHT JOIN " . $wpdb->prefix . "postmeta AS pt ON p.ID = pt.post_id RIGHT JOIN " . $wpdb->prefix . "postmeta as pt2 ON p.ID = pt2.post_id WHERE p.post_type = 'lmsorder' AND pt.meta_key = 'themeum_order_price' AND pt2.meta_value = 'complete' AND DAY(p.post_date) = '". $day ."' AND MONTH(p.post_date) = '". $month ."' AND YEAR(p.post_date) = '". $year ."'");

	if($result->total_price) {
		return $result->total_price;
	} else {
		return "0";
	}
}

function themeum_lms_get_total_sales() {

	global $wpdb;
	$result = $wpdb->get_row("SELECT SUM(pt.meta_value) as total_price FROM " . $wpdb->prefix . "posts AS p RIGHT JOIN " . $wpdb->prefix . "postmeta AS pt ON p.ID = pt.post_id RIGHT JOIN " . $wpdb->prefix . "postmeta as pt2 ON p.ID = pt2.post_id WHERE p.post_type = 'lmsorder' AND pt.meta_key = 'themeum_order_price' AND pt2.meta_value = 'complete'");

	if($result->total_price) {
		return $result->total_price;
	} else {
		return "0";
	}
}



function themeum_lms_get_count($post_type, $meta_key, $meta_value) {

	$posts = get_posts(
		array(
			'post_type' 	=> $post_type,
			'meta_key'		=> $meta_key,
			'meta_value'	=> $meta_value
		)
	);

	return count($posts);
}


//Add admin assets
function themeum_lms_load_admin_assets() {
	wp_enqueue_style( 'themeum-lms-dashboard', plugins_url('themeum-lms') . '/admin/assets/css/dashboard.css', false );
	wp_enqueue_script( 'themeum-lms-dashboard', plugins_url('themeum-lms') . '/admin/assets/js/Chart.min.js', false );
	wp_enqueue_script( 'themeum-lms-dashboard', plugins_url('themeum-lms') . '/admin/assets/js/main.js', false );
}
add_action( 'admin_enqueue_scripts', 'themeum_lms_load_admin_assets' );