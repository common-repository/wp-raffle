<?php

$userdata = get_userdata( get_current_user_id() );

$tickets = unserialize( get_option( 'wp_raffle_setting_tickets' ) );
$events  = unserialize( get_option( 'wp_raffle_setting_events' ) );
$prizes  = unserialize( get_option( 'wp_raffle_setting_prizes' ) );
$users   = unserialize( get_option( 'wp_raffle_setting_users' ) );

?>

<div id="wp-raffle__wrap">

    <?php
        $user_role = user_control::get_role();
    ?>
    
    <h2 class="wp-raffle_manager-title">
        <?php 
            _e( 'WP Raffle Manager : ' . ucfirst ( $userdata->user_login ) . " ({$user_role})", 'wp-raffle' ); 
        ?>
        <?php
            _e( form::page_manager_head( '' ), 'wp-raffle' );
        ?>    
    </h2>
    
    <div class="ajaxs-results"></div>

    <?php
        $wp_raffle_settings = unserialize( settings::get_value()->boxies_sort );
    ?>

    <?php
    
        if( isset( $wp_raffle_settings ) ) :

        foreach( $wp_raffle_settings as $key => $value ) :

            switch ( $value ) :

            case 'tickets_value' : 
                if( isset( $tickets->hide_checkbox ) ) : 
                load::view( 'template/tickets-tpl' ); 
                endif;
            break;

            case 'events_value' : 
                if( isset( $events->hide_checkbox ) ) : 
                load::view( 'template/events-tpl' );
                endif;
            break;

            case 'prizes_value' : 
                if( isset( $prizes->hide_checkbox ) ) : 
                load::view( 'template/prizes-tpl' );
                endif;
            break;

            case 'users_value' : 
                if( isset( $users->hide_checkbox ) ) : 
                load::view( 'template/users-tpl' );
                endif;
            break;

            case 'accounts_value' : 
                load::view( 'template/accounts-tpl' ); 
            break;

            endswitch;
    ?>

    <?php endforeach; endif; ?>


</div>

