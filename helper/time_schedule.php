<?php if( !class_exists( 'schedule' ) )
{
    class schedule
    {
        public static function selected_randoms ( $ticket_id=null, $nums=0, $status=false ) 
        {
            global $wpdb;
            
            if( $status == false ) 
            {
                $querys = self::get_query_users( $ticket_id );    
            } else {
                $querys = $ticket_id;
            }
            
            $rand_keys = array_rand( $querys, $nums );
            
            return ( $rand_keys );
            
        }
        
        public static function timer_counter ( $value=null )
        {
             $now =  date( 'Y-m-d' );
             $current = new DateTime( $now );  
             
             $date = new DateTime( self::get_time_set( $value, 0 ) ); 

             $diff = $date->diff( $current )->format( '%a' ); 
             $days = intval( $diff );
             
             return $days;
        }
        
        public static function get_time_set( $value=null, $offet=null )
        {
             $date_vals = explode( ' ', $value );
             if( !is_null($offet)) 
             {
                return trim( $date_vals[$offet] );
             }
        }
        
        public static function get_querys_users ( $ids=null ) 
        {
            global $wpdb;    
            
            $ticket_id = $ids;
            
            $tbls = $wpdb->prefix . __( "raffle_user" ); 
            return $wpdb->get_results( "SELECT * FROM {$tbls} WHERE ticket_id={$ticket_id}", OBJECT );  
        }
        
        public static function get_querys_tickets ( $ids=null ) 
        {
            global $wpdb;    
            
            $event_id = $ids;
            
            $tbls = $wpdb->prefix . __( "raffle_tickets" ); 
            return $wpdb->get_results( "SELECT * FROM {$tbls} WHERE event_id={$event_id}", OBJECT ); 
        }
        
        public static function get_current_time( $ints=1, $is_status=false )
        {
            global $wpdb;
            $tbls = $wpdb->prefix.'raffle_events';
            
            if( $is_status == false ) 
            {
                $is_wheres = null;
            } else {
                $is_wheres = "WHERE time_set={$ints}";
            }
            
            return $wpdb->get_results( "SELECT * FROM {$tbls} {$is_wheres}", OBJECT );
        }
        
        public static function time_calculate()
        {
            global $wpdb;
            
            $html = null;
            
            $querys = self::get_current_time( 1, true );
            
            if( count( $querys ) >= 0  ) 
            {
                
                $html .= '<div class="wp_raffle_time-loop_wrap">';
                
                foreach ( $querys as $keys => $results ) 
                {
                    
                    $is_index = $keys == 0 ? 'index' : null; 
                    
                    $html .= "<div class='wp_raffle_time-inner_wrap {$is_index}'>";
                    
                        $html .= "<div class='wp_raffle_time-name'>{$results->name}</div>";
                        
                        $dater = self::get_time_set( $results->time, 0 );
                        $html .= "<div class='wp_raffle_time-schedule'>{$dater}</div>"; 
                        
                        $counter = self::timer_counter( $results->time );
                        $timer = self::get_time_set( $results->time, 1 );
                        
                        $html .= "<div class='wp_raffle_time-remaining'>
                                      <span>{$counter}</span>
                                      <span>{$timer}</span>
                                  </div>"; 
                             
                    $html .= '</div>';

                }
                
                $html .= '</div>';

            }
            
            return $html;
            
        }
        
        public static function random_selected () 
        {
            global $wpdb;
            
            $html = null;
            
            $querys = self::get_current_time( 0, false );
            
            if( count( $querys ) >= 0  ) 
            {
                $html .= "<div class='wp_raffle-randoms_selected'>";
                
                foreach( $querys as $querys_keys => $querys_vals ) :
                
                    $ids = intval( $querys_vals->id );
                    $querys_tickets = self::get_querys_tickets( $ids );
                    
                    foreach( $querys_tickets as $querys_tickets_vals ) 
                    {
                        $ticket_ids = $querys_tickets_vals->id;
                        $users_ticket_ids = self::get_querys_users( $ticket_ids );
                        
                        foreach( $users_ticket_ids as $users_ticket_ids_keys => $users_ticket_ids_vals ) :
                                
                                $randoms_class = $users_ticket_ids_vals->selected == 1 ? 'class="randoms_seleced"' : null;
                                
                                if( !is_null( $randoms_class ) ) 
                                {
                                    $html .= "<span {$randoms_class}>";
                                    $html .= $users_ticket_ids_keys.'-';
                                    $html .= $users_ticket_ids_vals->id.'-';
                                    $html .= $users_ticket_ids_vals->user_id.'-';
                                    $html .= $users_ticket_ids_vals->ticket_id.'-';
                                    $html .= $users_ticket_ids_vals->value.'-';
                                    $html .= $users_ticket_ids_vals->selected;
                                    $html .= '</span>';    
                                }
                                   
                        endforeach;

                    }
                
                endforeach; 
                
                $html .= '</div>';  
                
                return $html;
            }
            
        }
    }
}
?>