<?php

/**
 * @author charly capillanes
 * @copyright 2016
 */

?>

<div class="wp-raffle__events--wrap boxies_events-ctrl meta-box-sortables">

      <div class="wp-raffle__events--title">

           <?php 
                $counts = tickets::counts( 'events' ); 
            ?>

           <?php 
                _e( form::page_section_head( 
                        "Events {$counts}", 
                        array( 'setting', 'delete', 'add' ), 
                        array( page_rounter::url( 'settings_wp_raffle', 
                        array( 'setting' => 'events' ) ), '#delete', page_rounter::url( 'add_events_wp_raffle', array() ) ), 
                        'events',
                        array( 'value' => 2, 'name' => 'box[]', 'class' => 'box events' ) 
                      ), 
                      'wp-raffle' 
                ); 
            ?>

           <div class="clear"></div>
      </div>
      
      <div class="wp-raffle__events--inner">
      
           <div class="wp-raffle__events--inside">

                <?php 
                        echo tickets::events_loop(); 
                ?>
                
            </div>
      </div>
      
</div>