<div id="wp-raffle__wrap" class="settings-wrap">

    <h2>
        <?php
            $slug = input::get_is_object_element( 'setting' );
            $page = $slug != '' ? ': ' . ucfirst( strtolower( $slug ) ) : __( ': Paypal', 'wp-raffle' );
        ?>

        <?php 
            _e( "Settings {$page}", 'wp-raffle' ); 
        ?>

     </h2>

    <div class="wp-raffle__inner">

         <div class="ajaxs-results"></div>

         <div class="wp-raffle__add--prizes-wraps">
            <?php 
                echo form::form_open( array( 'method' => 'post' ) ); 
            ?>

            <?php
                echo form::settings_page(); 
            ?>

            <?php 
                echo form::form_close(); 
            ?>
         </div>
         
    </div>

</div>