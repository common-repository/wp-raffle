<?php if( !class_exists( 'events_objects' ) ) 
{    
    class events_objects 
    {
        public static function template () 
        {
            $html = null;
            $html .= events::query_loops();
            
            return $html;
            
        }       
    }
}
?>