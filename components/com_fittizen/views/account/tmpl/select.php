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
$email=$name=$gname=$lastname=$middlename="";
if(isset($objs['email']))
{
    $email = $objs['email'];
}
if(isset($objs['first_name']))
{
    $name = $objs['first_name'];
}
if(isset($objs['last_name']))
{
    $lastname = $objs['last_name'];
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
?>

<form action="./index.php?option=com_fittizen&task=create_account" id="account_sel" method="POST">        
    <div id="tabs" class="span12">
      <ul>
        <li><a href="#tabs-1"><?php echo JText::_('COM_FITTIZEN_QUICK_REGISTER');  ?></a></li>
        <li><a href="#tabs-2"><?php echo JText::_('COM_FITTIZEN_ACCOUNT_TYPE');  ?></a></li>
        <li><a href="#tabs-3"><?php echo JText::_('COM_FITTIZEN_CONFIRM_COMPLETE_DATA');  ?></a></li>
        <li><a href="#tabs-4"><?php echo JText::_('COM_FITTIZEN_INVITE_SHARE');  ?></a></li>
      </ul>
      <div id="tabs-1">
        
      </div>
      <div id="tabs-2">
        <h2><?php echo JText::_('COM_FITTIZEN_SELECT_ACCOUNT_TYPE') ?></h2>
        <input type="hidden" id="account_type" name="account_type" value=""/>
            <input type="hidden" id="params" name="params" value="<?php echo $params; ?>" />
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
        
      
      </div>
      <div id="tabs-4">
        <p><strong>Click this tab again to close the content pane.</strong></p>
        <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
      </div>
    </div>
</form>

<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery( '#tabs' ).tabs({
      collapsible: true,
      active: 1
    });
    //jQuery('#tabs').disable(0);
});
function submitaccount(type)
{
    jQuery("#account_type").val(type);
    jQuery( "#tabs" ).tabs({active:jQuery( "#tabs" ).tabs( "option", "active" )+1});
}
</script>
    
