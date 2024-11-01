<?php if( ! class_exists( 'settings' ) or die ( 'error found.' ) ) 
{
    class settings extends db
    {
        public static $tbls = array( '_settings' );

        function __construct()
        {
            // scripts
        }

        public static function get_value () 
        {
            global $wpdb;
            
            $id = 1;

            $tbls = $wpdb->prefix . __( "raffle" ) . self::$tbls[0];
            return $wpdb->get_row ( "SELECT * FROM {$tbls} WHERE id='{$id}'", OBJECT ); 
        }

        // END
    }
}
?>
