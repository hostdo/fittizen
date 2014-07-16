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
$params = $jinput->get('params', "");
if($params == "")
{
    
}
?>
<script>
function submitaccount(type)
{
    jQuery("#account_type").val(type);
    jQuery("#account_sel").submit();
}
</script>
<div id="block" class="span12">
<h2><?php echo JText::_('COM_FITTIZEN_SELECT_ACCOUNT_TYPE') ?></h2>
<form action="./index.php?option=com_fittizen&task=create_account" id="account_sel" method="POST">
    <input type="hidden" id="account_type" name="account_type" value=""/>
    <input type="hidden" id="userid" name="userid" value="<?php echo $user->id; ?>"/>
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
</form>
    
</div>
    
