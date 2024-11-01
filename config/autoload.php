<?php if( !class_exists( 'wp_raffle_page' ) ) 
{    
    class wp_raffle_page extends config
    {
        private static $instance; 
        
        public static function getInstance()  
        { 
              if( !self::$instance ) self::$instance = new self(); 
              return self::$instance;
        } 

        /**
          * user roles access entry cntrl
          * user role filter  
        **/

        public static function user_roles_access_entry ( $status=false ) 
        {
            if( $status != false ) 
            {
                return array ( 
                    'administrator' 
                );       
            } else {
                return array ();   
            }
        }
        
        /**
         * load construct
         * auto file call
         * wp structure hook
         * wpdb querys - etc   
        **/

        public function admin_page () 
        {
            global $wp_roles;
            
            $this->userdata=  get_userdata( get_current_user_id() );
            
            if( in_array( $this->userdata->roles[0], self::user_roles_access_entry( true ) ) ) {
                $icon = self::$icon;
            } else {
                $icon = self::$icon_2;
            }

            $menu[] = array( self::$name, self::$name, 1, self::$plugin_slug, array( $this,  self::$plugin_slug.'_function'), $icon );
            
            if( in_array( $this->userdata->roles[0], self::user_roles_access_entry( true ) ) ) 
            { 
                $menu[] = array( 'Time Schedule', 'Time Schedule', 1, self::$plugin_slug, 'time_schedule_'.self::$plugin_slug, array( $this, 'time_schedule_'.self::$plugin_slug.'_function' ) );
                $menu[] = array( 'Add Tickets', 'Add Tickets', 1, self::$plugin_slug, 'add_tickets_'.self::$plugin_slug, array( $this, 'add_tickets_'.self::$plugin_slug.'_function' ) );
                $menu[] = array( 'Add Events', 'Add Events', 1, self::$plugin_slug, 'add_events_'.self::$plugin_slug, array( $this, 'add_events_'.self::$plugin_slug.'_function' ) );
                $menu[] = array( 'Add Prizes', 'Add Prizes', 1, self::$plugin_slug, 'add_prizes_'.self::$plugin_slug, array( $this, 'add_prizes_'.self::$plugin_slug.'_function' ) );
                $menu[] = array( 'Settings', 'Settings', 1, self::$plugin_slug, 'settings_'.self::$plugin_slug, array( $this, 'settings_'.self::$plugin_slug.'_function' ) );
            }
            
            $menu[] = array( 'Help?', 'Help?', 1, self::$plugin_slug, 'help_'.self::$plugin_slug, array( $this, 'help_'.self::$plugin_slug.'_function' ) );
            
            if( is_array( $menu ) ) add::load_menu_page( $menu );
        }
        
        public function update_db_check () 
        {
            global $db_version;
            if ( get_site_option( 'db_version' ) != $db_version ) self::install();
        }
        
        /** view structure ( include ) **/
        
        public function wp_raffle_function() 
        {
            load::view( 'manage' );
        }
        
        public function time_schedule_wp_raffle_function()
        {
            load::view( 'time_schedule_manager' );
        }
        
        public function add_tickets_wp_raffle_function() 
        {
            load::view( 'add-tickets' );
        }
        
        public function add_events_wp_raffle_function() 
        {
            load::view( 'add-events' );
        }

        public function add_prizes_wp_raffle_function() 
        {
            load::view( 'add-prizes' );
        }

        public function settings_wp_raffle_function() 
        {
            load::view( 'settings' );
        }
        
        public function help_wp_raffle_function() 
        {
            load::view( 'help' );
        }
        
        /** shortcode structure ( include ) **/
        
        public function wp_raffle_randoms () 
        {
            load::view( 'shortcode/shortcode' );
            return shortcode::randoms();
        }
        
        public function wp_raffle_times() 
        {
            load::view( 'shortcode/shortcode' );
            return shortcode::times();
        }
        
        public function wp_raffle_events() 
        {
            load::view( 'shortcode/shortcode' );
            return shortcode::events();
        }
        
        public function wp_raffle_prizes() 
        {
            load::view( 'shortcode/shortcode' );
            return shortcode::prizes();
        }
        
        /** ajaxs structures ( load-file ) **/
        
        public function ajaxs_get_tickets () 
        {
            action::ticket_select();
            die();
        }
        
        public function ajaxs_set_timer () 
        {
            action::set_timer();
            die();
        }
        
        public function ajaxs_action_randoms_selected () 
        {
            action::selected_randoms();
            die();
        }

        public function ajaxs_action_paypal_validate () 
        {
            action::action_paypal_validate();
            die();
        }

        public function ajaxs_action_sortable () 
        {
            action::action_sortable();
            die();
        }
        
    }

}  

new wp_raffle_page( true );
?>