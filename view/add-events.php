<div id="wp-raffle__wrap">

<h2><?php _e( 'WP Raffle : Add New Events', 'wp-raffle' ); ?></h2>

<div class="wp-raffle__inner">

     <div class="wp-raffle__add--events-wraps">
     
        <?php 
            echo form::form_open( array( 'method' => 'post' ) ); 
        ?>

        <?php 
            echo form::page_form_events( 'events' ); 
        ?>

        <?php 
            echo form::form_close(); 
        ?>
     </div>

</div>

</div>