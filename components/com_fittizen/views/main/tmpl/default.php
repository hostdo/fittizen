<?php

/**
 * @version		$Id: default.php 15 2009-11-02 18:37:15Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$lang = new languages(AuxTools::GetCurrentLanguageIDJoomla());

$social_logout=!(bool)JFactory::getUser()->id;
?>
<script>
  var exec=0;  
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    <?php if($social_logout == true){ ?>
        if(exec == 0)
        {
            FB.logout(function(response) {
                response.authResponse.accessToken="";
                response.authResponse.expiresIn=0;
                response.authResponse.signedRequest="";
                response.authResponse.userID="";
                // Reload the same page after logout
                var FBAUTH=FB.Auth;
            });
            exec=1;
            return;
        }
    <?php } ?>
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      loginAPI();
    } 
    else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } 
    else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
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
    appId      : '<?php echo FB_API_ID; ?>',
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
    js.src = "//connect.facebook.net/<?php echo str_replace('-','_',$lang->lang_code) ?>/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function loginAPI() {
    console.log('Welcome!  Fetching your information.... ');
    
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      var form_html = '<input type="hidden" name="option" value="com_fittizen" /><input type="hidden" name="task" value="facebook_login" />';
      form_html+= '<input type="hidden" name="user_creation_fail_redirect" value="" />';
      form_html+= '<input type="hidden" name="params" value="'+jQuery.param(response)+'" />'; 
      jQuery("#social-login").html(form_html);
      jQuery("#social-login").submit();
    });
  }
   
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
            document.getElementById('signinButton').setAttribute('style', '');
          },
          error: function(e) {
            // Gestiona el error
              console.log(e);
            // Puedes indicar a los usuarios que se desconecten de forma manual si se produce un error
            // https://plus.google.com/apps
          }
          });
   }
  
  var gexec=0; 
  /*
   * Activado cuando el usuario acepta el inicio de sesión, cancela o cierra el
   * cuadro de diálogo de autorización.
   */
  function loginFinishedCallback(authResult) {
    if (authResult) {
      if (authResult['error'] == undefined){
          
        // Autorizado correctamente
        <?php if($social_logout == true){ ?>
        if(gexec <= 0)
        {
            disconnectGUser(authResult['access_token']);
            gexec=1;
            return;
        }
        <?php } ?>  
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
    var form_html = '<input type="hidden" name="option" value="com_fittizen" /><input type="hidden" name="task" value="googleplus_login" />';
    form_html+= '<input type="hidden" name="user_creation_fail_redirect" value="" />';
    form_html+= '<input type="hidden" name="params" value="'+jQuery.param(obj)+'" />'; 
    jQuery("#social-login").html(form_html);
    jQuery("#social-login").submit();
    document.getElementById('signinButton').setAttribute('style', 'display: none');
  }

  function toggleElement(id) {
    var el = document.getElementById(id);
    if (el.getAttribute('class') == 'hide') {
      el.setAttribute('class', 'show');
    } else {
      el.setAttribute('class', 'hide');
    }
  }
  
  (function() {
       var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
       po.src = 'https://apis.google.com/js/client:plusone.js';
       var s = document.getElementsByTagName('script')[0]; 
       s.parentNode.insertBefore(po, s);
  })();
  
  jQuery(function(){
     jQuery("#quick_register").click(function()
     {
        var form_html = '<input type="hidden" name="option" value="com_fittizen" /><input type="hidden" name="task" value="quick_register" />';
        form_html+= '<input type="hidden" name="user_creation_fail_redirect" value="" />';
        form_html+= '<input type="hidden" name="email" value="'+jQuery("#qemail").val()+'" />'; 
        jQuery("#social-login").html(form_html);
        jQuery("#social-login").submit(); 
     });
     jQuery("#qemail").click(function()
     {
        if(this.value === "<?php echo JText::_('COM_FITTIZEN_EMAIL') ?>")
        {
            this.value="";
        }
        return false;
     });
     jQuery("#qemail").blur(function()
     {
        if(this.value === "")
        {
            this.value="<?php echo JText::_('COM_FITTIZEN_EMAIL') ?>";
        }
        return false;
     });
  });
  
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->
<div>
    <div class="span7">
        
    </div>
    <div class="span5">
        <p class="main-header-message">
            <?php echo JText::_('COM_FITTIZEN_GET_IN_SHAPE'); ?>
        </p>
        <p class="main-header-message">
            <?php echo JText::_('COM_FITTIZEN_BECAME_A_FITTIZEN'); ?>
        </p>
        <p class="main-text-message">
            <?php echo JText::_('COM_FITTIZEN_FITTIZEN_COMUNITY_DESCRIPTION'); ?>
        </p>
        <fb:login-button scope="public_profile,email,user_likes,user_likes" onlogin="checkLoginState();">
        </fb:login-button>
        <div id="signinButton">
          <div class="g-signin"
            data-callback="loginFinishedCallback"
            data-height="short"
            data-clientid="<?php echo GPLUS_CLIENT_ID; ?>"
            data-cookiepolicy="<?php echo GPLUS_COOKIE_POLICY; ?>"
            data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email">
          </div>
        </div>
        <div id="status">
        </div>

        <form id="social-login" method="GET">
        </form>
        
        <p class="main-text-message">
            <?php echo JText::_('COM_FITTIZEN_CREATE_ACCOUNT_WITH_YOUR'); ?>
        </p>
        <div id="quick-register">
            <input type="email" id="qemail" name="email" value="<?php echo JText::_('COM_FITTIZEN_EMAIL') ?>" />
            <button id="quick_register"><?php echo JText::_('COM_FITTIZEN_REGISTER') ?></button>
        </div>
        <p class="main-tos-message">
            <?php echo JText::_('COM_FITTIZEN_REGISTRY_AGREEMENT_TOS_PP'); ?>
        </p>
        <div id="block">
        </div>  
    </div>
</div>