<?php if( !class_exists( 'prizes_objects' ) ) 
{    
    class prizes_objects 
    {
        public static function template () 
        {
            $html = null;
            $html .= prizes::query_loops();
            
            return $html;
        }       
    }
}
?>