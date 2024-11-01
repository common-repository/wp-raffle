<?php if( ! class_exists( 'config' ) or die ( 'config load error.' ) ) 
{   
    load::includes_file( '/model/db', 'model-file' );
    load::includes_file( '/model/times', 'model-file' );
    
    class config 
    {
         public static $name        = "WP Raffle";
         public static $icon        = "wp-raffle/assets/images/210-16.png";
         public static $icon_2      = "wp-raffle/assets/images/212-16.png";
         public static $plugin_slug = 'wp_raffle';
         public static $folder      = 'wp-raffle';
         public static $shortcode   = 'wp_raffle';
         public static $assets      = 'assets';
         
         protected $values          = array();
        
         function __construct() 
         {
                global $wpdb;
                
                add::action_page( array( $this, 'admin_page' ) );
                
                /** backend style ( admin ) **/
                 
                add::style( true, self::$plugin_slug.__( 'admin-style', 'wp-raffle' ), self::$folder.'/'.self::$assets.'/css/admin.css' );
                
                
                /** frontend style ( front ) **/
                
                add::style( false, self::$plugin_slug.__( 'front-style', 'wp-raffle' ), self::$folder.'/'.self::$assets.'/css/front.css' );
                
                /** backend script **/
                
                add::wp_script( 'jquery' );
                add::wp_script( 'dashboard', true );
                add::wp_script( 'jquery-ui-sortable', true );
                add::wp_script( 'jquery-ui-draggable' );
                add::wp_script( 'jquery-ui-droppable' );
                
                add::wp_script( 'jquery-ui-core' );
                add::wp_script( 'jquery-ui-dialog' );
                add::wp_script( 'jquery-ui-slider' );
                
                add::script( true, self::$plugin_slug.'admin-script', self::$folder.'/'.self::$assets.'/js/admin.js' );
                add::script( true, self::$plugin_slug.'sort-script', self::$folder.'/'.self::$assets.'/js/sort.js' );
                
                add::script( true, self::$plugin_slug.'ajax_handler', self::$folder.'/'.self::$assets.'/js/ajax.js' );
                add::localize_script( true, self::$plugin_slug.'ajax_handler', 'ajax_script', self::get_localize_script_arrays() );
                
                /** frontend script  **/
                
                add::script( false, self::$plugin_slug.'front-script', self::$folder.'/'.self::$assets.'/js/front.js' );
                
                /** actions method -- **/
                
                add::action_submit( 1, array( $this, 'action_add_tickets_handler' ) );
                add::action_submit( 1, array( $this, 'action_delete_tickets_handler' ) );

                add::action_submit( 1, array( $this, 'action_add_events_handler' ) );
                add::action_submit( 1, array( $this, 'action_delete_events_handler' ) );

                add::action_submit( 1, array( $this, 'action_add_prizes_handler' ) );
                add::action_submit( 1, array( $this, 'action_delete_prizes_handler' ) );

                add::action_submit( 1, array( $this, 'action_setting_handler' ) );
                add::action( 1, array( $this, 'menu_class_filter' ) );
                
                /** actions option ( callback ) **/
                
                add::action_loaded( array( $this,'update_db_check' ) );
                add_action( 'admin_bar_menu', array( $this, 'node_menu' ) );
                
                /** actions shortcode ( callback ) **/
                
                add::shortcode( self::$shortcode.'_shortcode_randoms', array( $this, self::$shortcode.'_randoms' ) );
                add::shortcode( self::$shortcode.'_shortcode_times', array( $this, self::$shortcode.'_times' ) );
                add::shortcode( self::$shortcode.'_shortcode_events', array( $this, self::$shortcode.'_events' ) ); 
                add::shortcode( self::$shortcode.'_shortcode_prizes', array( $this, self::$shortcode.'_prizes' ) );  
                
                /** actions ajax actions ( callback ) **/
              
                add::action_ajax( array( $this, 'ajaxs_get_tickets' ) ); 
                add::action_ajax( array( $this, 'ajaxs_set_timer' ) ); 
                add::action_ajax( array( $this, 'ajaxs_action_randoms_selected' ) );
                add::action_ajax( array( $this, 'ajaxs_action_paypal_validate' ) );
                add::action_ajax( array( $this, 'ajaxs_action_sortable' ) );

                /** actions widget ( create ) **/
                
                add::widget_init( array( $this, 'register_widgets' ) );
                add::action( 2, array( $this, 'media_loader' ) );
                
         }
         
         public static function get_localize_script_arrays () 
         {
                return array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'admin_url' => admin_url(), 'times_schedule_key' => times::get_generated_ranoms_keys() );   
         }
         
         public function media_loader () 
         {
                add::wp_media( true );
         }

         public static function install () 
         {
                global $wpdb;

                $tbls = array();
                    
                $tickets = $wpdb->prefix . __( 'raffle_tickets','wp-raffle' );
                $events  = $wpdb->prefix . __( 'raffle_events', 'wp-raffle' );
                $prizes  = $wpdb->prefix . __( 'raffle_prizes', 'wp-raffle' );
                $users   = $wpdb->prefix . __( 'raffle_user', 'wp-raffle' );
                $settings= $wpdb->prefix . __( 'raffle_settings', 'wp-raffle' );
                    
                $charset_collate = $wpdb->get_charset_collate();
                    
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                
                $tbls[] = "CREATE TABLE `{$tickets}` (
                  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
                  `event_id` int(11) NOT NULL,
                  `num` mediumint(9) NOT NULL,
                  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                  `name` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
                  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `url` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                  `price` int(11) NOT NULL,
                  `orders` int(11) NOT NULL,
                  `qty` int(11) NOT NULL,
                  UNIQUE KEY id (id)
                ) {$charset_collate};";

                $tbls[] = "CREATE TABLE {$events} (
                  id mediumint(9) NOT NULL AUTO_INCREMENT,
                  num mediumint(9) NOT NULL,
                  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                  name tinytext NOT NULL,
                  text text NOT NULL,
                  url varchar(55) DEFAULT '' NOT NULL,
                  orders text NOT NULL,
                  UNIQUE KEY id (id)
                ) {$charset_collate};";
                 
                $tbls[] = "CREATE TABLE {$prizes} (
                  id mediumint(9) NOT NULL AUTO_INCREMENT,
                  num mediumint(9) NOT NULL,
                  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                  name tinytext NOT NULL,
                  text text NOT NULL,
                  url varchar(55) DEFAULT '' NOT NULL,
                  orders text NOT NULL,
                  UNIQUE KEY id (id)
                ) {$charset_collate};";
                
                $tbls[] = "CREATE TABLE `{$users}` (
                  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
                  `user_id` int(11) NOT NULL,
                  `ticket_id` int(11) NOT NULL,
                  `value` int(11) NOT NULL
                ) {$charset_collate};";

                $tbls[] = "CREATE TABLE `{$settings}` (
                  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
                  `boxies_hide` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `boxies_sort` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `manager_dashboard` text COLLATE utf8mb4_unicode_ci NOT NULL
                ) {$charset_collate};";

                $sqls = $tbls;
                
                if( isset( $sqls ) and is_array( $sqls ) ) foreach( $sqls as $sql ) :     
                dbDelta( $sql ); 
                endforeach;
                
                // self::dbDelta_alters( array( $tickets, $events, $prizes, $users ) );
                
         }
         
         public static function dbDelta_alters ( $tbls=array() ) 
         {
                global $wpdb;
                
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                
                if( is_array( $tbls ) ) : foreach( $tbls as $tbls_keys => $tbls_res ) :  
                
                    $alts = "ALTER TABLE {$tbls_res} ADD COLUMN orders NOT NULL AFTER url;";
                    dbDelta( $alts ); 
                    
                endforeach; endif;  
         }
         
         /**
          * Actions submits functions
          * events
         **/
         
         /**
          * Tickets
         **/

         public function action_add_tickets_handler () 
         {
            action::add_tickets();          
         }

         public function action_delete_tickets_handler () 
         {
            action::delete_tickets();          
         }

         /**
          * Events
         **/

         public function action_add_events_handler ()  
         {
            action::add_events();          
         }

         public function action_delete_events_handler () 
         {
            action::delete_events();          
         }

         /**
          * Prizes
         **/

         public function action_add_prizes_handler () 
         {
            action::add_prizes();          
         }

         public function action_delete_prizes_handler () 
         {
            action::delete_prizes();          
         }

         /**
           * Settings
         **/

         public function action_setting_handler () 
         {
            action::action_settings_loader();
         }
         
         /**
            WP register widgtet
         **/
         
         public function register_widgets () 
         {
            register_widget( 'Add_Widget' );
         }

         /**
           * admin page menu - filter
           * conditional class elements 
         **/

         public function menu_class_filter () 
         {
            global $menu, $submenu; 
            // $key_vals = array_search( 'wp_raffle', array_column( $menu, 2 ) );
            // foreach( $submenu['wp_raffle'] as $key => $value ) {
                // $value;    
            // }
         }

         public function node_menu ( $wp_admin_bar )
         {
            global $menu, $submenu;
            // $wp_admin_bar->add_node( $args );
         }

         // END
    }    
    
}
?>