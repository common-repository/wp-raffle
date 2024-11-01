<?php

if( ! class_exists( 'router' ) or die ( 'page rounter url' ) ) 
    {
        class router 
        {
            public static $wp_admin_page_querys = 'admin.php?page=';
            public static $wp_raffle_url = 'admin.php?page=wp_raffle';
            public static $wp_base_url = 'admin.php?page=wp_raffle';
            public static $wp_raffle_table = 'wp_raffle';

            public static $limit = 10;
            public static $offset = -1;

            public static $wp_separate = '&';

            public static $wp_users = 'user-edit.php?user_id=';
            public static $wp_users_actions = 'users.php?action=delete&user=';
        }
    }

?>

<?php if( ! class_exists( 'page_rounter' ) or die ( 'page rounter url' ) ) 
{
    class page_rounter extends router
    {
        
        public static function url ( $path=null, $querys=array() )  
        {
            $results = null;
            $url = $path;

            if( !empty( $querys) AND is_array( $querys) ) 
            {
                $i = 0;
                foreach( $querys as $keys => $elements )  
                {
                    $is_separate = $i++ !=0 ? self::$wp_separate : null;
                    $results .=  $is_separate . "{$keys}={$elements}";            
                }

            }

            if( $querys !=false ) {
                $results_values = self::$wp_separate . __( $results, 'wp-router' );    
            } else  {
                $results_values = null;    
            }
            
            return self::$wp_admin_page_querys.__( $url, 'wp-router' ). __( $results_values, 'wp-router' ); 
        }

        public static function admin_user_url ( $ids=0 ) 
        {
            $url = admin_url();
            return $url . __( self::$wp_users, 'wp-raffle-router' ) . intval( $ids );
        }

        public static function admin_user_url_actions ( $ids=0 ) 
        {
            $url = admin_url();
            // $nonce_url = wp_nonce_url();
            return $url . __( self::$wp_users_actions, 'wp-raffle-router' ) . intval( $ids );
        }

    }

    new page_rounter(true);
}

?>