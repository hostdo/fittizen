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
</script>
   
<form action="./index.php?option=com_fittizen&task=create_account" id="account_sel" method="POST">        
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
          <button type="button" onclick="return confirm_info();">
            <?php echo JText::_('COM_FITTIZEN_CONTINUE') ?>
          </button>
      </div>
      <div id="tabs-4">
          <button class="no-style-button" type="submit">
              <?php echo JText::_('COM_FITTIZEN_SKIP_THIS_STEP'); ?>
          </button>
      </div>
    </div>
</form>

     

      