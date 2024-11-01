
<?php

/**
 * @author charly capillanes
 * @copyright 2016
 */

$userdata = get_userdata( get_current_user_id() );

?>


<div id="wp-raffle__wrap" class="time-schedule_manager">
    
    <h2><?php _e( 'WP Raffle : Time Schedule Manager', 'wp-raffle' ); ?></h2>
    
    <div class="ajaxs-results">
        <?php
        
        ?>
    </div>

    <?php
        load::view( 'template/time_schedule-tpl' ); 
    ?>
    
    <?php
        load::view( 'template/select-winner-tpl' ); 
    ?>
    
</div>