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

  var exec=0;
  

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
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->

<fb:login-button scope="public_profile,email,user_likes,user_likes" onlogin="checkLoginState();">
</fb:login-button>
<span id="signinButton">
  <span
    class="g-signin"
    data-callback="signinCallback"
    data-clientid="<?php echo GPLUS_CLIENT_ID; ?>"
    data-cookiepolicy="<?php echo GPLUS_COOKIE_POLICY; ?>"
    data-requestvisibleactions="http://schemas.google.com/AddActivity"
    data-scope="https://www.googleapis.com/auth/plus.login">
  </span>
</span>
<div id="status">
</div>

<form id="social-login" method="GET">
    
</form>


<div id="block">
</div>

<!-- Coloca este JavaScript asíncrono justo delante de la etiqueta </body> -->
<script type="text/javascript">
    function signinCallback(authResult) {
    if (authResult['access_token']) {
      // Autorizado correctamente
      // Oculta el botón de inicio de sesión ahora que el usuario está autorizado, por ejemplo:
      document.getElementById('signinButton').setAttribute('style', 'display: none');
      var form_html = '<input type="hidden" name="option" value="com_fittizen" /><input type="hidden" name="task" value="facebook_login" />';
      form_html+= '<input type="hidden" name="params" value="'+jQuery.param(authResult)+'" />'; 
      jQuery("#social-login").html(form_html);
      jQuery("#social-login").submit();
    } 
    else if (authResult['error']) {
      // Se ha producido un error.
      // Posibles códigos de error:
      //   "access_denied": el usuario ha denegado el acceso a la aplicación.
      //   "immediate_failed": no se ha podido dar acceso al usuario de forma automática.
        console.log('There was an error: ' + authResult['error']);
    }
  }  
    
  (function() {
   var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
   po.src = 'https://apis.google.com/js/client:plusone.js';
   var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
 })();
</script>
    
