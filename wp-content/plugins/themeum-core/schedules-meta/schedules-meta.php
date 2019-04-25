<?php

add_action("add_meta_boxes", "thm_schedules_metabox");

function thm_schedules_metabox()
{
	add_meta_box("schedules_metabox", "Schedules Metabox", "thm_schedules_metabox_cb", "vehicle", "advanced", "default", null);
}

function thm_schedules_metabox_cb($object)
{
	wp_nonce_field(basename(__FILE__), "schedules-meta-nonce");

	$scheduals = get_post_meta($object->ID, "thm_vc_schedual", false); 
	?>
	<style type="text/css">
		.thm-scheduals{
			display: table;
		}
		.thm-scheduals .thm-schedual{
			display: table-cell;
		}
		.thm-scheduals label{
			display: block;
		}
	</style>

	<div class="thm-vc-booking">
		<div class="thm-book-item">
			
		</div>
	</div>
	
	<!-- <div class="thm-scheduals">
		<div class="thm-schedual">
			<strong>Sunday</strong>
			<label><input type="checkbox" name="scheduals[sunday][offday]" <?php checked( get_post_meta($object->ID, "sunday_offday", true), 'on' ); ?>> Offday</label>
			<label>In Time: <input type="text" class="thm-time-picker" name="scheduals[sunday][in]" value="<?php echo get_post_meta($object->ID, "sunday_in", true); ?>"></label>
			<label>Out Time: <input type="text" class="thm-time-picker" name="scheduals[sunday][out]" value="<?php echo get_post_meta($object->ID, "sunday_out", true); ?>"></label>
		</div>
		<div class="thm-schedual">
			<strong>Monday</strong>
			<label><input type="checkbox" name="scheduals[monday][offday]" <?php checked( get_post_meta($object->ID, "monday_offday", true), 'on' ); ?>> Offday</label>
			<label>In Time: <input type="text" class="thm-time-picker" name="scheduals[monday][in]" value="<?php echo get_post_meta($object->ID, "monday_in", true); ?>"></label>
			<label>Out Time: <input type="text" class="thm-time-picker" name="scheduals[monday][out]" value="<?php echo get_post_meta($object->ID, "monday_out", true); ?>"></label>
		</div>
		<div class="thm-schedual">
			<strong>Tuesday</strong>
			<label><input type="checkbox" name="scheduals[tuesday][offday]" <?php checked( get_post_meta($object->ID, "tuesday_offday", true), 'on' ); ?>> Offday</label>
			<label>In Time: <input type="text" class="thm-time-picker" name="scheduals[tuesday][in]" value="<?php echo get_post_meta($object->ID, "tuesday_in", true); ?>"></label>
			<label>Out Time: <input type="text" class="thm-time-picker" name="scheduals[tuesday][out]" value="<?php echo get_post_meta($object->ID, "tuesday_out", true); ?>"></label>
		</div>
		<div class="thm-schedual">
			<strong>Wednesday</strong>
			<label><input type="checkbox" name="scheduals[wednesday][offday]" <?php checked( get_post_meta($object->ID, "wednesday_offday", true), 'on' ); ?>> Offday</label>
			<label>In Time: <input type="text" class="thm-time-picker" name="scheduals[wednesday][in]" value="<?php echo get_post_meta($object->ID, "wednesday_in", true); ?>"></label>
			<label>Out Time: <input type="text" class="thm-time-picker" name="scheduals[wednesday][out]" value="<?php echo get_post_meta($object->ID, "wednesday_out", true); ?>"></label>
		</div>
		<div class="thm-schedual">
			<strong>Thursday</strong>
			<label><input type="checkbox" name="scheduals[thursday][offday]" <?php checked( get_post_meta($object->ID, "thursday_offday", true), 'on' ); ?>> Offday</label>
			<label>In Time: <input type="text" class="thm-time-picker" name="scheduals[thursday][in]" value="<?php echo get_post_meta($object->ID, "thursday_in", true); ?>"></label>
			<label>Out Time: <input type="text" class="thm-time-picker" name="scheduals[thursday][out]" value="<?php echo get_post_meta($object->ID, "thursday_out", true); ?>"></label>
		</div>
		<div class="thm-schedual">
			<strong>Friday</strong>
			<label><input type="checkbox" name="scheduals[friday][offday]" <?php checked( get_post_meta($object->ID, "friday_offday", true), 'on' ); ?>> Offday</label>
			<label>In Time: <input type="text" class="thm-time-picker" name="scheduals[friday][in]" value="<?php echo get_post_meta($object->ID, "friday_in", true); ?>"></label>
			<label>Out Time: <input type="text" class="thm-time-picker" name="scheduals[friday][out]" value="<?php echo get_post_meta($object->ID, "friday_out", true); ?>"></label>
		</div>
		<div class="thm-schedual">
			<strong>Saturday</strong>
			<label><input type="checkbox" name="scheduals[saturday][offday]" <?php checked( get_post_meta($object->ID, "saturday_offday", true), 'on' ); ?>> Offday</label>
			<label>In Time: <input type="text" class="thm-time-picker" name="scheduals[saturday][in]" value="<?php echo get_post_meta($object->ID, "saturday_in", true); ?>"></label>
			<label>Out Time: <input type="text" class="thm-time-picker" name="scheduals[saturday][out]" value="<?php echo get_post_meta($object->ID, "saturday_out", true); ?>"></label>
		</div>
	</div> -->
	<?php
}

function thm_schedules_meta_save($post_id, $post, $update)
{
	if (!isset($_POST["schedules-meta-nonce"]) || !wp_verify_nonce($_POST["schedules-meta-nonce"], basename(__FILE__))){
        return $post_id;
	}

    if(!current_user_can("edit_post", $post_id)){
        return $post_id;
    }

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE){
        return $post_id;
    }

    $scheduals = $_POST['scheduals'];

    foreach ($scheduals as $day => $schedual) {
    	if (isset($schedual['offday'])) {
    		update_post_meta($post_id, $day.'_offday', $schedual['offday']);
    	} else {
    		update_post_meta($post_id, $day.'_offday', 'off');
    	}

    	if (isset($schedual['in'])) {
    		update_post_meta($post_id, $day.'_in', $schedual['in']);
    	}

    	if (isset($schedual['out'])) {
    		update_post_meta($post_id, $day.'_out', $schedual['out']);
    	}
    }

    
}

add_action("save_post", "thm_schedules_meta_save", 10, 3);