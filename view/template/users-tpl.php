<?php

/**
 * @author charly capillanes
 * @copyright 2016
 */

?>

<div class="wp-raffle__users--wrap boxies_users-ctrl meta-box-sortables">

      <div class="wp-raffle__users--title">

           <?php 
                $counts = users::counts(); 
           ?>

           <?php 
                _e( form::page_section_head( 
                        "Users {$counts}", 
                        array( 'setting', 'delete', 'add' ), 
                        array( page_rounter::url( 'settings_wp_raffle', array( 'setting' => 'users' ) ), '#delete', '#add' ), 
                        'users',
                        array( 'value' => 4, 'name' => 'box[]', 'class' => 'box users' ) 
                    ),
                    'wp-raffle'
                ); 
            ?>

           <div class="clear"></div>

      </div>
      
      <div class="wp-raffle__users--inner">
           <div class="wp-raffle__users--inside">
                <?php 
                    echo users::loop(); 
                ?>
            </div>
      </div>
      
</div>