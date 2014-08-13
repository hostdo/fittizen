<?php

$user = JFactory::getUser();
$fitinfo = bll_fitinfos::getProfileByUserId($user->id);
$lang = new languages(AuxTools::GetCurrentLanguageIDJoomla());

$languages = $fitinfo->setCheckboxValues('title_native',
        'lang_id',array($lang), 'lang_id', "",
        languages::GetLanguages());

$location = new bll_locations($fitinfo->location_id);
$gender = new bll_gender($fitinfo->gender_id);
$genders =  new fittizen_gender_lang(-1);
$genders_arr = $genders->setCheckboxValues(
        'name', 'gender_id', 
        array($gender), 'id',
        null, $genders->findAll('lang_id', $lang->lang_id));

$form = Form::getInstance();

$perm=$fitinfo->get_permissions();
if($perm->public == 1)
{
    $profile_privacy_arr = array("#__1"=>JText::_('COM_FITTIZEN_PUBLIC'),"0"=>JText::_('COM_FITTIZEN_PRIVATE'));
}
else
{
    $profile_privacy_arr = array("1"=>JText::_('COM_FITTIZEN_PUBLIC'),"#__0"=>JText::_('COM_FITTIZEN_PRIVATE'));
}
?>
<script src="https://apis.google.com/js/client:plusone.js"></script>
<script>
    
    var fb_login = 0;
    var gp_login = 0;
    var tw_login = 0;
    
    
    jQuery(function() {
        
        jQuery("#sync-fb").click(function(){
            FB.login(function(response) {
                statusChangeCallback(response);
            });
        });
        
        jQuery("#langswitch").change(function(){
           jQuery("#edit-form").submit(); 
        });
        
        jQuery("#sync-gp").click(function(){
            
            var r_params = {
            client_id: '<?php echo GPLUS_CLIENT_ID; ?>',
            immediate:false,
            scope: "https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email"
            };
            gapi.auth.authorize(r_params, loginFinishedCallback);
        });
        
        jQuery("#sync-tw").click(function(){
            jQuery("#tw-button-login").click();
        });
        
        
        var gpid = <?php echo json_encode($fitinfo->gplus_id) ?>;

          if(gpid != null)
          {
              jQuery.ajax({
                  url:"https://www.googleapis.com/plus/v1/people/"+gpid+"?key=AIzaSyA8oRtWdB_iU1tGQPrDPxcFgCEo2gBwO7o",
              }).done(function(data){
                  if(data)
                  {
                      jQuery("#link-gp").hide();
                      var html='<?php echo $fitinfo->name ?> <?php echo $fitinfo->last_name ?> <a href="#" onclick="return unlink_social_account(\'g+\', <?php echo json_encode((int)$fitinfo->id) ?>)">[x]<?php echo JText::_('COM_FITTIZEN_REMOVE'); ?></a><img id="gpimage" src="'+data.image.url+'" />';
                      document.getElementById('gpstatus').innerHTML = html;
                  }
              });
          } 
        
        
        jQuery( "#bday" ).datetimepicker({ format:"Y-m-d", lang:"<?php echo $lang->sef ?>", timepicker:false});
        
        jQuery( "#location" ).autocomplete({
            source: function( request, response ) {
              jQuery.ajax({
                url:"./index.php?option=com_fittizen&task=find_locations&format=json", 
                data:{address:jQuery("#location").val()},
                dataType:"json",
                success: function( data ) {
                  response( jQuery.map( data, function( item ) {
                    return {
                      label: item.address,
                      value: item.id
                    }
                  }));
                }
              });
            },
            minLength: 2,
            open: function() {
              jQuery( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
              jQuery( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            },
            select: function( event, ui ) {
              jQuery("#location").val(ui.item.label);
              jQuery("#location_id").val(ui.item.value);
              event.stopPropagation();
              return false;
            }
          });
          jQuery("#profile_code").change(function(){
              jQuery.ajax({
                url:"./index.php?option=com_fittizen&task=validate_profile_code&format=json", 
                data:{code:jQuery("#profile_code").val()},
                dataType:"json",
                    success: function( data ) {
                        var html="";
                        if(jQuery("#profile_code").val() === "<?php echo $fitinfo->profile_code ?>")
                        {
                            html="";
                        }
                        else if(data !== true)
                        {
                            html="<?php echo JText::_('COM_FITTIZEN_PROFILE_CODE_UNAVAILABLE'); ?>";
                        }
                        else
                        {
                            html="<?php echo JText::_('COM_FITTIZEN_PROFILE_CODE_AVAILABLE'); ?>";
                        }
                        jQuery('#profile_code_state').html(html);
                    }
               });
          });
    });
        
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      var fbid = <?php echo json_encode($fitinfo->fb_id) ?>;
      if(fbid != null)
      {
          FB.api('/'+fbid+'/picture', function(response){
              if(response.data != undefined && response.data.url != undefined)
              {
                jQuery("#link-fb").hide();
                var html='<?php echo $fitinfo->name ?> <?php echo $fitinfo->last_name ?> <a href="#" onclick="return unlink_social_account(\'fb\', <?php echo json_encode((int)$fitinfo->id) ?>)">[x]<?php echo JText::_('COM_FITTIZEN_REMOVE'); ?></a><img id="fbimage" src="" />';
                document.getElementById('fbstatus').innerHTML = html;
                jQuery("#fbimage").attr("src",response.data.url);
              }
          });
      }
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '<?php echo FB_API_ID ?>',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.0' // use version 2.0
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));


  function unlink_social_account(type, fiid)
  {
      var strtype=type;
      jQuery.ajax({
          url:"<?php echo JRoute::_('/index.php?option=com_fittizen&task=update_social_account'); ?>",
          data:{type:type,pid:fiid}
      }).done(function(data){
          if(data == 1)
          {
              switch(strtype)
              {
                  case "fb":
                      if(fb_login==1)
                      {
                        FB.logout(function(response) {
                            response.authResponse.accessToken="";
                            response.authResponse.expiresIn=0;
                            response.authResponse.signedRequest="";
                            response.authResponse.userID="";
                            var FBAUTH=FB.Auth;
                        });
                      }
                      jQuery("#link-fb").show();
                      jQuery("#fbstatus").html("");
                  break;
                  case "tw":
                      if(tw_login==1)
                      {
                         
                      }
                  break;
                  case "g+":
                      if(gp_login==1)
                      {
                          var token = gapi.auth.getToken();
                          disconnectGUser(token.access_token);
                      }
                      jQuery("#link-gp").show();
                      jQuery("#gpstatus").html("");
                  break;
              }
          }
      });
      return false;
  }
    
  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    jQuery("#link-fb").hide();
    FB.api('/me', function(response) {
        
            jQuery.ajax({
              url:"<?php echo JRoute::_('/index.php?option=com_fittizen&task=update_social_account'); ?>",
              data:{type:"fb",pid:jQuery("#pid").val(), val:response.id}
            }).done(function(data){
                if(data == 1)
                {

                }
            });
            fb_login=1;
        
      console.log('Successful login for: ' + response.name);
        var html=response.name + ' <a href="#" onclick="return unlink_social_account(\'fb\', <?php echo json_encode((int)$fitinfo->id);?>)">[x]<?php echo JText::_('COM_FITTIZEN_REMOVE'); ?></a><img id="fbimage" src="" />';
        document.getElementById('fbstatus').innerHTML = html;
        FB.api('/me/picture', function(response){
            jQuery("#fbimage").attr("src",response.data.url);
        });
    });
    
  }
  
  
  //
  // Google plus zone
  //
  function disconnectGUser(access_token) 
  {
      var revokeUrl = 'https://accounts.google.com/o/oauth2/revoke?token=' +
      access_token;
        // Realiza una solicitud GET asíncrona.
        jQuery.ajax({
          type: 'GET',
          url: revokeUrl,
          async: false,
          contentType: "application/json",
          dataType: 'jsonp',
          success: function(nullResponse) {
            // Lleva a cabo una acción ahora que el usuario está desconectado
            // La respuesta siempre está indefinida.
            
             jQuery.ajax({
              url:"<?php echo JRoute::_('/index.php?option=com_fittizen&task=update_social_account'); ?>",
                data:{type:"g+",pid:jQuery("#pid").val()}
              }).done(function(data){
                  if(data == 1)
                  {

                  }
              });
          },
          error: function(e) {
            // Gestiona el error
              console.log(e);
            // Puedes indicar a los usuarios que se desconecten de forma manual si se produce un error
            // https://plus.google.com/apps
          }
          });
   }
  /*
   * Activado cuando el usuario acepta el inicio de sesión, cancela o cierra el
   * cuadro de diálogo de autorización.
   */
  function loginFinishedCallback(authResult) {
    if (authResult) {
      if (authResult['error'] == undefined){
          
        // Autorizado correctamente
        
//        if(gexec <= 0)
//        {
//            disconnectGUser(authResult['access_token']);
//            gexec=1;
//            return;
//        }
        gapi.auth.setToken(authResult); // Almacena el token recuperado.      
        getProfile();                     // Activa la solicitud para obtener la dirección de correo electrónico.
        
      } 
      else {
        console.log('An error occurred');
      }
    } else {
      console.log('Empty authResult');  // Se ha producido algún error
    }
  }

  /*
   * Inicia la solicitud del punto final userinfo para obtener la dirección de correo electrónico del
   * usuario. Esta función se basa en gapi.auth.setToken que contiene un token
   * de acceso de OAuth válido.
   *
   * Cuando se completa la solicitud, se activa getEmailCallback y recibe
   * el resultado de la solicitud.
   */
  function getProfile(){
    // Carga las bibliotecas oauth2 para habilitar los métodos userinfo.
    gapi.client.load('oauth2', 'v2', function() {
          var request = gapi.client.oauth2.userinfo.get();
          request.execute(getProfileCallback);
        });
  }

  function getProfileCallback(obj){
    // Activa la solicitud para obtener la dirección de correo electrónico.
    jQuery("#link-gp").hide();
    gp_login=1;
    var html=obj.name + ' <a href="#" onclick="return unlink_social_account(\'g+\', <?php echo json_encode((int)$fitinfo->id);?>)">[x]<?php echo JText::_('COM_FITTIZEN_REMOVE'); ?></a><img id="gpimage" src="'+obj.picture+'?sz=50" />';
    jQuery("#gpstatus").html(html);
    jQuery.ajax({
      url:"<?php echo JRoute::_('/index.php?option=com_fittizen&task=update_social_account'); ?>",
      data:{type:"g+",pid:jQuery("#pid").val(), val:obj.id}
    }).done(function(data){
        if(data == 1)
        {

        }
    });
  }
  
  // end of google plus zone
  
</script>
<div class="span12">
    <form id="edit-form" method="POST" action="/index.php?option=com_fittizen&task=edit_account">
            <div class="span6">
                <label><?php echo JText::_('COM_FITTIZEN_NAME') ?></label>
                <input type="text" name="name" value="<?php echo $fitinfo->name; ?>" />
                <label><?php echo JText::_('COM_FITTIZEN_LAST_NAME') ?></label>
                <input type="text" name="last_name" value="<?php echo $fitinfo->last_name; ?>" />
                <label><?php echo JText::_('COM_FITTIZEN_EMAIL') ?></label>
                <input type="text" name="email" value="<?php echo $user->email; ?>" disabled="disabled" />
                <label><?php echo JText::_('COM_FITTIZEN_PROFILE_CODE') ?></label>
                <input type="text" id="profile_code" name="profile_code" value="<?php echo $fitinfo->profile_code; ?>" />
                <div id="profile_code_state" class="field-message"></div>
                <label><?php echo JText::_('COM_FITTIZEN_NEW_PASSWORD') ?></label>
                <input type="password" name="pass" value="" />
                <label><?php echo JText::_('COM_FITTIZEN_REPEAT_PASSWORD') ?></label>
                <input type="password" name="pass2" value="" />
                <label><?php echo JText::_('COM_FITTIZEN_BIRTH_DATE') ?></label>
                <input type="text" id="bday" name="birth_date" value="<?php echo $fitinfo->birth_date; ?>" />
                
                <input id="pid" type="hidden" name="id" value="<?php echo $fitinfo->id; ?>" />
                <br/>
                <button type="submit"><?php echo JText::_('COM_FITTIZEN_SAVE') ?></button>
            </div>
            <div class="span6">
                <label><?php echo JText::_('COM_FITTIZEN_DEFAULT_LANGUAGE') ?></label>
                <?php 
                    $form->SelectBox('lid', $languages, "langswitch");
                    echo $form->renderFields();
                ?>
                <label><?php echo JText::_('COM_FITTIZEN_PROFILE_PRIVACY') ?></label>
                <?php 
                    
                    $form->SelectBox('public', $profile_privacy_arr);
                    echo $form->renderFields();
                ?>
                <div id="link-fb">
                    <button type="button" id="sync-fb">
                    <?php 
                    echo JText::_('COM_FITTIZEN_SYNC_FB');
                    ?>
                    </button>    
                </div>
                <div id="fbstatus">
                </div>
                <div id="link-gp">
                    <button type="button" id="sync-gp">
                    <?php 
                    echo JText::_('COM_FITTIZEN_SYNC_GPLUS');
                    ?>
                    </button>
                </div>
                <div id="gpstatus">
                </div>
                <div id="link-tw">
                    <button type="button" id="sync-tw">
                    <?php 
                    echo JText::_('COM_FITTIZEN_SYNC_TW');
                    ?>
                    </button>
                </div>
                <div id="twstatus">
                </div>
                <label><?php echo JText::_('COM_FITTIZEN_PHONE') ?></label>
                <input type="text" id="phone" name="phone" value="<?php echo $fitinfo->phone; ?>" />
                <label><?php echo JText::_('COM_FITTIZEN_LOCATION') ?></label>
                <input type="text" id="location" name="location" value="<?php echo $location->address; ?>" />
                <input type="hidden" id="location_id" name="location_id" value="<?php echo $fitinfo->location_id; ?>" />
                <label><?php echo JText::_('COM_FITTIZEN_GENDER') ?></label>
                <?php 
                    $form->SelectBox('gender_id', $genders_arr, "");
                    echo $form->renderFields();
                ?>
                <div style="display:none;">
                    <fb:login-button id="fb-button-login" scope="public_profile,email" onlogin="checkLoginState();">
                    </fb:login-button>
                     <div class="g-signin"
                        data-callback="loginFinishedCallback"
                        data-height="short"
                        data-clientid="<?php echo GPLUS_CLIENT_ID; ?>"
                        data-cookiepolicy="<?php echo GPLUS_COOKIE_POLICY; ?>"
                        data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email">
                     </div>
                </div>
            </div>
    </form>
</div>