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
    $uri=JRoute::_('/index.php?option=com_fittizen',false);
                
    JFactory::getApplication()->enqueueMessage(
        'COM_FITTIZEN_NO_CONTENT_COMPLETE', 'warning');
    
    $con = new FittizenController();
    $con->setRedirect($uri);
    $con->redirect();
}
$obj = new $this->objname($id);
$jspath = AuxTools::getJSPathFromPHPDir(BASE_DIR);
$uri="./index.php?option=com_fittizen&view=complete&obj=".$this->objname;
?>
<script type="text/javascript" src="../<?php echo LIBS . JS . JQUERY; ?>"></script>
<script type="text/javascript" src="../<?php echo LIBS . JS . JQUERY_UI . JQUERY_UI_CORE; ?>"></script>
<link rel="stylesheet" href="../<?php echo LIBS . JS . JQUERY_UI . JQUERY_CSS . JQUERY_UI_CSS; ?>" />
<div class="span9">
<h3 class="header-title">
<?php
echo JText::_('COM_FITTIZEN_CONTENT');
?>
</h3>
</div>
<div id="form-container" class="span9">
    <?php 
        $form = Form::getInstance();
        $form->setLayout(FormLayouts::FORMS_UL_LAYOUT);
        foreach(languages::GetLanguages() as $language)
        {
            $lval = $obj->getLanguageValue($language->lang_id);
            $lang_suffix = "_$language->lang_id";
            $lang_label = "($language->title_native)";
            $form->Label(JText::_('COM_FITTIZEN_NAME').$lang_label, 'name'.$lang_suffix);
            $form->Text('name'.$lang_suffix, $lval->name, '', '', true);
            $form->Label(JText::_('COM_FITTIZEN_DESCRIPTION').$lang_label, 'description'.$lang_suffix);
            $form->JEditor('description'.$lang_suffix,
                    $lval->description, 350, 200, 60, 25);
            $form->Label(JText::_('COM_FITTIZEN_URL').$lang_label, 'url'.$lang_suffix);
            $form->Text('url'.$lang_suffix, $lval->url);
            $form->Label(JText::_('COM_FITTIZEN_IMAGE').$lang_label, 'image'.$lang_suffix);
            $form->JMediaField('image'.$lang_suffix, $lval->image);
        }
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