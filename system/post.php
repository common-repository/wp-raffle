<?php if( !class_exists( 'post_objects' ) ){
      
      class post_objects 
      {
           
           public static function WP_Querys( $arry=array()){
                 
                 if( is_array( $arry ) ){
                     
                     $query = new WP_Query( $arry );
                     
                     if( is_object( $query ) ){
                         
                         $return = true;
                     } else {
                        
                         $return = false;
                        
                     }
                     
                 }
                 
                 return $return;
            
           }
           
      }
         
}
?>