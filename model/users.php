<?php if( !class_exists( 'users' ) ) 
{
     class users extends db
     {
          public static $setting = 'users';
          var $table = null;
          
          public static function querys () 
          {
               global $wpdb;
               $results = $wpdb->get_results( "SELECT * FROM {$wpdb->users} ", OBJECT );   
               if( isset( $results ) and is_array( $results ) ) return $results;
          }
          
          public static function counts () 
          {
              global $wpdb;
              $settings = unserialize( get_option( 'wp_raffle_setting_'.self::$setting ) );
              if( isset( $settings->count_checkbox ) ) {
                  $results = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->users" );
                  if( is_numeric( $results ) ) return "<span class='wp-raffle__counts'>{$results}</span>";
              } else {
                  return null;
              }
          }
          
          public static function users_get_all_data_by_user_id ( $ids=null ) 
          {
              global $wpdb;
                
              $users   = $wpdb->prefix . __( "raffle_user" );
              $tickets = $wpdb->prefix . __( "raffle_tickets" );
              $events  = $wpdb->prefix . __( "raffle_events" );
              
              if( !is_null( $ids ) ) 
              {
                  return $wpdb->get_results( "SELECT * FROM {$tickets} as tickets INNER JOIN {$users} as users ON tickets.id = users.ticket_id INNER JOIN {$events} as events ON tickets.event_id = events.id WHERE users.user_id = {$ids}" ); 
              }   
          }
          
          public static function user_get_event_counts_by_user_id( $ids=null ) 
          {
              global $wpdb;
              
              $querys = self::users_get_all_data_by_user_id( $ids );   
              $ids_val= array();

              foreach( $querys as $query ) 
              {
                  $ids_val[] = ( $query->id );                
              }
              
              return count( array_count_values( $ids_val ) );
          }
          
          public static function users_get_prize_data_by_user_id ( $ids=null ) 
          {
              global $wpdb;
                
              $users   = $wpdb->prefix . __( "raffle_user" );
              $tickets = $wpdb->prefix . __( "raffle_tickets" );
              $prizes  = $wpdb->prefix . __( "raffle_prizes" );
              
              if( !is_null( $ids ) ) 
              {
                  $querys = $wpdb->get_results( "SELECT * FROM {$users} as users INNER JOIN {$tickets} as tickets ON users.ticket_id = tickets.id WHERE users.user_id = {$ids}" ); 
              }   
              
              return $querys;
          }
          
          public static function users_get_prize_by_event_id ( $ids=null ) 
          {
              global $wpdb;
              
              $prizes = $wpdb->prefix . __( "raffle_prizes" );  
              
              if( !is_null( $ids ) ) 
              {
                  $querys = $wpdb->get_row( "SELECT * FROM {$prizes} as prizes WHERE prizes.event_id = {$ids}" ); 
              }  
              
              return $querys;
          }
     
          public static function loop () 
          {
              $html =null;
              $settings = unserialize( get_option( 'wp_raffle_setting_'.self::$setting ) );
              
              $results = self::querys(); 
              if( $results ) : 
                
              $html .= '<ul class="wp-raffle__users--list-wrap">';
                
              foreach( $results as $keys => $result ) :
              
                       $ids = $result->ID; 
                       $ticket_counts = self::user_counts_tickets( $ids );

                       $get_user_id = user_control::get_id();
                       $is_user_active = ( $get_user_id ) != $ids ? null : 'user_is_active';

                       $html .= "<li class='wp-raffle__users--list-item item-{$keys} {$is_user_active}'>";
                       
                       if( isset( $settings->id_checkbox ) ) :
                       $html .= '<div class="users--id">' . __( $ids, 'wp-raffle ') . '</div>';
                       endif;

                       $event_counts = self::user_get_event_counts_by_user_id( $ids );

                       $html .= "<div class='users--name'>" . __( ucfirst ( $result->user_login ), 'wp-raffle' ) . "<span class='users--email'>" . __( $result->user_email, 'wp-raffle' ) . "</span></div>";

                       if( isset( $settings->us_checkbox ) ) :
                       $html .= "<div class='users--action'>" . __( form::section_action( array( array( 'users', 'events', $event_counts ), array( 'users', 'tickets', $ticket_counts ) ) ), 'wp-raffle' ) . "</div>";
                       endif;

                       if( isset( $settings->actns_checkbox ) ) :
                       $html .= '<div class="users--actions">';

                       $edit_link = page_rounter::admin_user_url( $ids );
                       $delete_link = page_rounter::admin_user_url_actions( $ids );
                       
                       $html .= "<a href='".__( $edit_link, 'wp-raffle' )."' class='wp-raffle_edit-link'></a>";
                       $html .= "<a href='".__( $delete_link, 'wp-raffle' )."' class='wp-raffle_delete-link'></a>";

                       $html .= '</div>';
                       endif;

                       $html .= "</li>";
                      
              endforeach;
                     
              $html .= "</ul>";
                
              return $html;
                
              endif;
          }
          
          public static function tab1_accounts ( $userdata=null ) 
          {
              $html = null;
              
              if( is_object( $userdata ) and !is_null( $userdata ) ) :
               
                  $userdatas = array( 'user_login'      => array( 'Name', $userdata->user_login ),
                                      'user_email'      => array( 'Email', $userdata->user_email ),
                                      'roles'           => array( 'Role', $userdata->roles[0] ),
                                      'user_registered' => array( 'Time', $userdata->user_registered ) );
                                        
                  foreach( $userdatas as $keys => $users ) :
                        $html .= "<div class='wp-raffle__accounts--data'><span class='wp-raffle__accounts--data-label'>{$users[0]}</span>{$users[1]}</div>";
                  endforeach;
                  
                  $html .= "<div class='wp-raffle__accounts--data'>";
                  
                  $html .= "<span class='wp-raffle__accounts--data-label'>";
                  $html .= __( 'Total:', 'wp_raffle' );
                  $html .= "</span>";
                  
                  $total = self::total_purchase_cost_amount( $userdata );
                  $path  = page_rounter::url( 'settings_wp_raffle', array( 'setting' => 'paypal-validate' ) );
                  $price = '<a href="' . __( $path ) . '" class="settings_links button" id="settings_links">' . __( money_format::cash( $total, 0 ), 'wp-raffle money_format' ). '</a>';

                  $html .= __( $price, 'wp_raffle' );
                  
                  $html .= "</div>";
              
              endif;
               
              return $html;
          }
          
          public static function total_purchase_cost_amount( $userdata=null ) 
          {
              if( is_object( $userdata ) and ! is_null( $userdata ) ) :
                  
                  $ids = $userdata->ID; 
                  
                  $querys = self::user_get_data( $ids );
                  
                  foreach( $querys as $query ) 
                  {
                       $ticket_id = $query->ticket_id;
                       $value = $query->value;

                       $total = tickets::get_total_prices( 'tickets', $ticket_id, $value );
                       $cost += $total[0];
                  }
                  
                  return ( $cost );
                  
              endif;           
          }
          
          public static function tab2_tickets ( $userdata=null ) 
          {
              if( is_object( $userdata ) and !is_null( $userdata ) ) :
                  
                  $ids = $userdata->ID;

                  $querys = self::user_get_data( $ids );
                  
                  foreach( $querys as $query ) 
                  {
                       $ticket_id = $query->ticket_id;

                       $name  = $query->name;
                       $value = $query->value;
                       
                       $html .= "<div class='wp-raffle__accounts--data tickets--data'>";
                       $total = tickets::get_total_prices( 'tickets', $ticket_id, $value );
                       $html .= "<span class='wp-raffle__accounts--data-label'>({$ticket_id})</span>{$value} - <span class=''wp-raffle__accounts--data-label'>{$total[0]}</span></div>";  
                  }

                  return $html;
                  
              endif;
          }

          public static function tab3_events ( $userdata=null ) 
          {
              global $wpdb;
              
              $html = null;
                
              if( is_object( $userdata ) and !is_null( $userdata ) ) :
                  
                  $ids = $userdata->ID;

                  $querys = self::users_get_all_data_by_user_id( $ids );

                  foreach( $querys as $datas ) {
                        $html .= "<div class='wp-raffle__accounts--data tickets--data'>";
                        
                        $name = $datas->name;
                        $html .= "<span class='wp-raffle__accounts--data-label events--label'>{$name}</span>";
                        
                        $ticket_id = $datas->ticket_id;
                        $values = $datas->value;
                        
                        $html .= "<span class='wp-raffle__accounts--data-label events--label'>{$ticket_id} <span class='wp-raffle__counts'>{$values}</span></span>";

                        $html .= "</div>";     
                  }  
                  
                  return $html;
                  
              endif;
          }
          
          public static function tab4_events ( $userdata=null ) 
          {
              global $wpdb;
              
              $html = null;
                
              if( is_object( $userdata ) and !is_null( $userdata ) ) :
                  
                  $ids = $userdata->ID;
                  
                  $querys = self::users_get_prize_data_by_user_id( $ids );
                  
                  foreach( $querys as $datas ) {
                        $html .= "<div class='wp-raffle__accounts--data tickets--data'>";
                        
                        $get_prizes = self::users_get_prize_by_event_id( $datas->event_id );

                        $name = strtoupper( $get_prizes->name );
                        $html .= "<span class='wp-raffle__accounts--data-label events--label'>{$name}</span>";
                        
                        $ticket_id = $datas->ticket_id;
                        $values = $datas->value;
                        
                        $html .= "<span class='wp-raffle__accounts--data-label events--label'>{$ticket_id} <span class='wp-raffle__counts'>{$values}</span></span>";

                        $html .= "</div>";     
                  }  
                  
                  return $html;
                  
              endif;
          }
     }

}  