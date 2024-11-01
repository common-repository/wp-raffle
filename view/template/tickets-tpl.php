
<div class="wp-raffle__tickets--wrap boxies_tickets-ctrl meta-box-sortables">

    <div class="wp-raffle__tickets--title">
       <?php 
            $counts = tickets::counts( 'tickets' );  
       ?>
       <?php 
            _e( form::page_section_head( 
                  "Tickets {$counts}", 
                  array( 'setting', 'delete', 'add' ), 
                  array( page_rounter::url( 'settings_wp_raffle', array( 'setting' => 'tickets' ) ), 
                    '#delete', 
                    page_rounter::url( 'add_tickets_wp_raffle', array() ) 
                  ), 
                  'tickets',
                  array( 'value' => 1, 'name' => 'box[]', 'class' => 'box tickets' ) 
                ), 
                'wp-raffle' 
            ); 
       ?>
    </div>
    
    <div class="wp-raffle__tickets--inner">
       <div class="wp-raffle__tickets--inside">
            <?php 
                echo tickets::loop(); 
            ?>
       </div>
    </div>
    
</div>