<div id="wp-raffle__wrap">

    <h2><?php _e( 'WP Raffle : Add New Tickets', 'wp-raffle' ); ?></h2>
    
    <div class="wp-raffle__inner">
    
         <?php
            // form::page_form_tickets_validate();
         ?>
         
         <div class="wp-raffle__add--tickets-wraps">
            <?php 
                echo form::form_open( array( 'method' => 'post' ) ); 
            ?>
            <?php 
                echo form::page_form_tickets( 'tickets' ); 
            ?>
            <?php 
                echo form::form_close(); 
            ?>
         </div>
         
    </div>

</div>