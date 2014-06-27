<?php 
$lang = JFactory::getLanguage();
$extension = 'com_fittizen';
$language_tag = AuxTools::GetCurrentLanguageJoomla();
$reload = true;
$lang->load($extension, JPATH_COMPONENT_ADMINISTRATOR, $language_tag, $reload);
$jspath = AuxTools::getJSPathFromPHPDir(BASE_DIR); ?>
<script type="text/javascript" src="../../../<?php echo $jspath . LIBS . JS . JQUERY; ?>"></script>
<script type="text/javascript" src="../../../<?php echo $jspath . LIBS . JS . JQUERY_UI . JQUERY_UI_CORE; ?>"></script>
<link rel="stylesheet" href="../../../<?php echo $jspath . LIBS . JS . JQUERY_UI . JQUERY_CSS . JQUERY_UI_CSS; ?>" />

<h3>
<?php
echo JText::_('COM_FITTIZEN_COM_FITTIZEN_TITLE');
?>
</h3>
<p>
<?php
echo JText::_('COM_FITTIZEN_COM_FITTIZEN_DESC');
?>
</p>