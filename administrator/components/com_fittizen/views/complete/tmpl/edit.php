<?php 
$lang_id = AuxTools::GetCurrentLanguageIDJoomla();
$limitstart =0;
$id=0;
$langobj="";
if(filter_has_var(INPUT_POST, 'langobj'))
{
    $langobj = filter_input(INPUT_POST, 'langobj');
}
if(filter_has_var(INPUT_POST, 'limitstart'))
{
    $limitstart = filter_input(INPUT_POST, 'limitstart');
}
if(filter_has_var(INPUT_POST, 'id'))
{
    $id = filter_input(INPUT_POST, 'id');
}
$tmp = new $this->objname(-1);
$total=count($tmp->checkIncomplete(true,
$tmp->getPrimaryKeyField()));
if($total <= 0)
{
    $jsbase_path_route = AuxTools::getJSPathFromPHPDir(BASE_DIR);
    $uri=$jsbase_path_route.DS."administrator".
            DS.JRoute::_('/index.php?option=com_fittizen');
                
    JFactory::getApplication()->enqueueMessage(
        'COM_FITTIZEN_NO_CONTENT_COMPLETE', 'error');
    JFactory::getApplication()->redirect($uri);
}
$obj = new $langobj($id);
$jspath = AuxTools::getJSPathFromPHPDir(BASE_DIR);
$uri="./index.php?option=com_fittizen&view=complete&obj=".$this->objname;
?>
<script type="text/javascript" src="<?php echo DS.$jspath . LIBS . JS . JQUERY; ?>"></script>
<script type="text/javascript" src="<?php echo DS.$jspath . LIBS . JS . JQUERY_UI . JQUERY_UI_CORE; ?>"></script>
<link rel="stylesheet" href="<?php echo DS.$jspath . LIBS . JS . JQUERY_UI . JQUERY_CSS . JQUERY_UI_CSS; ?>" />
<div class="span9">
<h3 class="header-title">
<?php
echo JText::_('COM_FITTIZEN_DIETS');
?>
</h3>
</div>
<div id="form-container" class="span9">
    <?php 
        $form = Form::getInstance();
        $form->setLayout(FormLayouts::FORMS_UL_LAYOUT);
        $language = new languages($obj->lang_id);
        $lval = $obj;
        $lang_label = "($language->title_native)";
        $form->Label(JText::_('COM_FITTIZEN_NAME').$lang_label, 'name');
        $form->Text('name', $lval->name, '', '', true);
        $form->Label(JText::_('COM_FITTIZEN_DESCRIPTION').$lang_label, 'description');
        $form->JEditor('description',
                $lval->description, 350, 200, 60, 25);
        $form->Label(JText::_('COM_FITTIZEN_URL').$lang_label, 'url');
        $form->Text('url', $lval->url);
        $form->Label(JText::_('COM_FITTIZEN_IMAGE').$lang_label, 'image');
        $form->JMediaField('image', $lval->image);
        $form->LinkButton(JText::_('COM_FITTIZEN_CANCEL'),
                $uri,'cancel',
                'cancel_btn', 'cancel_btn', 
         '<span class="icon-cancel"></span>'
         );
        $form->Button(
         '<span class="icon-save"></span>'.
         JText::_('COM_FITTIZEN_SAVE'), 'save','',
                'btn btn-small', $uri, 'submit');
        
        $form->Hidden('id', $obj->id);
        $form->Hidden('mode', 'save');
        $form->Hidden('limitstart', $limitstart);
        $form->Hidden('objname', $this->objname);
        $form->Hidden('langobj', $langobj);
        echo $form->Render($uri, null, 'submit');
    ?>
</div>