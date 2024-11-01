<?php if( !class_exists( 'randoms_objects' ) or die ( 'error found.' ) ) 
{    
    class randoms_objects extends schedule
    {
        public static function template () 
        {
            $html = null;
            $html .= self::random_selected();
            
            return $html;
        }       
    }
}
?>