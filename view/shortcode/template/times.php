<?php if( !class_exists( 'times_objects' ) or die ( 'error found.' ) ) 
{    
    class times_objects extends schedule
    {
        public static function template () 
        {
            $html = null;
            $html .= self::time_calculate();
            
            return $html;
        }       
    }
}
?>