<?php if( !class_exists( 'shortcode' ) ) 
{    
    class shortcode 
    {
        public static function randoms()
        {
            load::view( 'shortcode/template/randoms' );
            
            $html = null;
            $html .= randoms_objects::template();
            
            return $html;
        }
        
        public static function times() 
        {
            load::view( 'shortcode/template/times' );
            
            $html = null;
            $html .= times_objects::template();
            
            return $html;
        }
        
        public static function events() 
        {
            load::view( 'shortcode/template/events' );
             
            $html = null;
            $html .= events_objects::template();
            
            return $html;
        }
        
        public static function prizes() 
        {
            load::view( 'shortcode/template/prizes' );
             
            $html = null;
            $html .= prizes_objects::template();
            
            return $html;
        }
        
    }
}

new shortcode();

?>