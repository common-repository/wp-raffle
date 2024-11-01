<div id="wp-raffle__wrap">

    <h2>
        <?php 
            _e( 'WP Raffle : Add New Prizes', 'wp-raffle' ); 
        ?>
     </h2>

    <div class="wp-raffle__inner">

         <div class="wp-raffle__add--prizes-wraps">
            <?php 
                echo form::form_open( array( 'method' => 'post' ) ); 
            ?>
            <?php 
                echo form::page_form_prizes( 'prizes' ); 
            ?>
            <?php 
                echo form::form_close(); 
            ?>
         </div>
         
    </div>

</div>