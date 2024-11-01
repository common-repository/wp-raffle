<?php if( ! class_exists( 'action' ) ) 
{
    
     class action extends db_action
     {
          
          public static $tbls = array( '_tickets', '_events', '_user', '_prizes', '_settings' );

          public function __construct () 
          {
                parent::__construct();
          }
          
          // action event submit - tickets
          // user the querys at the top
          
          public static function ticket_select ()
          {
                global $wpdb;
                
                $inputs = input::post_is_object();
                
                $id = user_control::get_id();
                
                $qtys_value = db::ticket_get_values( 'qty', $inputs->value )-1;
                $ords_value = db::ticket_get_values( 'orders', $inputs->value )+1;
                
                self::updates( 
                    'raffle'.self::$tbls[0],
                    array( 'orders' => $ords_value, 'qty' => $qtys_value ), 
                    array( 'id' => intval( $inputs->value ) ),
                    array( '%d', '%d' ),
                    array( '%d' ) 
                );
                
                $user_id_exists = db::user_id_exists( 'user_id', $id );
                $ticket_id_exists = db::user_id_exists( 'ticket_id', $inputs->value );

                if( $user_id_exists != true ) 
                {
                    
                    self::inserts(
                        'raffle'.self::$tbls[2],
                        array( 
                            'user_id' => $id, 
                            'ticket_id' => $inputs->value, 
                            'value' => 1 
                        ),
                        array( '%d', '%d', '%d' )
                    );
                    
                    _e( '<p>access submit - 1</p>', 'wp_raffle_submit_message' );
                           
                } else {
                    
                    $user_filer = db::user_id_exists_filter( $id, $inputs->value );
                    
                    if( $ticket_id_exists != true ) 
                    {
                        
                        self::inserts(
                            'raffle'.self::$tbls[2],
                            array( 
                                'user_id' => $id, 
                                'ticket_id' => $inputs->value, 
                                'value' => 1 
                            ),
                            array( '%d', '%d', '%d' )
                        );  
                        
                        _e( '<p>access submit - 2</p>', 'wp_raffle_submit_message' );
                          
                    } else {
                        
                        if( ! $user_filer ) 
                        {
                            
                            self::inserts(
                                'raffle'.self::$tbls[2],
                                array( 
                                    'user_id' => $id, 
                                    'ticket_id' => $inputs->value, 
                                    'value' => 1 
                                ),
                                array( '%d', '%d', '%d' )
                            );
                            
                            _e( '<p>access submit - 3</p>', 'wp_raffle_submit_message' );
                                  
                        } else {
                            
                            $values = db::user_get_values_filter( 'value', $id, $inputs->value );
                            
                            self::updates( 
                                'raffle'.self::$tbls[2],
                                array( 'value' => $values+1 ),
                                array( 'user_id' => $id, 'ticket_id' => intval( $inputs->value ) ),
                                array( '%d' ),
                                array( '%d', '%d' ) 
                            );
                            
                            _e( '<p>access submit - 4</p>', 'wp_raffle_submit_message' );
                        } 
                            
                    }

                }

          }

          // action event submit - tickets
          // user the querys at the top
          // END

          /**
            * timer action event
            * control submit element objects
          **/
          
          public static function set_timer ()
          {
                global $wpdb;
                
                $inputs = input::post_is_object();
                
                $is_status = $inputs->value['time'] != '0000-00-00 00:00:00' ? 1 : 0;

                self::updates( 
                    'raffle'.self::$tbls[1],
                    array( 'time' => $inputs->value['time'], 'time_set' => $is_status ),
                    array( 'id' => intval( $inputs->value['id'] ) ),
                    array( '%s', '%d' ),
                    array( '%d' ) 
                );
          }
          
          public static function selected_randoms ()
          {
                global $wpdb;
                
                $html = null;
                
                $inputs = input::post_is_object();    
                $html .= db::timer_get_data(1);

                echo $html;
          }

          /**
            * timer action event
            * control submit element objects
            * END
          **/

          /**
            * tickets action event
            * control submit element objects
          **/
          
          public static function add_tickets () 
          {
                global $wpdb;
                
                $inputs = input::post_is_object();  
                $id     = input::get_is_object_element( 'edit_tickets' );
                
                if( isset( $inputs->submit_tickets ) ) 
                {
                    
                    if( $id != 0 ) 
                    {

                        self::updates( 
                            'raffle'.self::$tbls[0],
                            array( 
                                'event_id' => $inputs->event_selects,
                                'num' => 1, 
                                'time' => $inputs->time_inputs, 
                                'name' => $inputs->name_inputs,
                                'text' => $inputs->descr_inputs,
                                'url' => $inputs->url_inputs,
                                'price' => $inputs->price_inputs,
                                'qty' => $inputs->quantity_inputs
                            ),
                            array( 'id' => intval( $id ) ),
                            array( '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%d' ),
                            array( '%d' ) 
                        );

                    } else {

                        $validate = form::tickets_form_validate( $inputs );

                        if( ! in_array( false, $validate ) ) 
                        {

                            self::inserts(
                                'raffle'.self::$tbls[0],
                                array( 
                                    'event_id' => $inputs->event_selects,
                                    'num' => 1, 
                                    'time' => $inputs->time_inputs, 
                                    'name' => $inputs->name_inputs,
                                    'text' => $inputs->descr_inputs,
                                    'url' => $inputs->url_inputs,
                                    'price' => $inputs->price_inputs,
                                    'qty' => $inputs->quantity_inputs
                                ),
                                array( '%d', '%d', '%s', '%s', '%s', '%s', '%d', '%d' )
                            );

                        }

                    }

                }
          }

          public static function delete_tickets () 
          {
                global $wpdb;  

                $id = input::get_is_object_element( 'delete_tickets' );

                if( isset( $inputs->delete_tickets ) ) 
                {

                    if( $id != 0 ) 
                    {

                        self::deletes( 
                            'raffle'.self::$tbls[0],
                            array( 'id' => intval( $id ) ),
                            array( '%d' ) 
                        );

                        redirect::filter( page_rounter::url( 'wp_raffle', false ) );
                    }
                }
          }

          /**
            * tickets action event
            * control submit element objects 
            * END
          **/

          /**
            * events action event
            * control submit element objects
          **/

          public static function add_events () 
          {
                global $wpdb;
                
                $inputs = input::post_is_object();  
                $id     = input::get_is_object_element( 'edit_events' );
                
                if( isset( $inputs->submit_events ) ) 
                {
                    
                    if( $id != 0 ) 
                    {
                        self::updates( 
                            'raffle'.self::$tbls[1],
                            array( 
                                'num' => 1, 
                                'time' => $inputs->time_inputs, 
                                'name' => $inputs->name_inputs,
                                'text' => $inputs->descr_inputs,
                                'url' => $inputs->url_inputs
                            ),
                            array( 'id' => intval( $id ) ),
                            array( '%d', '%s', '%s', '%s', '%s' ),
                            array( '%d' ) 
                        );

                    } else {

                        $validate = form::events_form_validate( $inputs );

                        if( !in_array( false, $validate )) 
                        {
                            // actions
                            self::inserts(
                                'raffle'.self::$tbls[1],
                                array( 
                                    'num' => 1, 
                                    'time' => $inputs->time_inputs, 
                                    'name' => $inputs->name_inputs,
                                    'text' => $inputs->descr_inputs,
                                    'url' => $inputs->url_inputs
                                ),
                                array( '%d', '%s', '%s', '%s', '%s' )
                            );
                        }
                    }
                }
          }

          public static function delete_events () 
          {
                global $wpdb;  

                $inputs = input::get_is_object();
                $id = input::get_is_object_element( 'delete_events' );

                if( isset( $inputs->delete_events ) ) 
                {
                    if( $id != 0 ) 
                    {
                        self::deletes( 
                            'raffle'.self::$tbls[1],
                            array( 'id' => intval( $id ) ),
                            array( '%d' ) 
                        );

                        redirect::filter( page_rounter::url( 'wp_raffle', false ) );

                    }
                }
          } 

          /**
            * events action event
            * control submit element objects 
            * END
          **/

          /**
            * prizes action event
            * control submit element objects
          **/

          public static function add_prizes () 
          {
                global $wpdb;
                
                $inputs = input::post_is_object();  
                $id     = input::get_is_object_element( 'edit_prizes' );
                
                if( isset( $inputs->submit_prizes ) ) 
                {
                    if( $id != 0 ) 
                    {
                        self::updates( 
                            'raffle'.self::$tbls[3],
                            array( 
                                'ticket_id' => $inputs->tickets_selects, 
                                'event_id' => $inputs->events_selects,
                                'num' => 1, 
                                'name' => $inputs->name_inputs,
                                'text' => $inputs->descr_inputs,
                                'url' => $inputs->url_inputs,
                                'image' => $inputs->image_inputs
                            ),
                            array( 'id' => intval( $id ) ),
                            array( '%d', '%d', '%d', '%s', '%s', '%s', '%s' ),
                            array( '%d' ) 
                        ); 

                    } else {
                        
                        $validate = form::prizes_form_validate( $inputs );

                        if( ! in_array( false, $validate ) ) 
                        {
                            self::inserts(
                                'raffle'.self::$tbls[3],
                                array( 
                                    'ticket_id' => $inputs->tickets_selects, 
                                    'event_id' => $inputs->events_selects,
                                    'num' => 1, 
                                    'name' => $inputs->name_inputs,
                                    'text' => $inputs->descr_inputs,
                                    'url' => $inputs->url_inputs,
                                    'image' => $inputs->image_inputs
                                ),
                                array( '%d', '%d', '%d', '%s', '%s', '%s', '%s' )
                            );
                        }
                    }
                }
          }

          public static function delete_prizes () 
          {
                global $wpdb;  

                $inputs = input::get_is_object();
                $id = input::get_is_object_element( 'delete_prizes' );

                if( isset( $inputs->delete_prizes ) ) 
                {
                    if( $id != 0 ) 
                    {
                        self::deletes( 
                            'raffle'.self::$tbls[3],
                            array( 'id' => intval( $id ) ),
                            array( '%d' ) 
                        );

                        redirect::filter( page_rounter::url( 'wp_raffle', false ) );
                    }
                }
          } 

          /**
            * prizes action event
            * control submit element objects 
            * END
          **/

          /** 
            * settings action event
            * control submit element objects
          **/

          public static function action_settings_loader () 
          {
                $gets = input::get_is_object_element( 'setting' );
                $default = 'paypal';

                switch( $gets ) :

                case 'tickets' :
                self::action_setting( $gets );
                break;

                case 'events' :
                self::action_setting( $gets );
                break;

                case 'prizes' :
                self::action_setting( $gets );
                break;

                case 'users' :
                self::action_setting( $gets );
                break;

                default;
                self::action_paypal( $default );

                endswitch;
          }

          public static function action_setting ( $key=null ) 
          {
                global $wpdb;

                $handler = 'wp_raffle_setting_'.__( $key, 'wp-raffle-action' );
                $submit  = 'submit_setting_'.__( $key, 'wp-raffle-submit' );

                $posts = input::post_is_object();  
                $gets = input::get_is_object();

                if ( isset( $gets->setting ) ) :

                    if( isset( $posts->$submit ) AND 
                        $gets->setting == $key ) 
                    {

                        $value = serialize( $posts );
                        $get_value = get_option( $handler );

                        if( ! is_null( $get_value ) 
                            AND ! empty( $get_value ) ) {
                            update_option( $handler, $value );
                        } else {
                            add_option( $handler, $value );   
                        }
                        
                    }    

                endif;
          }

          public static function action_paypal ( $key=null ) 
          {
                global $wpdb;

                $posts   = input::post_is_object(); 
                $handler = 'wp_raffle_setting_'.__( $key, 'wp-raffle-action' );

                if( isset( $posts->submit_settings ) ) 
                {
                    $value = serialize( $posts );
                    $get_value = get_option( $handler );

                    if( ! is_null( $get_value ) 
                        AND ! empty( $get_value ) ) {
                        update_option( $handler, $value );
                    } else {
                        add_option( $handler, $value );   
                    }    
                }
          }

          /** 
            * settings action event
            * control submit element objects
            * END
          **/

          /** 
            * paypal action event
            * control submit element objects
          **/

          public static function total_purchase_cost_amount () 
          {
              $userdata = user_control::get_userdata_objects();

              if( is_object( $userdata ) and ! is_null( $userdata ) ) :
                  
                  $ids = $userdata->ID; 
                  
                  $querys = users::user_get_data( $ids );
                  
                  foreach( $querys as $query ) 
                  {
                       $ticket_id = $query->ticket_id;
                       $value = $query->value;

                       $total = tickets::get_total_prices( 'tickets', $ticket_id, $value );
                       $cost += $total[0];
                  }
                  
                  return money_format::cash( $cost, 0 );
                  
              endif;           
          }

          public static function action_paypal_email_value ( $key='email' ) 
          {
                $gateway = new paypal_gateway( true );

                $get_value = unserialize( get_option( 'wp_raffle_setting_paypal' ) );  
                $posts = input::post_is_array();

                if( $gateway::get_email() == true AND $key == 'email' ) 
                {
                    $value = isset( $get_value->email_inputs ) ? trim( $get_value->email_inputs ) : trim( $posts[$key] );
                } else {
                    $value = null;
                }

                return $value;
          }

          public static function action_paypal_validate () 
          {
                global $wpdb;

                $gateway = new paypal_gateway( true );

                $get_value = unserialize( get_option( 'wp_raffle_setting_paypal' ) );

                $path  = page_rounter::url( 'settings_wp_raffle', array( 'setting' => 'paypal-validate' ) );
                $posts = input::post_is_object();

                if( isset( $posts->action ) ) 
                {

                    $name  = trim( $posts->value['name'] );
                    $email = trim( $posts->value['email'] ); 
                    $key_id= trim( $posts->value['key_id'] );

                    $hidden_value = array( 
                                'business'  => self::action_paypal_email_value(),
                                'cmd'           => '_xclick',
                                'item_name'     => __( 'WP Raffle : Tickets', 'wp-raffle' ),
                                'item_number'   => 0,
                                'amount'        => self::total_purchase_cost_amount(),
                                'currency_code' => 'USD',
                                'cancel_return' => admin_url( $path ),
                                'return' => admin_url( $path )
                            );

                    foreach(  $hidden_value as $key => $value ) 
                    {
                        $results .= $key != 'return' ? '&' . $key . '=' . $value : '&' . $key . '=' . $value;
                    }

                    var_dump( $gateway->sandbox_url .$results );
                              
                }

          }

          /** 
            * paypal action event
            * control submit element objects
            * END
          **/

          /** 
            * sortable action event
            * control submit element objects
          **/

          public static function action_sortable () 
          {
                global $wpdb;

                $id = 1;
                $posts = input::post_is_object();
                $get_data = settings::get_value();

                if( isset( $posts->action ) ) :

                    $get_sorted = serialize( $posts->value );

                    if( ! is_null( $get_data->id ) AND
                    ! empty( $get_data->id ) ) 
                    {

                        self::updates( 
                            'raffle'.self::$tbls[4],
                            array(
                                'boxies_sort' => $get_sorted,
                            ),
                            array( 'id' => intval( $id ) ),
                            array( '%s' ),
                            array( '%d' ) 
                        ); 

                    } else {

                        self::inserts(
                            'raffle'.self::$tbls[4],
                            array(
                                'boxies_sort' => $get_sorted,
                            ),
                            array( '%s' )
                        );
                    }

                endif;

          }

          /** 
            * sortable action event
            * control submit element objects
            * END
          **/

          // END
     }
}
?>