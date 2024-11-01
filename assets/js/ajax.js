jQuery( function() 
    {
        var $params = jQuery;
        var $schedule_key = ajax_script.times_schedule_key;
        var $run_defaulted = '<p class="defaulted">click the play/run button to select randoms ' + $schedule_key + '</p>';   
        
        function ajax_results_classes( $this, $flts )
        {
            if( $flts == 'before' ){
               $params( $this ).addClass( 'before--ajax' );
            } else if ( $flts == 'error' ){
               $params( $this ).removeClass( 'before--ajax' );
               $params( $this ).addClass( 'error--ajax' );  
            } else if ( $flts == 'success' ){
               $params( $this ).removeClass( 'before--ajax' );
               $params( $this ).addClass( 'success--ajax' );   
            } else if ( $flts == 'done' ) {
               $params( $this ).removeClass( 'before--ajax' );
               $params( $this ).removeClass( 'success--ajax' );
               $params( $this ).addClass( 'done--ajax' );  
            }      
        }
        
        /**
          * wp ajaxs custom function
          * actions scripting
          * events handler
        **/
          
        function ajax_actions ( actions, vals, sets, load )
        {
              var $scripts = ajax_script.ajax_url;
              var $values  = vals;
              var $setups  = sets;  
              var $loads   = load;
    
              $params.ajax ( {
                      data: { 
                         action : actions, 
                         value  : $values,
                      },
                      type   : 'POST',
                      url    : $scripts,
                      beforeSend : function() { 
                           $params( $loads ).addClass( 'ajaxs-true' );
                      },
                      error : function( xhr, status, err ) {
                           // Handle errors
                      },
                      success : function( html, data ) {
                           console.log( html );  
                           $params( $setups ).html( html );
                           $params( $loads ).removeClass( 'ajaxs-true' );
                      }
              } ) . done ( function( html, data ) {
                      $params( $loads ).removeClass( 'ajaxs-true' );
                  }
              );       
        }
        
        function ajax_actions_tickets ( actions, vals, sets, load )
        {
              var $scripts = ajax_script.ajax_url;
              var $values  = vals;
              var $setups  = sets;  
              var $loads   = load;
              
              var $admin   = ajax_script.admin_url;
    
              $params.ajax ( {
                      data: { 
                         action : actions, 
                         value  : $values,
                      },
                      type   : 'POST',
                      url    : $scripts,
                      beforeSend : function() {
                           ajax_results_classes($setups,'before');    
                           $params( $loads ).addClass( 'ajaxs-true' );
                      },
                      error : function( request,error ) {
                           ajax_results_classes($setups,'error');    
                           $params( $setups ).addClass( 'error--ajax' );
                      },
                      success : function( data ) {
                           console.log( data );  
                           ajax_results_classes($setups,'success');    
                           $params( $loads ).removeClass( 'ajaxs-true' );
                           window.location = $admin+'/admin.php?page=wp_raffle';
                      }
              } ) . done ( function( html, data ) {
                      ajax_results_classes($setups,'done');       
                      $params( $loads ).removeClass( 'ajaxs-true' );
                  }
              );       
        }  
        
        $params( 'span.wp-raffle__selected.loader' ).click( function() 
            {
                var $ids = $params(this).find( '.tickets_id--vals' ).val();
                ajax_actions_tickets( 'ajaxs_get_tickets', $ids, '.ajaxs-results', this ); 
            }
        );
        
        /**
          * wp ajaxs custom function
          * actions scripting
          * set timer data enabled handler
        **/
        
        function ajax_actions_set_timer ( actions, vals, sets, load ) 
        {
            var $scripts = ajax_script.ajax_url;
            var $values  = vals;
            var $setups  = sets;  
            var $loads   = load;
              
            var $admin   = ajax_script.admin_url;   
            
            $params.ajax ( {
                      data: { 
                         action : actions, 
                         value  : $values,
                      },
                      type   : 'POST',
                      url    : $scripts,
                      beforeSend : function() {
                           ajax_results_classes($setups,'before');    
                           $params( $loads ).addClass( 'ajaxs-true' );
                      },
                      error : function( request,error ) {
                           ajax_results_classes($setups,'error');    
                           $params( $setups ).addClass( 'error--ajax' );
                      },
                      success : function( data ) {
                           console.log( data );  
                           ajax_results_classes($setups,'success');    
                           $params( $loads ).removeClass( 'ajaxs-true' );
                           window.location = $admin+'/admin.php?page=time_schedule_wp_raffle';
                      }
              } ) . done ( function( html, data ) {
                      ajax_results_classes($setups,'done');       
                      $params( $loads ).removeClass( 'ajaxs-true' );
                  }
              );        
        }
        
        $params( 'span.time_schedule_set-timer' ).click( function() 
            {
                var $vals = $params(this).find( 'input' ).val();
                $params(this).parent().next().show();
            }
        );
        
        $params( 'span.time-set_schedule_submit.loader' ).click( function() 
            {
                var $vals = $params(this).prev().val();
                var $ids = $params(this).find('input').val();
                
                var $datas = {'id':$ids, 'time':$vals};
                ajax_actions_set_timer( 'ajaxs_set_timer', $datas, '.ajaxs-results', this );
            }
        );
        
        /**
          * wp ajaxs custom function
          * actions scripting
          * action randoms data enabled handler
        **/
        
        function ajax_actions_selected_randoms ( actions, vals, sets, load ) 
        {
            var $scripts = ajax_script.ajax_url;
            var $values  = vals;
            var $setups  = sets;  
            var $loads   = load;
              
            var $admin = ajax_script.admin_url;
            
            var $run_selected = '<p class="selecting">selecting randoms</p>';
            var $is_run_empty_selected = 'selected randoms';

            $params.ajax ( {
                      data: { 
                         action : actions, 
                         value  : $values,
                      },
                      type   : 'POST',
                      url    : $scripts,
                      beforeSend : function() {
                           ajax_results_classes($setups,'before');    
                           $params( $loads ).addClass( 'ajaxs-true' );
                           $params( $setups ).html( $run_selected );
                      },
                      error : function( request,error ) {
                           ajax_results_classes($setups,'error');    
                           $params( $setups ).addClass( 'error--ajax' );
                      },
                      success : function( data ) {
                           console.log( data );  
                           ajax_results_classes($setups,'success');    
                           $params( $loads ).removeClass( 'ajaxs-true' );
                           $params( $setups ).html( '<p class="selected">' + $is_run_empty_selected + ' <span>' + data + 0 + '</span></p>' );
                      }
              } ) . done ( function( html, data ) {
                      ajax_results_classes($setups,'done');       
                      $params( $loads ).removeClass( 'ajaxs-true' );
                      window.location = $admin+'/admin.php?page=time_schedule_wp_raffle';
                  }
              );        
        }
        
        $params( '.time-schedule_manager .ajaxs-results' ).html( $run_defaulted );
        
        $params( 'span.randoms-button-start' ).click( function() 
            {
                ajax_actions_selected_randoms( 'ajaxs_action_randoms_selected', 1, '.time-schedule_manager .ajaxs-results', this );
            }
        );
        
        /**
          * wp ajaxs custom function
          * actions scripting
          * action paypal validate data enabled handler
        **/

        function ajax_actions_paypal_validate ( actions, vals, sets, load )
        {
              var $scripts = ajax_script.ajax_url;
              var $values  = vals;
              var $setups  = sets;  
              var $loads   = load;
    
              $params.ajax ( {
                      data: { 
                         action : actions, 
                         value  : $values,
                      },
                      type   : 'POST',
                      url    : $scripts,
                      beforeSend : function() { 
                           $params( $loads ).addClass( 'ajaxs-true' );
                      },
                      error : function( xhr, status, err ) {
                           // Handle errors
                      },
                      success : function( result ) { 
                           $params( $loads ).removeClass( 'ajaxs-true' );
                           setTimeout( function() {
                              window.location.href = result;
                           }, 2000);
                      }
              } ) . done ( function( html, data ) {
                      $params( $loads ).removeClass( 'ajaxs-true' );
                  }
              );       
        }

        $params( '.paypal-submit_validate #paypal_submit' ).click ( function() 
            {
                var $name  = $params( '#name-settings_inputs' ).val();
                var $email = $params( '#email-settings_inputs' ).val();
                var $keyID = $params( '#pid-settings_inputs' ).val();
                
                var $datas = { 'name' : $name, 
                               'email' : $email, 
                               'key_id' : $keyID };

                ajax_actions_paypal_validate( 'ajaxs_action_paypal_validate', $datas, '.settings-wrap .ajaxs-results', this );

                return false;
            }
        );


        /**
          * wp ajaxs custom function
          * actions scripting
          * action sort validate data enabled handler
        **/

        var fixHelper=function( e, ui ) {
             ui.children().each( function () {
                    $params(this).width( $params(this).width() );
                 }
             );
             return ui;
          };

          function ajax_actions_sortable_handler ( actions, vals, sets, load )
        {
              var $scripts = ajax_script.ajax_url;
              var $values  = vals;
              var $setups  = sets;  
              var $loads   = load;
    
              $params.ajax ( {
                      data: { 
                         action : actions, 
                         value  : $values,
                      },
                      type   : 'POST',
                      url    : $scripts,
                      beforeSend : function() { 
                           $params( $loads ).addClass( 'ajaxs-true' );
                      },
                      error : function( xhr, status, err ) {
                           // Handle errors
                      },
                      success : function( html, data ) { 
                           $params( $loads ).removeClass( 'ajaxs-true' );
                           console.log( html );
                      }
              } ) . done ( function( html, data ) {
                      $params( $loads ).removeClass( 'ajaxs-true' );
                  }
              );       
        }

          $params( '#wp-raffle__wrap' ).sortable( 
            {
                 items: '.meta-box-sortables',
                 helper: fixHelper,
                 appendTo: document.body,
                 revert: 100,
                 placeholder: 'ui-state-highlight',
                 stop: function( event, ui ) {

                     var $datas = [];
                     $params( 'input[type=hidden].box' ).each( function(i)
                         {
                            var $classes = $params( this ).attr( 'class' ).split( ' ' );
                            $datas[i] = $classes[1]+'_value';
                         }
                     );
                     

                     ajax_actions_sortable_handler( 'ajaxs_action_sortable', $datas, '', this )
                 } 
            }
          );

        // END
    }
);