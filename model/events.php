<?php if( ! class_exists( 'events' ) or die ( 'error found.' ) ) 
{
    class events extends db
    {
        function __construct()
        {
            // scripts
        }

        public static function get_querys ()
        {
            global $wpdb;

            $tbls = $wpdb->prefix . __( "raffle_events" ); 
            return $wpdb->get_results ( "SELECT * FROM {$tbls}", OBJECT );   
        }  

        public static function get_querys_inner_selects ()
        {
            global $wpdb;

            $querys = self::get_querys();
            $results = array();

            if ( ! empty ( $querys ) ) 
            {
                foreach( $querys as $keys => $values ) 
                {
                    $results[$values->id] = __( $values->name, 'wp-raffle' );    
                }
            }

            if( ! empty ( $results ) ) 
            {
                return ( $results );   
            }
        }    
        
        public static function query_loops ()
        {
            global $wpdb;
            
            $tbls = $wpdb->prefix . __( "raffle_events" ); 
            $results = $wpdb->get_results( "SELECT * FROM {$tbls} ", OBJECT );   

            if(  count( $results ) >= 0 ) 
            {
                $html .= '<div class="wp_raffle_events-loop_wrap">';
                
                foreach( $results as $keys => $result ) :
                    
                    $is_first = $keys == 0 ? 'index_1' : null;
                    
                    $html .= '<div class="wp_raffle_events-inner_wrap '.__( $is_first, 'wp_raffle' ).'">';
                    
                        $html .= '<div class="wp_raffle_events-name">';
                        $html .= __( $result->name, 'wp_raffle' );
                        $html .= '</div>';
                        
                        $html .= '<div class="wp_raffle_events-text">';
                        $html .= __( $result->text, 'wp_raffle' );
                        $html .= '</div>';
                        
                        $html .= '<div class="wp_raffle_events-tickets">';
                        
                            $html .= '<span>';
                            $html .= self::counts_where_id( $result->id );
                            $html .= '</span>';
                            
                        $html .= '</div>';
                        
                        $html .= '<div class="clear_both"></div>';
                        
                    $html .= '</div>';
                    
                endforeach; 

                $html .= '</div>';
            }
            
            return $html;
        }
        
        public static function counts_where_id ( $event_id=null ) 
        {
            global $wpdb;
            if( !is_null( $event_id ) ) 
            {
                $tbls = $wpdb->prefix . __( "raffle_tickets" );
                $results = $wpdb->get_var( "SELECT COUNT(*) FROM {$tbls} WHERE event_id={$event_id}" );
                
                if( is_numeric( $results ) ) return $results; 
            }
        } 

        public static function get_querys_rows ( $id=null )
        {
            global $wpdb;

            $tbls = $wpdb->prefix . __( "raffle_events" ); 
            return $wpdb->get_row ( "SELECT * FROM {$tbls} WHERE id='{$id}'", OBJECT );   
        }       
    }
}
?>