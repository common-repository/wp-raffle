<?php if( !class_exists( 'tickets' ) ) 
{
    class tickets extends db
    {
    
        public static $setting = array( 'tickets', 'events' );

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
            $settings = unserialize( get_option( 'wp_raffle_setting_'.$slugs ) );
            if( ! is_null( $slugs ) AND isset( $settings->count_checkbox ) ) 
            {
                $tbls = $wpdb->prefix . __( "raffle_{$slugs}" );
                $results = $wpdb->get_var( "SELECT COUNT(*) FROM {$tbls}" ); 
                if( is_numeric( $results ) ) return "<span class='wp-raffle__counts'>{$results}</span>"; 
            } else {
                return null;
            }
        }
        
        public static function counts_where_id ( $slugs=null, $where='' ) 
        {
            global $wpdb;
            if( !is_null( $slugs ) ) 
            {
                $tbls = $wpdb->prefix . __( "raffle_{$slugs}" );
                $results = $wpdb->get_var( "SELECT COUNT(*) FROM {$tbls} {$where}" );
                if( is_numeric( $results ) ) return "<span class='wp-raffle__counts'>{$results}</span>"; 
            }
        }
        
        public static function get_total_prices ( $slugs=null, $ids=null, $value=null ) 
        {
            global $wpdb;
            
            $tbls = $wpdb->prefix . __( "raffle_{$slugs}" );
            $results = $wpdb->get_results( "SELECT price FROM {$tbls} WHERE id={$ids}", OBJECT );
            foreach( $results as $result ) 
            {
                $total[] = $result->price * intval( $value );
            } 

            return $total;
        }
        
        public static function loop () 
        {
            $html = null;
            $nums = array();
            $settings = unserialize( get_option( 'wp_raffle_setting_'.self::$setting[0] ) );

            $user_role = user_control::get_role();
            $all_role  = user_control::get_all_role();
            $is_active = user_control::is_active();

            if( in_array( $user_role, $all_role ) ) 
            {
                $results = self::querys( 'tickets '); 
            } else {
                // needed to be structured soon
                $results = array();
            }
            
            if( $results ) : 

            $html .= '<ul class="wp-raffle__tickets--list-wrap">';
            
            foreach( $results as $keys => $result ) :
                $nums[] = sanitize_title( $result->name ); 
            endforeach;
            
            $counts = array_count_values( $nums );
            
            $i = 1;
            foreach ( $results as $keys => $result ) :
            
                $i_vals[] = $i; 
                $id = intval($result->id);
                 
                $price = self::price($result->price,false);

                $html .= "<li class='wp-raffle__tickets--list-item item-{$i}'>";

                if( isset( $settings->id_checkbox ) AND in_array( $user_role, $is_active ) ) :
                $html .= "<div class='tickets--id'>". __( $id, 'wp-raffle' )."</div>";
                endif;

                $html .= "<div class='tickets--name'>" . __( ucfirst ( $result->name ), 'wp-raffle' ) . "</div>";
                $html .= "<div class='tickets--price'>" . __( $price, 'wp-raffle' ) . "</div>";

                if( isset( $settings->rq_checkbox ) ) :
                $html .= "<div class='tickets--counts'><span class='wp-raffle__counts'>" . __( $result->qty, 'wp-raffle' ) . "</span></div>";
                endif;

                if( isset( $settings->ords_checkbox ) ) :
                $html .= "<div class='tickets--selected'>";
                $html .= "<span class='wp-raffle__selected loader'>";
                $html .= "<input type='hidden' value='{$id}' class='tickets_id--vals'/>";
                $html .= "</span>";
                $html .= "<span class='wp-raffle__orders'>".$result->orders."</span>";
                $html .= "</div>";
                endif;

                if( isset( $settings->actns_checkbox ) AND in_array( $user_role, $is_active ) ) :
                $html .= "<div class='tickets--functions'>";
                
                $edit_link = page_rounter::url( 'add_tickets_wp_raffle', array( 'edit_tickets' => $id ) );
                $delete_link = page_rounter::url( 'wp_raffle', array( 'delete_tickets' => $id ) );
               
                $html .= "<a href='".__( $edit_link, 'wp-raffle' )."' class='wp-raffle_edit-link'></a>";
                $html .= "<a href='".__( $delete_link, 'wp-raffle' )."' class='wp-raffle_delete-link'></a>";
                $html .= "</div>";
                endif;

                $html .= "</li>";
                
                $i++; 
            endforeach;
            
            $html .= "</ul>";
            
            return $html;
            
            endif;
        }
        
        /**
        * Events Section Areas
        * Functions, Scripts 
        **/
        
        public static function events_get_tickets_status ($ids=null) 
        {
            global $wpdb;
            
            $tbls = $wpdb->prefix . __( "raffle_tickets" );
            
            if( !is_null( $ids ) ) {
                $results = $wpdb->get_results( "SELECT name, qty FROM {$tbls} WHERE event_id={$ids}", OBJECT );    
            }
            
            return $results;
        }
        
        public static function events_loop () 
        {
            $html =null;  
            $settings = unserialize( get_option( 'wp_raffle_setting_'.self::$setting[1] ) );

            $results = self::querys( 'events '); 
            if( $results ) : 
            
            $html .= '<ul class="wp-raffle__events--list-wrap">';
            
            foreach ( $results as $keys => $result ):
            
                $event_id = intval($result->id);
                
                $html .= "<li class='wp-raffle__events--list-item item-{$keys}'>";

                if( isset( $settings->id_checkbox ) ) :
                $html .= "<div class='events--id'>" . __( $event_id, 'wp-raffle' ) . "</div>";
                endif;

                $html .= "<div class='events--name'>" . __( ucfirst ( $result->name ), 'wp-raffle' ) . "</div>";
                // $html .= "<div class='events--descr'>" . __( ucfirst ( $result->text ), 'wp-raffle' ) . "</div>";

                if( isset( $settings->ct_checkbox ) ) :
                $html .= "<div class='events--action'>";
                $html .= self::counts_where_id('tickets',"WHERE event_id={$event_id}");
                $html .= "</div>";
                endif;

                $html .= "<div class='events--status'>";
                
                $qtys = self::events_get_tickets_status( $event_id );
                
                if( isset( $settings->rq_checkbox ) ) :

                if( $qtys ) {
                    foreach( $qtys as $qtyl ) {
                        $name = $qtyl->name;
                        $counts = $qtyl->qty;
                        
                        if( $counts != 0 ) {
                            $html .= "<span class='events--status_data'>{$name} : {$counts}</span>";
                        } else {
                            $html .= "<span class='events--status_data'>Sold</span>";
                        }
                    }
                }

                endif;
                
                $html .= "</div>";

                if( isset( $settings->actns_checkbox ) ) :
                $html .= "<div class='events--actions'>";

                $edit_link = page_rounter::url( 'add_events_wp_raffle', array( 'edit_events' => $event_id ) );
                $delete_link = page_rounter::url( 'wp_raffle', array( 'delete_events' => $event_id ) );

                $html .= "<a href='".__( $edit_link, 'wp-raffle' )."' class='wp-raffle_edit-link'></a>";
                $html .= "<a href='".__( $delete_link, 'wp-raffle' )."' class='wp-raffle_delete-link'></a>";
                
                $html .= "</div>";
                endif;

                $html .= "</li>";
            
            endforeach;
            
            $html .= "</ul>";
            
            return $html;
            
            endif; 
        }
        
        public static function price($str=null,$status=true)
        {
            setlocale( LC_MONETARY, 'en_US' );
            
            if($status == true)
            {
                $price = money_format( '%i',$str );  
            } else {
                $price = $str;
            }
            
            return $price;
        }

        public static function get_querys_rows ( $id=null )
        {
            global $wpdb;

            $tbls = $wpdb->prefix . __( "raffle_tickets" ); 
            return $wpdb->get_row ( "SELECT * FROM {$tbls} WHERE id='{$id}'", OBJECT );   
        }

        public static function get_querys_inner_selects ()
        {
            global $wpdb;

            $querys = self::querys( 'tickets' );
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
    }
}  