
<?php
    $userdata = user_control::get_userdata_objects();
?>

<div class="wp-raffle__accounts--wrap boxies_users-ctrl meta-box-sortables">

      <div class="wp-raffle__accounts--title">
           <?php 
                 $counts   = users::counts(); 
                 $profiles = admin_url( '/profile.php' );
           ?>
           <?php 
            _e( form::accounts_section_head( 
                "Accounts", 
                    array( 'accounts'=> 'Accounts', 
                            'tickets'=> 'Tickets', 
                            'events' => 'Events',
                            'prizes' => 'Prizes' ), 
                    array( 'delete', 
                            'add' ), 
                    array( $profiles, 
                            '#status-online' ), 
                    'accounts', 
                    array( 'value' => 5, 'name' => 'box[]', 'class' => 'box accounts' )
                ), 
                'wp-raffle' 
              ); 
           ?>
           <div class="clear"></div>
      </div>
      
      <div class="wp-raffle__accounts--inner">
           <div class="wp-raffle__accounts--inside accounts--inner_tabs tab1">
                <?php 
                    echo __( users::tab1_accounts( $userdata ), 'wp-raffle' ); 
                ?>
           </div>
           <div class="wp-raffle__accounts--inside accounts--inner_tabs tab2">
                <?php 
                   echo __( users::tab2_tickets( $userdata ), 'wp-raffle' ); 
                ?>
           </div>
           <div class="wp-raffle__accounts--inside accounts--inner_tabs tab3">
                <?php 
                    echo __( users::tab3_events( $userdata ), 'wp-raffle' ); 
                ?>
           </div>
           <div class="wp-raffle__accounts--inside accounts--inner_tabs tab4">
                <?php 
                    echo __( users::tab4_events( $userdata ), 'wp-raffle' ); 
                ?>
           </div>
      </div>
      
</div>