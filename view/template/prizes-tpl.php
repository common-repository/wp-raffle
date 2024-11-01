<?php

/**
 * @author charly capillanes
 * @copyright 2016
 */

?>

<div class="wp-raffle__prizes--wrap boxies_prizes-ctrl meta-box-sortables">

      <div class="wp-raffle__prizes--title">
           <?php 
                $counts = prizes::counts( 'prizes' ); 
           ?>
           <?php 
                _e( form::page_section_head( 
                        "Prizes {$counts}", 
                        array( 'setting', 'delete', 'add' ), 
                        array( page_rounter::url( 'settings_wp_raffle', array( 'setting' => 'prizes' ) ), '#delete', page_rounter::url( 'add_prizes_wp_raffle', array() ) ), 
                        'events',
                        array( 'value' => 3, 'name' => 'box[]', 'class' => 'box prizes' )  
                    ), 
                        'wp-raffle'
                  ); 
           ?>
           <div class="clear"></div>
      </div>
      
      <div class="wp-raffle__prizes--inner">
           <div class="wp-raffle__prizes--inside">
                <?php
                    echo prizes::loop();
                ?>
           </div>
      </div>
      
</div>