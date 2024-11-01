<?php if( !class_exists( 'db' ) ) {
    
    class db 
    {
    
        public function __construct() 
        {
            parent::__construct();
        }
        
        /**
        * wpdb query function 
        * @param name (string)
        * @param keyword (true or false)
        * @param where (string)
        * @param sort (true or false)
        */ 
        
        public static function query( $tbl=null, $is_get=true, $is_where='', $is_sort=true ) 
        {
            global $wpdb;
            
            if( !is_null( $tbl ) ) 
            {
                $tbl_val = $wpdb->prefix . $tbl;
                $tbl_active = true;
            } else {
                $tbl_val = $wpdb->prefix;
                $tbl_active = false;
            }
        
            $is_sort_val = $is_sort == true ? "ORDER BY `sort` ASC" : $sort = '';
            $is_where_val = is_string( $is_where ) ? $is_where : '';
        
            if( $tbl_active == true ) 
            { 
            
                if( $is_get == true ) 
                {
                    $sql = $wpdb->get_results("SELECT * FROM $tbl_val $is_where_val $is_sort_val");
                } else {
                    if( $is_get == false ) 
                    {
                        $sql = $wpdb->get_row("SELECT * FROM $tbl_val $is_where_val");
                    }
                } 
            
            } 
        
            if( is_array( $sql ) or is_object( $sql ) )
            {
                return $sql;
            } 
        
        }
        
        public static function selects ( $tbls=null ) 
        {
            if( !is_null( $tbls ) ) return $wpdb->get_results( "SELECT * FROM {$wpdb->users}", OBJECT );  
        } 
        
        public static function counts ( $tbls=null ) 
        {
            if( !is_null( $tbls ) ) return $wpdb->get_var( "SELECT COUNT(*) FROM {$tbls}" );
        }
        
        /**
        * Users Querys
        **/
        
        public static function user_counts_tickets ( $ids=null ) 
        {
            global $wpdb;
            
            $tbls = $wpdb->prefix.'raffle_user';
            
            $querys = $wpdb->get_results( "SELECT value FROM {$tbls} WHERE user_id={$ids}", OBJECT );
            
            $tls = 0;
            foreach( $querys as $query ) 
            {
                $tls += $query->value;
            }
            
            return  $tls; 
            
        }

        public static function user_id_exists ( $lbls=null, $ids=null ) 
        {
            global $wpdb;
            
            $tbls = $wpdb->prefix.'raffle_user';
            
            $counts = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$tbls} WHERE {$lbls} = %d", $ids ) );

            if( $counts ) { 
                return true; 
            } else { 
                return false; 
            }
        
        }
        
        public static function user_id_exists_filter ( $user_id=null, $ticket_id=null ) 
        {
            global $wpdb;
            
            $tbls = $wpdb->prefix.'raffle_user';
            
            $counts = $wpdb->get_var( "SELECT COUNT(*) FROM {$tbls} WHERE user_id={$user_id} AND ticket_id={$ticket_id}" );

            if( $counts ) 
            { 
                return true; 
            } else { 
                return false; 
            }
        
        }
        
        public static function user_get_values ( $lbls=null, $fields=null, $ids=null )
        {
            global $wpdb;
            
            $tbls = $wpdb->prefix.'raffle_user';
            
            if( !is_null( $lbls ) ) 
            {
                return $wpdb->get_var( "SELECT {$lbls} FROM {$tbls} WHERE {$fields}={$ids}" ); 
            }
        }
        
        public static function user_get_values_filter ( $lbls=null, $user_id=null, $ticket_id=null )
        {
            global $wpdb;
            
            $tbls = $wpdb->prefix.'raffle_user';
            
            if( !is_null( $lbls ) ) 
            {
                return $wpdb->get_var( "SELECT {$lbls} FROM {$tbls} WHERE user_id={$user_id} AND ticket_id={$ticket_id}" ); 
            }
        }
        
        public static function user_get_data ( $ids=null )
        {
            global $wpdb;
            
            $tbls = $wpdb->prefix.'raffle_user';
            
            $querys = $wpdb->get_results( "SELECT ticket_id, value FROM {$tbls} WHERE user_id={$ids}", OBJECT );
            
            return $querys;
        }
        
        /**
        * Tickets Querys
        **/
        
        public static function ticket_get_values( $lbls=null, $ids=null ) 
        {
            global $wpdb;
            
            $tbls = $wpdb->prefix.'raffle_tickets';
            
            if( !is_null( $lbls ) ) 
            {
                return $wpdb->get_var( "SELECT {$lbls} FROM {$tbls} WHERE id={$ids}" ); 
            }
        }
        
        /**
        * Timer Querys
        **/
        
        public static function timer_get_data( $choice=1 )
        {
            global $wpdb;
            
            $html = null;
            $users_keys = array();
            
            $tbls_1 = $wpdb->prefix . __( "raffle_events" ); 
            $tbls_2 = $wpdb->prefix . __( "raffle_tickets" ); 
            $tbls_3 = $wpdb->prefix . __( "raffle_user" ); 
            
            $results = $wpdb->get_results( "SELECT * FROM {$tbls_1} ", OBJECT );  
            
            if( $results ) 
            {
                foreach( $results as $results_vals ) 
                {

                    $event_ids = $results_vals->id;
                    $events_results = $wpdb->get_results( "SELECT * FROM {$tbls_2} WHERE event_id={$event_ids}", OBJECT ); 
                    
                    foreach( $events_results as $events_results_vals ) :
                    
                        $ticket_ids = $events_results_vals->id;
                        $users_results = $wpdb->get_results( "SELECT * FROM {$tbls_3} WHERE ticket_id={$ticket_ids}", OBJECT );  
                        
                        $users_keys = $users_results;
                        $rand_keys = array_rand( $users_results, 1 );
                        
                        $html .= $rand_keys . ', ';
                        
                        foreach( $users_results as $users_results_keys => $users_results_vals ) :
                            
                            $keys_validate = $rand_keys == $users_results_keys ? $users_results_vals : null;
                            
                            if( !is_null( $keys_validate ) ) {
                                
                                $user_ids = $keys_validate->id;

                                $wpdb->update( 
                                    $tbls_3,
                                    array( 'selected' => 1 ),
                                    array( 'id' => intval( $user_ids ) ),
                                    array( '%d' ),
                                    array( '%d' ) 
                                );

                            } else {
                                
                                $user_ids = $users_results_vals->id;
                                
                                $wpdb->update( 
                                    $tbls_3,
                                    array( 'selected' => 0 ),
                                    array( 'id' => intval( $user_ids ) ),
                                    array( '%d' ),
                                    array( '%d' ) 
                                );
                                
                            }
                            
                        endforeach;

                    endforeach;
                    
                }    
            }
            
            if( $choice != 1 ) 
            {
                return $users_keys;
            } else {
                
                return $html;
            }
        }
        
    }     
}