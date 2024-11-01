<?php if( !class_exists( 'prizes' ) ) 
{
    class prizes extends db
    {
        public static $setting = 'prizes';
        var $table = null;
        
        public static function querys ( $slugs=null ) 
        { 
            global $wpdb;
            if( !is_null( $slugs ) ) 
            {
                $tbls = $wpdb->prefix . __( "raffle_{$slugs}" ); $results = $wpdb->get_results( "SELECT * FROM {$tbls} ", OBJECT );   
                if( isset( $results ) and is_array( $results ) ) return $results; 
            }
        } 
        
        public static function querys_filters ( $slugs=null, $names=null ) 
        { 
            global $wpdb;
            if( !is_null( $slugs ) && !is_null( $names ) ) 
            {
                $tbls = $wpdb->prefix . __( "raffle_{$slugs}" ); $results = $wpdb->get_results( "SELECT * FROM {$tbls} WHERE name='{$names}'", OBJECT );   
                if( isset( $results ) and is_array( $results ) ) return $results; 
            }
        }  
        
        public static function counts ( $slugs=null ) 
        {
            global $wpdb;
            $sets = unserialize( get_option( 'wp_raffle_setting_'.self::$setting ) );
            if( !is_null( $slugs ) AND isset( $sets->count_checkbox ) ) 
            {
                $tbls = $wpdb->prefix . __( "raffle_{$slugs}" );
                $results = $wpdb->get_var( "SELECT COUNT(*) FROM {$tbls}" ); 
                if( is_numeric( $results ) ) return "<span class='wp-raffle__counts'>{$results}</span>"; 
            } else {
                return null;
            }
        }
        
        public static function counts_where_id ( $slugs=null,$where='' ) 
        {
        global $wpdb;
            if( !is_null( $slugs ) ) 
            {
                $tbls = $wpdb->prefix . __( "raffle_{$slugs}" );
                $results = $wpdb->get_var( "SELECT COUNT(*) FROM {$tbls} {$where}" );
                if( is_numeric( $results ) ) return "<span class='wp-raffle__counts'>{$results}</span>"; 
            }
        }
        
        public static function loop () 
        {
            $html = null;
            $nums = array();
            $sets = unserialize( get_option( 'wp_raffle_setting_'.self::$setting ) );

            $user_role = user_control::get_role();
            $all_role = user_control::get_all_role();

            if( in_array( $user_role, $all_role ) ) 
            {
                $results = self::querys( 'prizes' ); 
            } else {
                // needed to be structured soon
                $results = array();
            }
            
            if( $results ) : 

            $html .= '<ul class="wp-raffle__prizes--list-wrap">';
            
            foreach( $results as $keys => $result ) :
                $nums[] = sanitize_title( $result->name ); 
            endforeach;
            
            $counts = array_count_values( $nums );
            
            $i = 1;
            foreach ( $results as $keys => $result ) :
                
                if ( $i % 2 == 1 ) {
                    $slug = 'even';
                } else {
                    $slug = 'odd';
                }
                
                $i_vals[] = $i; 
                $id = intval($result->id);
                
                $datas = self::querys_where( $result->event_id, $result->ticket_id );

                $html .= "<li class='wp-raffle__prizes--list-item item-{$i} item-{$slug}'>";
                
                // $html .= "<div class='prizes--id'>". __( $id, 'wp-raffle' )."</div>";
                $html .= "<div class='prizes--name'>" . __( ucfirst ( $result->name ), 'wp-raffle' );
                
                $events_name = $datas['event_querys'][0]->name;

                if( isset( $sets->et_checkbox ) ) :
                $html .= "<span class='prizes--events__name'>{$events_name}</span>";
                endif;

                $tickets_id = $datas['tickets_querys'][0]->id;
                $tickets_orders = $datas['tickets_querys'][0]->orders;
                
                if( isset( $sets->toc_checkbox ) ) :
                $html .= "<span class='prizes--tickets__orders'>T({$tickets_id}) : {$tickets_orders}</span>";
                endif;

                if( isset( $sets->actns_checkbox ) ) :

                $html .= "<span class='prizes--actions'>";

                $edit_link = page_rounter::url( 'add_prizes_wp_raffle', array( 'edit_prizes' => $id ) );
                $delete_link = page_rounter::url( 'wp_raffle', array( 'delete_prizes' => $id ) );

                $html .= "<a href='".__( $edit_link, 'wp-raffle' )."' class='wp-raffle_edit-link'></a>";
                $html .= "<a href='".__( $delete_link, 'wp-raffle' )."' class='wp-raffle_delete-link'></a>";

                $html .= "</span>";

                endif;
                
                $html .= "</div>";
                
                $html .= "<div class='prizes--text'>" . __( ucfirst ( $result->text ), 'wp-raffle' ) . "</div>";
                $html .= "</li>"; 
                
                $i++; 
                
            endforeach;
            
            $html .= "</ul>";
            
            return $html;
            
            endif;
        }
        
        public static function querys_where( $event_id=null, $ticket_id=null )
        {
            global $wpdb;
            
            $events_tbls = $wpdb->prefix . __( "raffle_events" );
            if( !is_null( $event_id ) ) {
                $events_results = $wpdb->get_results( "SELECT * FROM {$events_tbls} WHERE id={$event_id}", OBJECT );
            }

            $prizes_tbls = $wpdb->prefix . __( "raffle_tickets" );
            if( !is_null( $ticket_id ) ) {
                $prizes_results = $wpdb->get_results( "SELECT * FROM {$prizes_tbls} WHERE id={$ticket_id}", OBJECT );
            }
            
            $datas = array( 
                'event_querys' => $events_results,
                'tickets_querys' => $prizes_results,
            );
            
            return $datas;
        }
        
        public static function query_loops () 
        {
            global $wpdb;
            
            $tbls = 'prizes';
            
            $results = self::querys( 'prizes' ); 

            if( count( $results ) >= 0 ) 
            {   
                $html .= '<div class="wp_raffle_prizes-loop_wrap">';
                
                foreach ( $results as $keys => $result ) :
                    
                    $is_first = $keys == 0 ? 'index_1' : null;
                    
                    $html .= '<div class="wp_raffle_prizes-inner_wrap '.__( $is_first, 'wp_raffle' ).'">';
                    
                    $html .= '<div class="wp_raffle_prizes-name">';
                        $html .= __( ucfirst( strtolower( $result->name ) ), 'wp_raffle' );
                    $html .= '</div>';
                    
                    $html .= '<div class="wp_raffle_prizes-text">';
                        $html .= __( $result->text, 'wp_raffle' );
                    $html .= '</div>';
                    
                    $html .= '<div class="wp_raffle_prizes-action">';
                    
                        $datas = self::querys_where( $result->event_id, $result->ticket_id );
                        
                        $events_name = $datas['event_querys'][0]->name;
                        
                        $html .= "<span class='wp_raffle_prizes--event_name'>{$events_name}</span>";
                        
                        $tickets_id = $datas['tickets_querys'][0]->id;
                        $tickets_orders = $datas['tickets_querys'][0]->orders;
                        
                        $html .= "<span class='prizes--tickets_orders'>T({$tickets_id}) : {$tickets_orders}</span>";
                    
                    $html .= '</div>';
                    
                    $html .= '<div class="clear_both"></div>';
                    
                    $html .= '</div>';
                    
                endforeach;
                
                $html .= '</div>';
                
            }
            
            return $html;
            
        }

        public static function get_querys_rows ( $id=null )
        {
            global $wpdb;

            $tbls = $wpdb->prefix . __( "raffle_prizes" );
            return $wpdb->get_row ( "SELECT * FROM {$tbls} WHERE id='{$id}'", OBJECT );   
        }

        // END
    }
}  