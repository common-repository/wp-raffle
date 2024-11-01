<?php if( ! class_exists( 'form' ) or die ( 'error found.' ) ) 
{    
    class form extends input
    {
          var $html = null;
                
          public function __construct() 
          {
               parent::__construct();
               new page_rounter( true );
          }

          public static function page_manager_head ( $setting_link=null ) 
          {
               $html = null;

               $boxies = array(

                        'tickets' => 
                            array(
                                'slug' => 'tickets',
                                'column' => 0,
                                'status' => 'active' 
                            ),

                        'users' => 
                            array(
                                'slug' => 'users',
                                'column' => 1,
                                'status' => 'active'  
                            ),

                        'events' => 
                            array(
                                'slug' => 'events',
                                'column' => 2, 
                                'status' => 'active' 
                            ),

                        'prizes' => 
                            array(
                                'slug' => 'prizes',
                                'column' => 4,
                                'status' => 'active'  
                            ),

                    );

               $html .= '<span id="wp-raffle_boxies-dropdown"></span>';

               $settings = page_rounter::url( 'settings_wp_raffle', array( 'setting' => 'paypal' ) );

               $html .= '<a href="' . __( $settings, 'wp-raffle' ) . '" id="wp-raffle_action-settings"></a>';

               $html .= '<div class="wp-raffle_dropdown">';

               foreach( $boxies as $keys => $vals ) :

                  $slugs = $vals['slug'];
                  $status = $vals['status'];

                  $html .= '<div class="wp-raffle_dropdown-submenu '.__( $slugs, 'wp-raffle' ).'-ctrl '.__( $status, 'wp-raffle' ).'">';
                  $html .= ucfirst( strtolower( $keys ) );
                  $html .= '</div>';

               endforeach; 

               $html .= '</div>';

               return $html;
          }
          
          public static function page_section_head ( $titled=null, $classes=array(), $urls=array(), $slugs='', $values=array() ) 
          {    
               $html = null;

               $user_role = user_control::get_role();
               $is_active = user_control::is_active();

               $titled_class = ! in_array( $user_role, $is_active ) ? ' class="not-admin" ' : null;

               $html .= "<a href='#sortable' class='sortable-ui-handler-functions sortable-icon'>";
               $html .= self::hidden( $values );
               $html .= "</a>";

               $html .= "<h2 ". __( $titled_class, 'wp-raffle' ) .">" . __( "{$titled}", 'wp-raffle' ) . "</h2>";

               if( is_array( $classes ) and isset( $classes[0] ) AND in_array( $user_role, $is_active ) ) :
                   $html .= "<a href='{$urls[0]}' class='{$classes[0]}-icon {$slugs}-title__{$classes[0]} setting-boxies'></a>";   
               endif;
               
               if( is_array( $classes ) and isset( $classes[1] ) ) :
                   $html .= "<a href='{$urls[1]}' class='{$classes[1]}-icon {$slugs}-title__{$classes[1]} delete-boxies'></a>";   
               endif;
               
               if( is_array( $classes ) and isset( $classes[2] ) AND in_array( $user_role, $is_active ) ) : 
                   $html .= "<a href='{$urls[2]}' class='{$classes[2]}-icon {$slugs}-title__{$classes[2]}'></a>";   
               endif;
               
               return $html;
          }
          
          public static function accounts_section_head ( $titled=null, $tabs=array(), $classes=array(), $urls=array(), $slugs='', $values=array() ) 
          {    
               $html =null;

                $html .= "<a href='#sortable' class='sortable-ui-handler-functions sortable-icon'>";
                $html .= self::hidden( $values );
                $html .= "</a>";
               
               $html .= "<h2>" . __( "{$titled}", 'wp-raffle' ) . "</h2>";

               if( is_array( $tabs ) and isset( $tabs ) ) : 
                   $i = 1; 
                   foreach( $tabs as $keys => $tab_vals ) :
                        $html .= "<a href='#tab{$i}' class='accounts-tabs accounts-{$keys} tab{$i}' id='tab{$i}'>{$tab_vals}</a>";
                   $i++; endforeach; 
               endif;
                 
               if( is_array( $classes ) and isset( $classes[0] ) ) :
                   $html .= "<a href='{$urls[0]}' class='{$classes[0]}-icon {$slugs}-title__{$classes[0]}'></a>";   
               endif;
               
               if( is_array( $classes ) and isset( $classes[1] ) ) : 
                   $html .= "<a href='{$urls[1]}' class='{$classes[1]}-icon {$slugs}-title__{$classes[1]}'></a>";   
               endif;
               
               return $html;
          }
          
          public static function section_action ( $classes=array() ) 
          {
               $html =null; 
               
               if( is_array( $classes ) and isset( $classes ) ): 
               
                   foreach( $classes as $keys => $returns ) : 
                         $vals[] = $returns; 
                   endforeach;

                   if( isset( $vals ) ) : foreach( $vals as $vals_keys => $vals_returns ) : 
                       $html .= "<a href='#' class='{$vals[$vals_keys][0]}-{$vals[$vals_keys][1]}__links'>{$vals[$vals_keys][2]}</a>"; 
                   endforeach; 
                   endif;
                   
               endif;

               return $html;
          }

          /**
            * tickets forms functional handler
          **/

          public static function form_tickets_label ( $key=null, $class='' ) 
          {
              $html = null;

              $labels = array( 
                        'name_inputs' => 'Name (prize)',
                        'event_selects' => 'Event ID (selected)',
                        'time_inputs' => 'Date (time)',
                        'descr_inputs' => 'Description (message)',
                        'url_inputs' => 'URL (website)',
                        'price_inputs' => 'Price (of tickets)',
                        'quantity_inputs' => 'Quantity (of tickets)'
                    );

              if( ! empty( $labels[$key]) ) 
              {
                  $lbls = $labels[$key];
                  $html .= "<div class='wp-raffle_label-div label-div-{$class}'>";
                  $html .= html::label( array( 'text' => $lbls, 'for' => $lbls ) );
                  $html .= '</div>';
              }

              return $html;
          }
          
          public static function page_form_tickets ( $class='') 
          {
               $html  = null;
               $today = date( 'F j, Y, g:i A' ); 
               $default_date_format = '0000-00-00 00:00:00';
               $elems = self::post_is_object();
               $id    = self::get_is_object_element ( 'edit_tickets' );
               $rows  = tickets::get_querys_rows( $id );     
               $get_events = events::get_querys_inner_selects();

               if( $id != 0 ) 
               {
                  $name = isset( $rows->name ) ? $rows->name : 'Name';
                  $time = isset( $rows->time ) ? $rows->time : $default_date_format;
                  $text = isset( $rows->text ) ? $rows->text : 'Description';
                  $url  = isset( $rows->url ) ? $rows->url : 'http://';
                  $price= isset( $rows->price ) ? $rows->price : 200;
                  $qty  = isset( $rows->qty ) ? $rows->qty : 1000;
                  $evts = isset( $rows->event_id ) ? $rows->event_id : 0;
               } else {
                  $name = isset( $elems->name_inputs ) ? $elems->name_inputs : 'Name';
                  $time = isset( $elems->time_inputs ) ? $elems->time_inputs :  $default_date_format;
                  $text = isset( $elems->descr_inputs ) ? $elems->descr_inputs : 'Description';
                  $url  = isset( $elems->url_inputs ) ? $elems->url_inputs : 'http://';
                  $price= isset( $elems->price_inputs ) ? $elems->price_inputs : 200;
                  $qty  = isset( $elems->quantity_inputs ) ? $elems->quantity_inputs : 1000;
                  $evts = isset( $elems->event_selects ) ? $elems->event_selects : 0;
               }

               $inputs[] = array( 'value' =>  $name, 
                                  'name'  => 'name_inputs', 
                                  'class' => "name-{$class}_inputs",
                                  'id'    => "name-{$class}_inputs" );
               
               $inputs[] = array( 'name'  => 'event_selects',
                                  'class' => "events-{$class}_selects",
                                  'id'    => "events-{$class}_selects" );
               
               $inputs[] = array( 'value' =>  $time, 
                                  'name'  => 'time_inputs', 
                                  'class' => "time-{$class}_inputs",
                                  'id'    => "time-{$class}_inputs" );
               
               $inputs[] = array( 'value' => $text, 
                                  'name'  => 'descr_inputs', 
                                  'class' => "descr-{$class}_inputs",
                                  'id'    => "descr-{$class}_inputs" );
                 
               $inputs[] = array( 'value' => $url, 
                                  'name'  => 'url_inputs', 
                                  'class' => "url-{$class}_inputs",
                                  'id'    => "url-{$class}_inputs" );
                                  
               $inputs[] = array( 'value' =>  $price, 
                                  'name'  => 'price_inputs', 
                                  'class' => "price-{$class}_inputs",
                                  'id'    => "price-{$class}_inputs" );
                                  
               $inputs[] = array( 'value' =>  $qty, 
                                  'name'  => 'quantity_inputs', 
                                  'class' => "quantity-{$class}_inputs",
                                  'id'    => "quantity-{$class}_inputs" );
               
               $is_update = $id !=0 ? 'Update' : 'Submit';
               
               $inputs[] = array( 'value' => __( $is_update, 'wp-raffle' ), 
                                  'name'  => 'submit_tickets', 
                                  'class' => "submit-{$class}_inputs",
                                  'id'    => "submit-{$class}_inputs" );
                 
               foreach( $inputs as $keys => $results ) :
                      
                     $last_class = ( $results['name'] == 'submit_tickets' ) ? 'last-inputs' : null; 
                     
                     $html .= "<div class='wp-raffle__line {$class}-input {$last_class}'>";

                     $html .= self::form_tickets_label( $results['name'], 'tickets' );
                     
                     if( $results['name'] == 'submit_tickets' ) {
                         $html .= self::submit( $results );   
                     } else if( $results['name'] == 'event_selects' ) {
                         $html .= html::select( $results, $get_events, $evts, 'Events ...' );
                         $value = self::page_form_tickets_validate( $results['name'] );
                         if( $value == false AND $id == 0 ) {
                             $html .= __( '<p class="error-found">Empty Input : Default Value is not Valid Please change.</p>', 'wp-raffle' );  
                         } else {
                             $html .= __( '<p class="no-error">Value Input : Validate Confirm</p>', 'wp-raffle' );  
                         }
                     } else {
                         $html .= self::text( $results );
                         $value = self::page_form_tickets_validate( $results['name'] );
                         if( $value == false AND $id == 0 ) {
                             $html .= __( '<p class="error-found">Empty Input : Default Value is not Valid : Please change</p>', 'wp-raffle' );  
                         } else 
                         {
                             $html .= __( '<p class="no-error">Value Input : Validate Confirm</p>', 'wp-raffle' ); 
                         }
                     }
                     
                     $html .= '</div>';
                          
               endforeach;
               
               return $html;

          }
          
          public static function page_form_tickets_validate ( $strs=null ) 
          {
               $inputs = self::post_is_object ();

               $defauls = array( 
                        'name_inputs' => 'Name',
                        'event_selects' => 0,
                        'time_inputs' => null,
                        'descr_inputs' => 'Description',
                        'url_inputs' => 'http://',
                        'price_inputs' => 200,
                        'quantity_inputs' => 1000
                    );

               if( isset( $inputs->submit_tickets ) ) 
               {
                  $results = ( $inputs->$strs != $defauls[$strs] ) ? true : false;  
               } else {
                  $results = null;
               }

               return $results;   
          }

          public static function tickets_form_validate ( $inputs=null ) 
          {
               $value = array();

               $defauls = array( 
                        'name_inputs' => 'Name',
                        'event_selects' => 0,
                        'time_inputs' => null,
                        'descr_inputs' => 'Description',
                        'url_inputs' => 'http://',
                        'price_inputs' => 200,
                        'quantity_inputs' => 1000
                    );

               foreach( $inputs as $keys => $inputs_val )
               {
                    $value[] = $inputs->$keys != $defauls[$keys] ? true : false;     
               }  

               return $value;
          }

          /**
            * events forms functional handler
          **/

          public static function form_events_label ( $key=null, $class='' ) 
          {
              $html = null;

              $labels = array( 
                        'name_inputs' => 'Name (prize)',
                        'time_inputs' => 'Date (time)',
                        'descr_inputs' => 'Description (message)',
                        'url_inputs' => 'URL (website)'
                    );

              if( ! empty( $labels[$key]) ) 
              {
                  $lbls = $labels[$key];
                  $html .= "<div class='wp-raffle_label-div label-div-{$class}'>";
                  $html .= html::label( array( 'text' => $lbls, 'for' => $lbls ) );
                  $html .= '</div>';
              }

              return $html;
          }
          
          public static function page_form_events ( $class='' ) 
          {
               $html = null;
               $today = date( 'F j, Y, g:i A' );
               $default_date_format = '0000-00-00 00:00:00';
               $elems = self::post_is_object();
               $id    = self::get_is_object_element ( 'edit_events' );
               $rows  = events::get_querys_rows( $id );   

               if( $id != 0 ) 
               {
                  $name = isset( $rows->name ) ? $rows->name : 'Name';
                  $time = isset( $rows->time ) ? $rows->time : $default_date_format;
                  $text = isset( $rows->text ) ? $rows->text : 'Description';
                  $url  = isset( $rows->url ) ? $rows->url : 'http://';
               } else {
                  $name = isset( $elems->name_inputs ) ? $elems->name_inputs : 'Name';
                  $time = isset( $elems->time_inputs ) ? $elems->time_inputs : $default_date_format;
                  $text = isset( $elems->descr_inputs ) ? $elems->descr_inputs : 'Description';
                  $url  = isset( $elems->url_inputs ) ? $elems->url_inputs : 'http://';
               }
               
               $inputs[] = array( 'value' => $name, 
                                  'name'  => 'name_inputs', 
                                  'class' => "name-{$class}_inputs",
                                  'id'    => "name-{$class}_inputs" );

               $inputs[] = array( 'value' => $time, 
                                  'name'  => 'time_inputs', 
                                  'class' => "time-{$class}_inputs",
                                  'id'    => "time-{$class}_inputs" );
               
               $inputs[] = array( 'value' => $text, 
                                  'name'  => 'descr_inputs', 
                                  'class' => "descr-{$class}_inputs",
                                  'id'    => "descr-{$class}_inputs" );
                 
               $inputs[] = array( 'value' => $url, 
                                  'name'  => 'url_inputs', 
                                  'class' => "url-{$class}_inputs",
                                  'id'    => "url-{$class}_inputs" );
               
               $is_update = $id !=0 ? 'Update' : 'Submit';

               $inputs[] = array( 'value' => $is_update, 
                                  'name'  => 'submit_events', 
                                  'class' => "submit-{$class}_inputs",
                                  'id'    => "submit-{$class}_inputs" );
                 
               foreach( $inputs as $keys => $results ) :
               
                     $last_class = ( $results['name'] == 'submit_events' ) ? 'last-inputs' : null; 
                     
                     $html .= "<div class='wp-raffle__line {$class}-input {$last_class}'>";

                     $html .= self::form_events_label( $results['name'], 'events' );
                     
                     if( $results['name'] == 'submit_events' ) {
                         $html .= self::submit( $results ); 
                     } else {
                         $html .= self::text( $results );
                         $value = self::page_form_events_validate( $results['name'] ); 
                         if( $value == false AND $id == 0 ) {
                             $html .= __( '<p class="error-found">Empty Input : Default Value is not Valid Please change.</p>', 'wp-raffle' );  
                         } else {
                             $html .= __( '<p class="no-error">Value Input : Validate Confirm</p>', 'wp-raffle' );  
                         }
                     }
                     
                     $html .= '</div>';
                          
               endforeach;
               
               return $html;

          }

          public static function page_form_events_validate ( $strs=null ) 
          {
               $inputs = self::post_is_object ();

               $defauls = array( 
                        'name_inputs' => 'Name',
                        'time_inputs' => null,
                        'descr_inputs' => 'Description',
                        'url_inputs' => 'http://'
                    );

               if( isset( $inputs->submit_events ) ) 
               {
                  $results = ( $inputs->$strs != $defauls[$strs] ) ? true : false;  
               } else {
                  $results = null;
               }

               return $results;   
          }

          public static function events_form_validate ( $inputs=null ) 
          {
               $value = array();

               $defauls = array( 
                        'name_inputs' => 'Name',
                        'time_inputs' => null,
                        'descr_inputs' => 'Description',
                        'url_inputs' => 'http://'
                    );

               foreach( $inputs as $keys => $inputs_val )
               {
                    $value[] = $inputs->$keys != $defauls[$keys] ? true : false;     
               }  

               return $value;
          }  

          /**
            * prizes forms functional handler
          **/

          public static function form_prizes_label ( $key=null, $class='' ) 
          {
              $html = null;

              $labels = array( 
                        'name_inputs' => 'Name (prize)',
                        'tickets_selects' => 'Ticket ID (selected)',
                        'events_selects' => 'Event ID (selected)',
                        'descr_inputs' => 'Description (message)',
                        'url_inputs' => 'URL (website)',
                        'image_inputs' => 'Image Browse (media)'
                    );

              if( ! empty( $labels[$key]) ) 
              {
                  $lbls = $labels[$key];
                  $html .= "<div class='wp-raffle_label-div label-div-{$class}'>";
                  $html .= html::label( array( 'text' => $lbls, 'for' => $lbls ) );
                  $html .= '</div>';
              }

              return $html;
          }

          public static function page_form_prizes ( $class='' ) 
          {
               $html = null;
               $today = date( 'F j, Y, g:i A' );
               $default_date_format = '0000-00-00 00:00:00';

               $elems = self::post_is_object();
               $id    = self::get_is_object_element ( 'edit_prizes' );

               $rows  = prizes::get_querys_rows( $id );

               $get_events = events::get_querys_inner_selects();
               $get_tickets = tickets::get_querys_inner_selects();

               if( $id != 0 ) 
               {
                  $name = isset( $rows->name ) ? $rows->name : 'Name';
                  $text = isset( $rows->text ) ? $rows->text : 'Description';
                  $tkts = isset( $rows->ticket_id ) ? $rows->ticket_id : 0;
                  $evts = isset( $rows->event_id ) ? $rows->event_id : 0;
                  $url  = isset( $rows->url ) ? $rows->url : 'http://';
                  $imgs = isset( $rows->image ) ? $rows->image : 'http://';
               } else {
                  $name = isset( $elems->name_inputs ) ? $elems->name_inputs : 'Name';
                  $text = isset( $elems->descr_inputs ) ? $elems->descr_inputs : 'Description';
                  $tkts = isset( $elems->tickets_selects ) ? $elems->tickets_selects : 0;
                  $evts = isset( $elems->event_selects ) ? $elems->event_selects : 0;
                  $url  = isset( $elems->url_inputs ) ? $elems->url_inputs : 'http://';
                  $imgs = isset( $elems->image_inputs ) ? $elems->image_inputs : 'http://';
               }

               $inputs[] = array( 'value' => $name, 
                                  'name'  => 'name_inputs', 
                                  'class' => "name-{$class}_inputs",
                                  'id'    => "name-{$class}_inputs" );

               $inputs[] = array( 'name'  => 'tickets_selects',
                                  'class' => "tickets-{$class}_selects",
                                  'id'    => "tickets-{$class}_selects" );

               $inputs[] = array( 'name'  => 'events_selects',
                                  'class' => "events-{$class}_selects",
                                  'id'    => "events-{$class}_selects" );

               $inputs[] = array( 'value' => $text, 
                                  'name'  => 'descr_inputs', 
                                  'class' => "descr-{$class}_inputs",
                                  'id'    => "descr-{$class}_inputs" );

               $inputs[] = array( 'value' => $url, 
                                  'name'  => 'url_inputs', 
                                  'class' => "url-{$class}_inputs",
                                  'id'    => "url-{$class}_inputs" );

               $inputs[] = array( 'value' => $imgs, 
                                  'name'  => 'image_inputs', 
                                  'class' => "image-{$class}_inputs",
                                  'id'    => "image-{$class}_inputs" );

               $is_update = $id !=0 ? 'Update' : 'Submit';

               $inputs[] = array( 'value' => $is_update, 
                                  'name'  => 'submit_prizes', 
                                  'class' => "submit-{$class}_inputs",
                                  'id'    => "submit-{$class}_inputs" );

               foreach( $inputs as $keys => $results ) :
               
                     $last_class = ( $results['name'] == 'submit_prizes' ) ? 'last-inputs' : null; 
                     
                     $html .= "<div class='wp-raffle__line {$class}-input {$last_class}'>";

                     $html .= self::form_prizes_label( $results['name'], 'prizes' );
                     
                     if( $results['name'] == 'submit_prizes' ) {
                         $html .= self::submit( $results ); 
                     } else if( $results['name'] == 'tickets_selects' ) {
                         $html .= html::select( $results, $get_tickets, $tkts, 'Tickets ...' );
                     } else if( $results['name'] == 'events_selects' ) {
                         $html .= html::select( $results, $get_events, $evts, 'Events ...' );
                     } else if( $results['name'] == 'image_inputs' ) {
                         $html .= uploader::file( $results, 'prize_browse_submit', 'prize_browse_submit' );
                         $value = self::page_form_prizes_validate( $results['name'] ); 
                         if( $value == false AND $id == 0 ) {
                             $html .= __( '<p class="error-found">Empty Input : Default Value is not Valid Please change.</p>', 'wp-raffle' );  
                         } else {
                             $html .= __( '<p class="no-error">Value Input : Validate Confirm</p>', 'wp-raffle' );  
                         }
                     } else { 
                         $html .= self::text( $results );
                         $value = self::page_form_prizes_validate( $results['name'] ); 
                         if( $value == false AND $id == 0 ) {
                             $html .= __( '<p class="error-found">Empty Input : Default Value is not Valid Please change.</p>', 'wp-raffle' );  
                         } else {
                             $html .= __( '<p class="no-error">Value Input : Validate Confirm</p>', 'wp-raffle' );  
                         }
                     }
                     
                     $html .= '</div>';
                          
               endforeach;

               return $html;

          }

          public static function page_form_prizes_validate ( $strs=null ) 
          {
               $inputs = self::post_is_object ();

               $defauls = array( 
                        'name_inputs' => 'Name',
                        'descr_inputs' => 'Description',
                        'url_inputs' => 'http://',
                        'image_inputs' => 'http://'
                    );

               if( isset( $inputs->submit_prizes ) ) 
               {
                  $results = ( $inputs->$strs != $defauls[$strs] ) ? true : false;  
               } else {
                  $results = null;
               }

               return $results;   
          }

          public static function prizes_form_validate ( $inputs=null ) 
          {
               $value = array();

               $defauls = array( 
                        'name_inputs' => 'Name',
                        'descr_inputs' => 'Description',
                        'url_inputs' => 'http://',
                        'image_inputs' => 'http://'
                    );

               foreach( $inputs as $keys => $inputs_val )
               {
                    $value[] = $inputs->$keys != $defauls[$keys] ? true : false;     
               }  

               return $value;
          }  

          /**
            * settings forms functional handler
            * settings page condition display UI
          **/

          public static function settings_page () 
          {
              // forms
              $html = null;

              $slug = input::get_is_object_element( 'setting' );

              switch( $slug ) :

              case 'tickets' :
              $html .= self::settings_tickets( $slug );
              break;

              case 'users' :
              $html .= self::settings_users( $slug );
              break;

              case 'events' :
              $html .= self::settings_events( $slug );
              break;

              case 'prizes' :
              $html .= self::settings_prizes( $slug );
              break;

              default:
                $html .= self::settings_paypal();    
                $html .= self::settings_links();
                $html .= self::settings_submit( 'settings' );
              endswitch;

              return $html;
          }

          public static function settings_paypal_label ( $key=null, $elem=null ) 
          {
              $labels = array ( 

                        'name_inputs' => array ( 
                            'name' => 'Name',
                            'desc' => '' 
                        ),

                        'email_inputs' => array (  
                            'name' => 'Email',
                            'desc' => '' 
                        ),

                        'pid_inputs' => array (  
                            'name' => 'Paypal ID (key)',
                            'desc' => '' 
                        ),

                        'phone_inputs' => array ( 
                            'name' => 'Phone',
                            'desc' => '' 
                        ),

                        'address_inputs' => array ( 
                            'name' => 'Address',
                            'desc' => '' 
                        )
                    );

              return isset( $labels[$key][$elem] ) ? $labels[$key][$elem] : null;
          } 

          public static function settings_paypal  ( $class='settings' ) 
          {
              $html = null;
              $elems = self::post_is_object();
              $gets  = input::get_is_object_element( 'setting' );

              $get_value = unserialize( get_option( 'wp_raffle_setting_paypal' ) );

              $name = isset( $elems->name_inputs ) ? $elems->name_inputs : $get_value->name_inputs;
              $email= isset( $elems->email_inputs ) ? $elems->email_inputs : $get_value->email_inputs;
              $pid  = isset( $elems->pid_inputs ) ? $elems->pid_inputs : $get_value->pid_inputs;
              $phone= isset( $elems->phone_inputs ) ? $elems->phone_inputs : $get_value->phone_inputs;
              $add  = isset( $elems->address_inputs ) ? $elems->address_inputs : $get_value->address_inputs;

              $inputs[] = array( 'value' => $name, 
                                 'name'  => 'name_inputs', 
                                 'class' => "name-{$class}_inputs",
                                 'id'    => "name-{$class}_inputs" );

              $inputs[] = array( 'value' => $email, 
                                 'name'  => 'email_inputs', 
                                 'class' => "email-{$class}_inputs",
                                 'id'    => "email-{$class}_inputs" );

              $inputs[] = array( 'value' => $pid, 
                                  'name' => 'pid_inputs', 
                                  'class'=> "pid-{$class}_inputs",
                                  'id'   => "pid-{$class}_inputs" );

              $inputs[] = array( 'value' => $phone, 
                                 'name'  => 'phone_inputs', 
                                 'class' => "phone-{$class}_inputs",
                                 'id'    => "phone-{$class}_inputs" );

              $inputs[] = array( 'value' => $add, 
                                  'name'  => 'address_inputs', 
                                  'class' => "address-{$class}_inputs",
                                  'id'    => "address-{$class}_inputs" );

              foreach( $inputs as $keys => $results ) :

                      $html .= "<div class='setting-input-wrap setting-input-{$class} ". __( $results['name'] ) ."-{$gets}'>";

                      $label = self::settings_paypal_label( $results['name'], 'name' );

                      $html .= "<div class='wp-raffle_label-div label-div-{$class}'>";
                      $html .= html::label( array( 'text' => $label, 'for' => $label ) );
                      $html .= '</div>';

                      $html .= self::text( $results ); 

                      $html .= '</div>';

                      if( $results['name'] == 'pid_inputs' ) {

                          $path = page_rounter::url( 'settings_wp_raffle', array( 'setting' => 'paypal-validate', 'submit' => 'paypal' ) );

                          $html .= '<div class="wp-raffle_paypal paypal-submit_validate">';
                          $html .= '<a href="' . __( '#', 'wp-raffle paypal' ). '" class="paypal_submit button loader" id="paypal_submit">' . __( 'Paypal Validate', 'wp-raffle paypal' ). '</a>';
                          $html .= '</div>';
                      }

              endforeach; 

              return $html;   
          }

          public static function settings_links ( $class='settings' ) 
          {
              $html = null;
              
              $inputs[] = array( 'href'  => 'tickets', 
                                 'label' => 'Tickets', 
                                 'class' => "tickets-{$class}_links",
                                 'id'    => "tickets-{$class}_links" );
                                 
              $inputs[] = array( 'href'  => 'users', 
                                 'label' => 'Users', 
                                 'class' => "users-{$class}_links",
                                 'id'    => "users-{$class}_links" );

              $inputs[] = array( 'href'  => 'events', 
                                 'label' => 'Events', 
                                 'class' => "events-{$class}_links",
                                 'id'    => "events-{$class}_links" );    

              $inputs[] = array( 'href'  => 'prizes', 
                                 'label' => 'Prizes', 
                                 'class' => "prizes-{$class}_links",
                                 'id'    => "prizes-{$class}_links" );

              $html .= "<div class='setting-input-wrap setting-input-{$class}'>";

              foreach( $inputs as $keys => $results ) :

                      $path = page_rounter::url( 'settings_wp_raffle', array( 'setting' => $results['href'] ) );

                      $html .= '<a href="'. __( $path, 'wp-raffle-settings' ) .'" ';
                      $html .= 'class="'. __( $results['class'], 'wp-raffle-settings' ) .' button" ';
                      $html .= 'id="'. __( $results['id'], 'wp-raffle-settings' ) .'">';
                      $html .= __( $results['label'], 'wp-raffle-settings' ) .'</a>';
                      
              endforeach; 

              $html .= '</div>';

              return $html;   
          }

          public static function settings_submit ( $class='settings' ) 
          {
              $html = null;

              $inputs = array( 'value' => 'Submit', 
                               'name'  => 'submit_settings', 
                               'class' => "submit-{$class}_inputs",
                               'id'    => "submit-{$class}_inputs" );

              $html .= "<div class='setting-input-wrap setting-input-{$class} submit-settings'>";
              $html .= self::submit( $inputs ); 
              $html .= '</div>';

              return $html;
          }

          public static function settings_tickets_label ( $key=null, $elem=null ) 
          {
              $labels = array (

                      'count_checkbox' => array (
                                    'label' => 'Counter (next to the title)',
                                    'details' => ''
                                ),

                      'id_checkbox' => array (
                                    'label' => 'ID Column (show or hide)',
                                    'details' => ''
                                ),

                      'rq_checkbox' => array (
                                    'label' => 'Quantity (show or hide)',
                                    'details' => ''
                                ),

                      'ords_checkbox' => array (
                                    'label' => 'Orders (show or hide)',
                                    'details' => ''
                                ),

                      'actns_checkbox' => array (
                                    'label' => 'Action Edit and Delete (show or hide)',
                                    'details' => ''
                                ),

                      'hide_checkbox' => array (
                                    'label' => 'Box (show or hide)',
                                    'details' => ''
                                )
                    );

              return isset( $labels[$key]) ? $labels[$key][$elem] : null;
          }

          public static function settings_tickets_is_checked ( $key=null ) 
          {
              $get_value = unserialize( get_option( 'wp_raffle_setting_tickets' ) );

              $is_checked = array(

                            'count_checkbox' => isset( $get_value->count_checkbox ) ? intval( $get_value->count_checkbox ) : null,
                            'id_checkbox'    => isset( $get_value->id_checkbox ) ? intval( $get_value->id_checkbox ) : null,
                            'rq_checkbox'    => isset( $get_value->rq_checkbox ) ? intval( $get_value->rq_checkbox ) : null,
                            'ords_checkbox'  => isset( $get_value->ords_checkbox ) ? intval( $get_value->ords_checkbox ) : null,
                            'actns_checkbox' => isset( $get_value->actns_checkbox ) ? intval( $get_value->actns_checkbox ) : null,
                            'hide_checkbox'  => isset( $get_value->hide_checkbox ) ? intval( $get_value->hide_checkbox ) : null,

                      );

              return isset( $is_checked[$key] ) ? $is_checked[$key] : null;
          }

          public static function settings_tickets ( $class=null ) 
          {
              $html = null;

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'count_checkbox', 
                                 'class' => "count-{$class}_checkbox",
                                 'id'    => "count-{$class}_checkbox" );
 
              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'id_checkbox', 
                                 'class' => "id-{$class}_checkbox",
                                 'id'    => "id-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'rq_checkbox', 
                                 'class' => "rq-{$class}_checkbox",
                                 'id'    => "rq-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'ords_checkbox', 
                                 'class' => "ords-{$class}_checkbox",
                                 'id'    => "ords-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'actns_checkbox', 
                                 'class' => "actns-{$class}_checkbox",
                                 'id'    => "actns-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'hide_checkbox', 
                                 'class' => "hide-{$class}_checkbox",
                                 'id'    => "hide-{$class}_checkbox" );

              $inputs[] = array( 'value' => 'Update', 
                                 'name'  => 'submit_setting_tickets', 
                                 'class' => "submit-{$class}_inputs",
                                 'id'    => "submit-{$class}_inputs"  );

              foreach( $inputs as $keys => $results ) :

                      $html .= "<div class='setting-input-wrap setting-input-{$class}''>";

                      $label = self::settings_tickets_label( $results['name'], 'label' );

                      if( $results['name'] != 'submit_setting_tickets' ) :
                        $html .= "<div class='wp-raffle_label-div label-div-{$class}'>";
                        $html .= html::label( array( 'text' => $label, 'for' => $label ) );
                        $html .= '</div>';
                      endif;

                      $submit_class = ( $results['name'] == 'submit_setting_tickets' ) ? 'submit-inputs' : null; 
                      $is_checked = self::settings_tickets_is_checked( $results['name'] );

                      $html .= "<div class='wp-raffle_input-div input-div-{$class} {$submit_class}'>";
                      if( $results['name'] != 'submit_setting_tickets' ) :
                        $html .= self::checkbox( $results, $is_checked );
                      else:
                        $html .= self::submit( $results );   
                      endif;
                      $html .= '</div>';

                      $html .= '</div>';

              endforeach; 

              return $html;
          }

          public static function settings_users_label ( $key=null, $elem=null ) 
          {
              $labels = array (

                      'count_checkbox' => array (
                                    'label' => 'Counter (next to the title)',
                                    'details' => ''
                                ),

                      'id_checkbox' => array (
                                    'label' => 'ID Column (show or hide)',
                                    'details' => ''
                                ),

                      'us_checkbox' => array (
                                    'label' => 'User Status (show or hide)',
                                    'details' => ''
                                ),

                      'actns_checkbox' => array (
                                    'label' => 'Action Edit and Delete (show or hide)',
                                    'details' => ''
                                ),

                      'hide_checkbox' => array (
                                    'label' => 'Box (show or hide)',
                                    'details' => ''
                                )
                    );

              return isset( $labels[$key]) ? $labels[$key][$elem] : null;
          }

          public static function settings_users_is_checked ( $key=null ) 
          {
              $get_value = unserialize( get_option( 'wp_raffle_setting_users' ) );

              $is_checked = array(

                            'count_checkbox' => isset( $get_value->count_checkbox ) ? intval( $get_value->count_checkbox ) : null,
                            'id_checkbox'    => isset( $get_value->id_checkbox ) ? intval( $get_value->id_checkbox ) : null,
                            'us_checkbox'    => isset( $get_value->us_checkbox ) ? intval( $get_value->us_checkbox ) : null,
                            'actns_checkbox' => isset( $get_value->actns_checkbox ) ? intval( $get_value->actns_checkbox ) : null,
                            'hide_checkbox'  => isset( $get_value->hide_checkbox ) ? intval( $get_value->hide_checkbox ) : null,

                      );

              return isset( $is_checked[$key] ) ? $is_checked[$key] : null;
          }

          public static function settings_users ( $class=null ) 
          {
              $html = null;

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'count_checkbox', 
                                 'class' => "count-{$class}_checkbox",
                                 'id'    => "count-{$class}_checkbox" );
 
              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'id_checkbox', 
                                 'class' => "id-{$class}_checkbox",
                                 'id'    => "id-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'us_checkbox', 
                                 'class' => "us-{$class}_checkbox",
                                 'id'    => "us-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'actns_checkbox', 
                                 'class' => "actns-{$class}_checkbox",
                                 'id'    => "actns-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'hide_checkbox', 
                                 'class' => "hide-{$class}_checkbox",
                                 'id'    => "hide-{$class}_checkbox" );

              $inputs[] = array( 'value' => 'Update', 
                                 'name'  => 'submit_setting_users', 
                                 'class' => "submit-{$class}_inputs",
                                 'id'    => "submit-{$class}_inputs" );

              foreach( $inputs as $keys => $results ) :

                      $html .= "<div class='setting-input-wrap setting-input-{$class}''>";

                      $label = self::settings_users_label( $results['name'], 'label' );

                      if( $results['name'] != 'submit_setting_users' ) :
                        $html .= "<div class='wp-raffle_label-div label-div-{$class}'>";
                        $html .= html::label( array( 'text' => $label, 'for' => $label ) );
                        $html .= '</div>';
                      endif;

                      $submit_class = ( $results['name'] == 'submit_setting_users' ) ? 'submit-inputs' : null; 
                      $is_checked = self::settings_users_is_checked( $results['name'] );

                      $html .= "<div class='wp-raffle_input-div input-div-{$class} {$submit_class}'>";
                      if( $results['name'] != 'submit_setting_users' ) :
                        $html .= self::checkbox( $results, $is_checked );
                      else:
                        $html .= self::submit( $results );   
                      endif;
                      $html .= '</div>';

                      $html .= '</div>';

              endforeach; 

              return $html;
          }

          public static function settings_events_label ( $key=null, $elem=null ) 
          {
              $labels = array (

                      'count_checkbox' => array (
                                    'label' => 'Counter (show or hide)',
                                    'details' => 'Next to the header title'
                                ),

                      'id_checkbox' => array (
                                    'label' => 'ID Column (show or hide)',
                                    'details' => ''
                                ),

                      'ct_checkbox' => array (
                                    'label' => 'Counter Tickets (show or hide)',
                                    'details' => ''
                                ),

                      'rq_checkbox' => array (
                                    'label' => 'Remaining Quantity Loop (show or hide)',
                                    'details' => ''
                                ),

                      'actns_checkbox' => array (
                                    'label' => 'Action Edit and Delete (show or hide)',
                                    'details' => ''
                                ),

                      'hide_checkbox' => array (
                                    'label' => 'Box (show or hide)',
                                    'details' => ''
                                )
                    );

              return isset( $labels[$key]) ? $labels[$key][$elem] : null;
          }

          public static function settings_events_is_checked ( $key=null ) 
          {
              $get_value = unserialize( get_option( 'wp_raffle_setting_events' ) );

              $is_checked = array(

                            'count_checkbox' => isset( $get_value->count_checkbox ) ? intval( $get_value->count_checkbox ) : null,
                            'id_checkbox'    => isset( $get_value->id_checkbox ) ? intval( $get_value->id_checkbox ) : null,
                            'ct_checkbox'    => isset( $get_value->ct_checkbox ) ? intval( $get_value->ct_checkbox ) : null,
                            'rq_checkbox'  => isset( $get_value->rq_checkbox ) ? intval( $get_value->rq_checkbox ) : null,
                            'actns_checkbox' => isset( $get_value->actns_checkbox ) ? intval( $get_value->actns_checkbox ) : null,
                            'hide_checkbox'  => isset( $get_value->hide_checkbox ) ? intval( $get_value->hide_checkbox ) : null,

                      );

              return isset( $is_checked[$key] ) ? $is_checked[$key] : null;
          }

          public static function settings_events ( $class=null ) 
          {
              $html = null;

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'count_checkbox', 
                                 'class' => "count-{$class}_checkbox",
                                 'id'    => "count-{$class}_checkbox" );
 
              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'id_checkbox', 
                                 'class' => "id-{$class}_checkbox",
                                 'id'    => "id-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'ct_checkbox', 
                                 'class' => "ct-{$class}_checkbox",
                                 'id'    => "ct-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'rq_checkbox', 
                                 'class' => "rq-{$class}_checkbox",
                                 'id'    => "rq-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'actns_checkbox', 
                                 'class' => "actns-{$class}_checkbox",
                                 'id'    => "actns-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'hide_checkbox', 
                                 'class' => "hide-{$class}_checkbox",
                                 'id'    => "hide-{$class}_checkbox" );
              
              $inputs[] = array( 'value' => 'Update', 
                                 'name'  => 'submit_setting_events', 
                                 'class' => "submit-{$class}_inputs",
                                 'id'    => "submit-{$class}_inputs" );

              foreach( $inputs as $keys => $results ) :

                      $html .= "<div class='setting-input-wrap setting-input-{$class}''>";

                      $label = self::settings_events_label( $results['name'], 'label' );

                      if( $results['name'] != 'submit_setting_events' ) :
                        $html .= "<div class='wp-raffle_label-div label-div-{$class}'>";
                        $html .= html::label( array( 'text' => $label, 'for' => $label ) );
                        $html .= '</div>';
                      endif;

                      $submit_class = ( $results['name'] == 'submit_setting_events' ) ? 'submit-inputs' : null; 
                      $is_checked = self::settings_events_is_checked( $results['name'] );

                      $html .= "<div class='wp-raffle_input-div input-div-{$class} {$submit_class}'>";
                      if( $results['name'] != 'submit_setting_events' ) :
                        $html .= self::checkbox( $results, $is_checked );
                      else:
                        $html .= self::submit( $results );   
                      endif;
                      $html .= '</div>';

                      $html .= '</div>';

              endforeach; 

              return $html;
          }

          public static function settings_prizes_label ( $key=null, $elem=null ) 
          {
              $labels = array (

                      'count_checkbox' => array (
                                    'label' => 'Counter (next to the title)',
                                    'details' => ''
                                ),

                      'et_checkbox' => array (
                                    'label' => 'Event Title (show or hide)',
                                    'details' => ''
                                ),

                      'toc_checkbox' => array (
                                    'label' => 'Ticket ORDER count (show or hide)',
                                    'details' => ''
                                ),

                      'actns_checkbox' => array (
                                    'label' => 'Action Edit and Delete (show or hide)',
                                    'details' => ''
                                ),

                      'hide_checkbox' => array (
                                    'label' => 'Box (show or hide)',
                                    'details' => ''
                                )
                    );

              return isset( $labels[$key]) ? $labels[$key][$elem] : null;
          }

          public static function settings_prizes_is_checked ( $key=null ) 
          {
              $get_value = unserialize( get_option( 'wp_raffle_setting_prizes' ) );
              $is_checked = array(

                            'count_checkbox' => isset( $get_value->count_checkbox ) ? intval( $get_value->count_checkbox ) : null,
                            'et_checkbox'    => isset( $get_value->et_checkbox ) ? intval( $get_value->et_checkbox ) : null,
                            'toc_checkbox'   => isset( $get_value->toc_checkbox ) ? intval( $get_value->toc_checkbox ) : null,
                            'actns_checkbox' => isset( $get_value->actns_checkbox ) ? intval( $get_value->actns_checkbox ) : null,
                            'hide_checkbox'  => isset( $get_value->hide_checkbox ) ? intval( $get_value->hide_checkbox ) : null,

                      );

              return isset( $is_checked[$key] ) ? $is_checked[$key] : null;
          }

          public static function settings_prizes ( $class=null ) 
          {
              $html = null;

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'count_checkbox', 
                                 'class' => "count-{$class}_checkbox",
                                 'id'    => "count-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'et_checkbox', 
                                 'class' => "et-{$class}_checkbox",
                                 'id'    => "et-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'toc_checkbox', 
                                 'class' => "toc-{$class}_checkbox",
                                 'id'    => "toc-{$class}_checkbox" );

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'actns_checkbox', 
                                 'class' => "actns-{$class}_checkbox",
                                 'id'    => "actns-{$class}_checkbox" ); 

              $inputs[] = array( 'value' => 1, 
                                 'name'  => 'hide_checkbox', 
                                 'class' => "hide-{$class}_checkbox",
                                 'id'    => "hide-{$class}_checkbox" );

              $inputs[] = array( 'value' => 'Update', 
                                 'name'  => 'submit_setting_prizes', 
                                 'class' => "submit-{$class}_inputs",
                                 'id'    => "submit-{$class}_inputs" );

              foreach ( $inputs as $keys => $results ) : 

                      $html .= "<div class='setting-input-wrap setting-input-{$class}''>";

                      $label = self::settings_prizes_label( $results['name'], 'label' );

                      if( $results['name'] != 'submit_setting_prizes' ) :
                        $html .= "<div class='wp-raffle_label-div label-div-{$class}'>";
                        $html .= html::label( array( 'text' => $label, 'for' => $label ) );
                        $html .= '</div>';
                      endif;

                      $submit_class = ( $results['name'] == 'submit_setting_prizes' ) ? 'submit-inputs' : null; 
                      $is_checked = self::settings_prizes_is_checked( $results['name'] );

                      $html .= "<div class='wp-raffle_input-div input-div-{$class} {$submit_class}'>";
                      if( $results['name'] != 'submit_setting_prizes' ) :
                        $html .= self::checkbox( $results, $is_checked );
                      else:
                        $html .= self::submit( $results );   
                      endif;
                      $html .= '</div>';

                      $html .= '</div>'; 

              endforeach; 

              return $html;
          }

          /**
            * settings forms functional handler
            * END
          **/

          // END
    }
}
?>