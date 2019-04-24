<?php
    /**
    * General Sub-tabs.
    *
    * @since    1.0.0
    */
    function wp_travel_engine_settings_general_subtabs() {
        $options[] = array(
            'page_settings'        => WP_TRAVEL_ENGINE_BASE_PATH.'/includes/backend/settings/sub-tabs/page_settings.php',
            'trip_tabs_settings'   => WP_TRAVEL_ENGINE_BASE_PATH.'/includes/backend/settings/sub-tabs/tabs_settings.php',
            'trip_info'           => WP_TRAVEL_ENGINE_BASE_PATH.'/includes/backend/settings/sub-tabs/trip_info.php'
            );
        $options[] = apply_filters( 'wp_travel_engine_settings_general_sub_tabs', $options );
        return $options;
    }
    ?>
    <div class="accordion-content clearfix" name="1">
        <div class="tabs-custom">
        	<ul>
        	   	<?php 
                $args = wp_travel_engine_settings_general_subtabs();
        	   	foreach ( $args[1] as $key ) { 
                    foreach ( $key as $k=>$val ){ ?>
               			<li><a href="#<?php echo $k;?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
    <g id="baseline-check_box-24px" transform="translate(-3 -3)">
        <path id="Path_1" data-name="Path 1" class="cls-1" d="M0,0H12V12H0Z" transform="translate(4 4)" />
        <path id="Path_2" data-name="Path 2" class="cls-2" d="M15.444,3H4.556A1.555,1.555,0,0,0,3,4.556V15.444A1.555,1.555,0,0,0,4.556,17H15.444A1.555,1.555,0,0,0,17,15.444V4.556A1.555,1.555,0,0,0,15.444,3Zm-7,10.889L4.556,10l1.1-1.1,2.792,2.784,5.9-5.9,1.1,1.1Z" transform="translate(0 0)" />
    </g>
</svg><?php $k = str_replace('_', ' ', $k); echo $k;?></a></li>
        	   		<?php	
        			}  
        	   	} ?>
            </ul>
            <?php
            foreach ( $args[1] as $key ) { 
        	   	foreach ( $key as $k=>$val ){ ?>
	        		<div id = "<?php echo $k;?>">
			        	<?php
							include $val;
						?>	
	        		</div>
	        	<?php }
			} ?>
		</div>
	</div>	
