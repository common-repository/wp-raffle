<?php if( ! class_exists( 'paypal_gateway' ) or die ( 'error found.' ) ) {

    // http://www.codexworld.com/paypal-standard-payment-gateway-integration-php/

    class paypal_gateway 
    {
        public $sandbox_url= 'http://www.sandbox.paypal.com/cgi-bin/webscr';
        public static $paypal_url= 'http://www.sandbox.paypal.com/cgi-bin/webscr';
        public static $paypal_id = true; 

        public function __contruct ( $sandbox_url=null ) 
        {
            $this->value = 'paypal-gateway';
            $this->sandbox_url = self::$paypal_url;
        }

        public static function get_email () 
        {
            if( self::$paypal_id != false ) 
            {
                return self::$paypal_id;
            }
        }

        public static function curl_init_enabled ( $request=null ) 
        {
            $chars = curl_init( self::$paypal_url );

            if ( $ch == FALSE ) {
                return FALSE;
            }
            curl_setopt( $chars, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
            curl_setopt( $chars, CURLOPT_POST, 1 );
            curl_setopt( $chars, CURLOPT_RETURNTRANSFER,1 );
            curl_setopt( $chars, CURLOPT_POSTFIELDS, $request );
            curl_setopt( $chars, CURLOPT_SSLVERSION, 6 );
            curl_setopt( $chars, CURLOPT_SSL_VERIFYPEER, 1 );
            curl_setopt( $chars, CURLOPT_SSL_VERIFYHOST, 2 );
            curl_setopt( $chars, CURLOPT_FORBID_REUSE, 1 );

            // Set TCP timeout to 30 seconds
            curl_setopt( $chars, CURLOPT_CONNECTTIMEOUT, 30 );
            curl_setopt( $chars, CURLOPT_HTTPHEADER, array( 'Connection: Close', 'User-Agent: company-name' ) );

            return curl_exec( $chars );
        }

        public static function curl_init_validate ( $result=null ) 
        {
            $tokens = explode( "\r\n\r\n", trim( $result ) );
            $result = trim( end( $tokens ) );

            if ( strcmp( $result, 'VERIFIED' ) == 0 || strcasecmp( $result, 'VERIFIED' ) == 0 ) {
                return true;
            } else {
                return false;
            }
        }

        // END
    }
}
?>