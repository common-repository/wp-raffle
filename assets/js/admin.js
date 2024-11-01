jQuery ( function() 
    { 
          var $params = jQuery;
          var $tabs   = '.accounts--inner_tabs.tab2, .accounts--inner_tabs.tab3, .accounts--inner_tabs.tab4';
          var $class  = 0;
          
          // .nextAll(':lt(2)')
          // .nextAll().slice(0, 2)

          // .prevAll(':lt(2)')
          // .prevAll().slice(0, 2)

          $params( '#wp-raffle__wrap #wp-raffle_boxies-dropdown' ).click ( function(e)
            {
                $params(this).next().next().toggle();
                console.log( 'boxies control : dropdown' );
            }
          );

          $params( '#wp-raffle__wrap .wp-raffle_dropdown .wp-raffle_dropdown-submenu' ).click ( function(e)
            {   
                var $classes = $params( this ).attr( 'class' ).split( ' ' );
                
                if( $classes[2] != 'active' ) {
                    $params(this).addClass( 'active' );
                    $params( '.boxies_'+$classes[1] ).show();   
                } else {
                    $params(this).removeClass( 'active' );
                    $params( '.boxies_'+$classes[1] ).hide(); 
                }

                console.log( 'boxies control : dropdown selected - ' + $classes[1] );
            }
          );

          /**
            * wp ajaxs custom function
            * actions scripting
            * events handler - libraries
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
                           $params( $loads ).addClass( 'true' );
                      },
                      error : function( xhr, status, err ) {
                           // Handle errors
                      },
                      success : function( html, data ) {
                           console.log( html )  
                           $params( $setups ).html( html );
                           $params( $loads ).removeClass( 'true' );
                      }
              } ) . done ( function( html, data ) {
                      $params( $loads ).removeClass( 'true' );
                  }
              );       
          }

          /**
            * wp media custom function
            * actions scripting
            * events handler - libraries
          **/

          function media_uploader_add ( $metabox, $inputbox ) 
          {

              var $frame,
                  $meta = $metabox,
                  $input= $inputbox;
              
              if ( $frame ) 
              {
                  $frame.open(); 
                  return;
              }

              $frame = wp.media( 
                  {
                      title: 'Select or Upload Media Of Your Chosen Persuasion',
                      button: {
                        text: 'Use this media'
                      },
                      multiple: false
                  }
              );

              $frame.on( 'select', function() 
                  {
                      var $attachment = $frame.state().get('selection').first().toJSON();
                      // $imgContainer.append( '<img src="'+$attachment.url+'" alt="" style="" />' );

                      $input.val( $attachment.url );
                  }
              );

              $frame.open();
          }  

          function media_uploader_remove ( $metabox, $inputbox ) 
          {
              var $frame,
                  $meta = $metabox,
                  $input= $inputbox,
                  $empty= '';

              $input.val( $empty );
          }  

          $params( '.prizes-input #prize_browse_submit' ).click( function( event )
            {   
                var $metabox = $params( '.wp-raffle__add--prizes-wraps .prizes-input' );
                var $inputbox = $metabox.find( '.image-prizes_inputs' );
                media_uploader_add( $metabox, $inputbox );
                return false;
            }
          );
      
          /**
            * accounts tab
            * actions scripting
            * events handler
          **/
          
          $params( $tabs ).hide();

          $params( '.wp-raffle__accounts--title .accounts-tabs' ).each ( function (i)
              {
                  if( i == 0 ) {
                      var $class = $params(this).attr( 'class' ).split( ' ' );
                      $params('#'+$class[2]).addClass( 'active' );   
                  }
              }
          );
          
          $params( '.accounts-tabs' ).click ( function() 
              {
                   var $classes = $params(this).attr( 'class' ).split( ' ' );

                   $params( '.accounts--inner_tabs' ).hide();
                   $params( '.accounts--inner_tabs.'+$classes[2] ).show();

                   $params( '.accounts--inner_tabs' ).each( function(i) 
                      {
                          var $classes_val = $params(this).attr( 'class' ).split( ' ' );
                          $params('#'+$classes_val[2]).removeClass( 'active' ); 
                      }
                   );

                   if( $params('#'+$classes[2]).hasClass( "active" ) ) {
                       $params('#'+$classes[2]).removeClass( 'active' ); 
                   } else {
                       $params('#'+$classes[2]).addClass( 'active' );    
                   }

                   return false;
              }
          );

          /**
            * boxies
            * actions scripting
            * events handler
          **/

          $params( '#wp-raffle__wrap div' ).each ( function() 
            {
                var $class = $params(this).attr( 'class' ); 
                // console.log( $class );   
            }
          );

          $params( 'a.delete-boxies' ).click ( function(e) 
            {
                e.preventDefault();
                var $get_1 = $params(this).prev().parent().parent().attr( 'class' ).split( ' ' );
                var $get_2 = $get_1[1].split( '_' );
                var $get_3 = $get_2[1];

                $params(this).prev().parent().parent().hide();
                $params( '.wp-raffle_dropdown-submenu.'+$get_3 ).removeClass('active');
                console.log( $get_3 ); 
                return false;
            }
          );

          // END
    }
);