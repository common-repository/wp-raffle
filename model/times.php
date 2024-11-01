<?php if( !class_exists( 'times' ) ) 
{
    class times extends db
    {
        function __construct()
        {
            // scripts
        }
        
        public static function label()
        {
            $labels = array( 
                    'name' => 'Name',
                    'description' => 'Description',
                    'time' => 'Time', 
                    'action' => '<span class="randoms-button-start"></span>', 
                );
                
            return $labels;
        }
        
        public static function get_query_users ( $nums_1=null ) 
        {
            global $wpdb;    
            
            $ticket_id = $nums_1;
            
            $tbls = $wpdb->prefix . __( "raffle_user" ); 
            return $wpdb->get_results( "SELECT * FROM {$tbls} WHERE ticket_id={$ticket_id}", OBJECT );  
        }
        
        public static function get_query_ticket ( $ids=null ) 
        {
            global $wpdb;    
            
            $event_id = $ids;
            
            $tbls = $wpdb->prefix . __( "raffle_tickets" ); 
            return $wpdb->get_results( "SELECT * FROM {$tbls} WHERE event_id={$event_id}", OBJECT ); 
        }
        
        public static function querys_loop () 
        {
            global $wpdb;
            
            $html = null;
            $users_ticket_ids = array();
            
            $tbls = $wpdb->prefix . __( "raffle_events" ); 
            $results = $wpdb->get_results( "SELECT * FROM {$tbls} ", OBJECT );  
            
            if( self::label() ) 
            {
                $html .= '<div class="time-schedule__label">';    
                
                    foreach( self::label() as $keys => $labels ) 
                    {
                        $html .= "<div class='data time-schedule__label-{$keys}'>";  
                        if( $keys != 'action' ) {
                            $html .= "<label>{$labels}</label>";
                        } else {
                            $html .= "{$labels}";
                        }
                        $html .= '</div>';
                    } 
                    
                $html .= '<div class="clear"></div>';
                $html .= '</div>';       
            }
            
            if( $results ) 
            {
                
                $html .= '<div class="time-schedule__result-wrap">';    
                    
                    foreach( $results as $keys_value => $values ) 
                    {
                        
                        $ids = intval( $values->id );
                        
                        $html .= "<div class='time-schedule__result results_{$keys_value}'>";    
                        
                        $html .= "<div class='data time-schedule__result-name'>";  
                        $html .= "<label>{$values->name}</label>";
                        $html .= '</div>';
                        
                        $html .= "<div class='data time-schedule__result-description'>";  
                        $html .= "<label>{$values->text}</label>";
                        $html .= '</div>';
                        
                        $html .= "<div class='data time-schedule__result-time'>";  
                        $html .= "<label>{$values->time}</label>";
                        $html .= '</div>';
                        
                        $html .= "<div class='data time-schedule__result-action'>";  
                        
                        if( $values->time_set == 1 ) 
                        {
                            $msgs = 'Enabled';
                            $is_status = 'active';
                        } else {
                            $msgs = 'Disabled';
                            $is_status = 'not-active';
                        }
                        
                        $html .= "<label><span class='time_schedule_set-timer {$is_status}'>[{$values->time_set}]<input type='hidden' value='{$values->time_set}'></span> - {$msgs}</label>";
                        
                        $html .= self::set_timer( $values->time, $values->time_set, $ids );
                       
                        $html .= '</div>';
                        
                        $html .= '<div class="clear"></div>';
                        $html .= '</div>';
                        
                        $html .= "<div class='ticket_data__result results_{$keys_value}'>";
                        $query_ticket = self::get_query_ticket( $ids );
                        
                        foreach( $query_ticket as $query_tickets ) :
                            
                            $ticket_ids = $query_tickets->id;
                            $users_ticket_ids = self::get_query_users( $ticket_ids );
                            
                            $rand_keys = self::select_random( $users_ticket_ids, 1, true );

                            foreach( $users_ticket_ids as $users_ticket_ids_keys => $users_ticket_ids_vals ) :
                                
                                $randoms_class = $users_ticket_ids_vals->selected == 1 ? 'class="randoms_seleced"' : null;
                            
                                $html .= "<code {$randoms_class}>";
                                    $html .= $users_ticket_ids_keys.'-';
                                    $html .= $users_ticket_ids_vals->id.'-';
                                    $html .= $users_ticket_ids_vals->user_id.'-';
                                    $html .= $users_ticket_ids_vals->ticket_id.'-';
                                    $html .= $users_ticket_ids_vals->value.'-';
                                    $html .= $users_ticket_ids_vals->selected;
                                $html .= '</code>';
                                
                            endforeach;

                        endforeach;
                        
                        $html .= '</div>';
                        
                    } 
                    
                $html .= '<div class="clear"></div>';
                $html .= '</div>';    
            }
            
            return $html;
        }
        
        public static function set_timer ( $vals_1=null, $vals_2=null, $ids=null )
        {
            $html = null;
            
            $is_status = $vals_2 == 1 ? 'active' : 'not-active';
            
            $html .= '<div class="wp-raffle__set-timer_form">';
            $html .= "<input type='text' value='{$vals_1}'>";
            $html .= "<span class='time-set_schedule_submit {$is_status} loader'>";
            $html .= "<input type='hidden' value='{$ids}'>";
            $html .= "</span>";
            $html .= '</div>';
            
            return $html;
        }
        
        public static function select_random ( $ticket_id=null, $nums=0, $status=false ) 
        {
            global $wpdb;
            
            if( $status == false ) 
            {
                $querys = self::get_query_users( $ticket_id );    
            } else {
                $querys = $ticket_id;
            }

            if( !empty($querys ) ) 
            {
                $rand_keys = array_rand( $querys, $nums );    
            } else {
                $rand_keys = null;    
            }

            return ( $rand_keys );
            
        }
        
        public static function get_generated_ranoms_keys()
        {
            global $wpdb;
            
            $html = null;
            $users_ticket_ids = array();
            
            $tbls = $wpdb->prefix . __( "raffle_events" ); 
            $results = $wpdb->get_results( "SELECT * FROM {$tbls} ", OBJECT );  
            
            if( $results ) 
            {
                
                $html .= "<span class='generated-key'>";
                
                foreach( $results as $keys_value => $values ) 
                {
                    
                    $ids = intval( $values->id );
                    $query_ticket = self::get_query_ticket( $ids );
                    
                    foreach( $query_ticket as $query_tickets ) :
                        
                        $ticket_ids = $query_tickets->id;
                        $users_ticket_ids = self::get_query_users( $ticket_ids );
                        
                        $rand_keys = self::select_random( $users_ticket_ids, 1, true );

                        foreach( $users_ticket_ids as $users_ticket_ids_keys => $users_ticket_ids_vals ) :
                            
                            $randoms_class = $users_ticket_ids_vals->selected == 1 ? $users_ticket_ids_keys : null;
                            
                            if( !is_null( $randoms_class ) ) {
                                $html .= $users_ticket_ids_keys.'-';
                            }

                        endforeach;

                    endforeach;
                    
                }

            }
            
            $html .= 0;
            
            $html .= '</span>'; 
            
            return $html;
        }
        
    }
}
?>