<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');

require_once JPATH_ROOT.'/libs/defines.php';
require_once BASE_DIR.LIBS.INCLUDES;

oDirectory::loadClassesFromDirectory(JPATH_COMPONENT_ADMINISTRATOR.DS.MODELS.DS.DATA);
oDirectory::loadClassesFromDirectory(JPATH_COMPONENT_ADMINISTRATOR.DS.MODELS.DS.LOGIC);

$lang = JFactory::getLanguage();
$extension = 'com_fittizen';
$language_tag = AuxTools::GetCurrentLanguageJoomla();
$reload = true;
$lang->load($extension, JPATH_COMPONENT_ADMINISTRATOR, $language_tag, $reload);

$user = JFactory::getUser();

$fitinfo = bll_fitinfos::getProfileByUserId($user->id);
$image=$fitinfo->get_main_image();
$img_path= "/no-image.jpg";
if($image != false)
{
    $img_path=$image->url_thumb;
}
?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-vertical">
        <a href="<?php echo JRoute::_('index.php?option=com_users&view=profile', true, $params->get('usesecure')); ?>">
            <img class="profile-thumb-img" src="<?php echo $img_path; ?>" />
        </a>
        <?php if ($params->get('greeting')) : ?>
	<div class="login-greeting">
            <a href="<?php echo JRoute::_('index.php?option=com_users&view=profile', true, $params->get('usesecure')); ?>">
                    <?php if ($params->get('name') == 0) : {
                            echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name')));
                    } else : {
                            echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username')));
                    } endif; ?>
            </a>
	</div>
        
        <?php endif; ?>
	<div class="edit-profile-button">
            <a href="<?php echo JRoute::_('./index.php?option=com_fittizen&view=account&layout=edit', true, $params->get('usesecure')); ?>">
            <?php echo JText::_('COM_FITTIZEN_EDIT_PROFILE'); ?>
            </a>
	</div>
	<div class="logout-button">
		<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
