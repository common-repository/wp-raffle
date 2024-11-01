<?php

$userdata = get_userdata( get_current_user_id() );
$shortcode_slug = 'wp_raffle';

?>

<div id="wp-raffle__wrap">

    <?php
        $user_role = user_control::get_role();
    ?>
    
    <h2 class="wp-raffle_manager-title">
        <?php 
            _e( 'WP Raffle : Help?', 'wp-raffle' ); 
        ?>
    </h2>
    
    <div class="ajaxs-results">
        
        <?php

            $helps[] = array(
                        'title' => 'Manager',
                        'descr' => array( 'to manage all data querys on a one single page (dashboard)' ),
                        'links' => page_rounter::url( 'wp_raffle', false ),
                        'codes' => null,
                        'settings' => null
                    );

            $helps[] = array(
                        'title' => 'Time Schedule',
                        'descr' => array( 'to manage event time schedule and generate random selected user`s' ),
                        'links' => page_rounter::url( 'time_schedule_wp_raffle', false ),
                        'codes' => '['.__( $shortcode_slug, 'slug' ).'_shortcode_randoms], ['.__( $shortcode_slug, 'slug' ).'_shortcode_times]',
                        'settings' => null
                    );

            $helps[] = array(
                        'title' => 'Settings',
                        'descr' => array( 'to manage all settings (events, tickets, prizes, paypal, contacts, users, etc.)' ),
                        'links' => page_rounter::url( 'settings_wp_raffle', false ),
                        'codes' => null,
                        'settings' => null
                    );

            $helps[] = array(
                        'title' => 'Tickets',
                        'descr' => array( 'to add or edit, delete on manager section', 'settings manager counts, actions, id, etc.' ),
                        'links' => page_rounter::url( 'add_tickets_wp_raffle', false ),
                        'codes' => null,
                        'settings' => page_rounter::url( 'settings_wp_raffle', array( 'setting' => 'tickets' ) )
                    );

            $helps[] = array(
                        'title' => 'Events',
                        'descr' => array( 'to add or edit, delete on manager section', 'settings manager counts, actions, id, etc.' ),
                        'links' => page_rounter::url( 'add_events_wp_raffle', false ),
                        'codes' => '['.__( $shortcode_slug, 'slug' ).'_shortcode_events]',
                        'settings' => page_rounter::url( 'settings_wp_raffle', array( 'setting' => 'events' ) )
                    );

            $helps[] = array(
                        'title' => 'Prizes',
                        'descr' => array( 'to add or edit, delete on manager section', 'settings manager counts, actions, id, etc.' ),
                        'links' => page_rounter::url( 'add_prizes_wp_raffle', false ),
                        'codes' => '['.__( $shortcode_slug, 'slug' ).'_shortcode_prizes]',
                        'settings' => page_rounter::url( 'settings_wp_raffle', array( 'setting' => 'prizes' ) )
                    );

            $helps[] = array(
                        'title' => 'Users',
                        'descr' => array( 'to add or edit, delete on manager section', 'settings manager counts, actions, id, etc.' ),
                        'links' => page_rounter::url( 'add_prizes_wp_raffle', false ),
                        'codes' => null,
                        'settings' => page_rounter::url( 'settings_wp_raffle', array( 'setting' => 'users' ) )
                    );

        ?>

        <div class='wp-raffle__help_wrap'>

            <?php
                foreach( $helps as $key => $help ) 
                {

                    $first_class = $key == 0 ? 'first-class' : null;
            ?>
                    <div class="wp-raffle__help_wrap_inner <?php _e( $first_class, 'class' ); ?>">

                        <?php
                            _e( $help['title'], 'title' );
                        ?>

                        <p>
                            <?php
                                _e( '- ' . $help['descr'][0], 'title' );
                            ?>
                            <a href='<?php _e( $help['links'], 'link' ); ?>'><?php _e( $help['links'], 'link_label' ); ?></a>
                            <?php if( isset( $help['codes'] ) AND 
                                ! empty( $help['codes'] ) ) : ?>
                                <code><?php _e(  $help['codes'], 'code' ) ?></code>
                            <?php endif; ?>
                        </p>

                        <?php if( ! is_null( $help['settings'] ) ) : ?>
                            <p>
                                <?php
                                    _e( '- ' . $help['descr'][1], 'title' );
                                ?>
                                <a href='<?php _e( $help['settings'], 'link' ); ?>'><?php _e( $help['settings'], 'link_label' ); ?></a>
                            </p>
                        <?php endif; ?>

                         
                    </div>
            <?php
                }
            ?>

        </div>

    </div>


</div>

