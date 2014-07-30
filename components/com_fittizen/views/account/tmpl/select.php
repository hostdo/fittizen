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
$user = JFactory::getUser();
$jinput = JFactory::getApplication()->input;
$params= base64_decode($jinput->get('params',""));
$objs=array();
parse_str($params,$objs);
$birth_date=$email=$name=$gname=$lastname=$middlename="";
if(isset($objs['email']))
{
    $email = $objs['email'];
}
if(isset($objs['first_name']))
{
    $name = $objs['first_name'];
}
if(isset($objs['given_name']))
{
    $name = $objs['given_name'];
}
if(isset($objs['last_name']))
{
    $lastname = $objs['last_name'];
}
if(isset($objs['family_name']))
{
    $lastname = $objs['family_name'];
}
if(isset($objs['middle_name']))
{
    $middlename = $objs['middle_name'];
}
if(isset($objs['gender']))
{
    $gname = $objs['gender'];
}
$gender = bll_gender::find_gender($gname);
$genders =  new fittizen_gender_lang(-1);
$genders_arr = $genders->setCheckboxValues(
        'name', 'gender_id', 
        array($gender), 'id',
        null, $genders->findAll('lang_id', $lang->lang_id));
?>
<script type="text/javascript">
    
jQuery(function(){
    jQuery("#tabs-1").hide();
    jQuery("#tabs-3").hide();
    jQuery("#tabs-4").hide();
    var elem=document.getElementById("atabs-2");
    elem.className = "selected";
});
function submitaccount(type)
{
    jQuery("#account_type").val(type);
    jQuery("#tabs-2").hide();
    jQuery("#tabs-3").show();
    var elem=document.getElementById("atabs-3");
    elem.className = "selected";
    var elem2=document.getElementById("atabs-2");
    elem2.className = "";
}
function confirm_info()
{
    jQuery("#tabs-3").hide();
    jQuery("#tabs-4").show();
    var elem=document.getElementById("atabs-4");
    elem.className = "selected";
    var elem2=document.getElementById("atabs-3");
    elem2.className = "";
}

  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallbackRet(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      return 1;
    } 
    else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      return 0;
        
    } 
    else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      return -1;
    }
  }
  
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
    statusChangeCallbackRet(response);
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
    FB.ui({
        method: 'share',
        href: 'http://fittizen.com/',
      } , function(response){

    });
  }


</script>
<?php
$params=base64_encode($params); 
?>   
<form class="row-fluid" action="/index.php?option=com_fittizen&task=create_account" id="account_sel" method="POST">        
    <div id="tabs" class="span12">
      <ol>
        <li><a id="atabs-1" class="" onclick="return false;"><?php echo JText::_('COM_FITTIZEN_QUICK_REGISTER');  ?></a></li>
        <li><a id="atabs-2" class="" onclick="return false;"><?php echo JText::_('COM_FITTIZEN_ACCOUNT_TYPE');  ?></a></li>
        <li><a id="atabs-3" class="" onclick="return false;"><?php echo JText::_('COM_FITTIZEN_CONFIRM_COMPLETE_DATA');  ?></a></li>
        <li><a id="atabs-4" class="" onclick="return false;"><?php echo JText::_('COM_FITTIZEN_INVITE_SHARE');  ?></a></li>
      </ol>
      <div id="tabs-1">
        
      </div>
      <div id="tabs-2">
        <h2><?php echo JText::_('COM_FITTIZEN_SELECT_ACCOUNT_TYPE') ?></h2>
            <input type="hidden" id="account_type" name="account_type" value=""/>
            <input type="hidden" name="email" value="<?php echo $email; ?>" />
            <input type="hidden"  id="params" name="params" value="<?php echo $params; ?>" />
            <div id="fittizen" class="span6">
                <h3><?php echo JText::_('COM_FITTIZEN_FITTIZEN') ?></h3>
                <p><?php echo JText::_('COM_FITTIZEN_FITTIZEN_DESC') ?></p>
                <button type="button" onclick="return submitaccount('fittizen');">
                    <?php echo JText::_('COM_FITTIZEN_SELECT') ?>
                </button>
            </div>
            <div id="trainer" class="span6">
                <h3><?php echo JText::_('COM_FITTIZEN_TRAINER') ?></h3>
                <p><?php echo JText::_('COM_FITTIZEN_TRAINER_DESC') ?></p>
                <button type="button" onclick="return submitaccount('trainer');">
                    <?php echo JText::_('COM_FITTIZEN_SELECT') ?>
                </button>
            </div>
      </div>
      <div id="tabs-3">
          <h2 class="span12"><?php echo JText::_('COM_FITTIZEN_CONFIRM_COMPLETE_DATA') ?></h2>
          <div class="span12">
              <div class="span6">
                    <label><?php echo JText::_('Name'); ?></label>
                    <input type="text" name="name" value="<?php echo $name ." ". $middlename; ?>" />
              </div>
              <div class="span6">
                  <label><?php echo JText::_('Last name'); ?></label>
                  <input type="text" name="lastname" value="<?php echo $lastname; ?>" />
              </div>
          </div>
          <div class="span12">
          <?php 
            $form = Form::getInstance();
            $form->HTML('<div class="span6">');
            $form->Label(JText::_('COM_FITTIZEN_BIRTH_DATE'), 'birth_date');
            $form->Date('birth_date', $birth_date, 'birth_date');
            $form->HTML('</div>');
            $form->HTML('<div class="span6">');
            $form->Label(JText::_('COM_FITTIZEN_GENDER'), 'gender');
            $form->Radiobuttons('gender', $genders_arr);
            $form->HTML('</div>');
            echo $form->renderFields();
          ?>
          </div>
          <button type="button" onclick="return confirm_info();">
            <?php echo JText::_('COM_FITTIZEN_CONTINUE') ?>
          </button>
      </div>
      <div id="tabs-4">
          
          <div id="fb-share">
            <fb:login-button scope="public_profile,email,user_likes,user_likes" onlogin="checkLoginState();">
            </fb:login-button>
          </div>
          <button class="no-style-button" type="submit">
              <?php echo JText::_('COM_FITTIZEN_SKIP_THIS_STEP'); ?>
          </button>
      </div>
    </div>
</form>

     

      