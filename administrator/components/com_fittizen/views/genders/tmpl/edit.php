<?php 
$lang_id = AuxTools::GetCurrentLanguageIDJoomla();
$limitstart =0;
$id=0;
if(filter_has_var(INPUT_POST, 'limitstart'))
{
    $limitstart = filter_input(INPUT_POST, 'limitstart');
}
if(filter_has_var(INPUT_POST, 'id'))
{
    $id = filter_input(INPUT_POST, 'id');
}
$obj = new bll_gender($id);
$jspath = AuxTools::getJSPathFromPHPDir(BASE_DIR);
$uri="./index.php?option=com_fittizen&view=genders";
?>
<script type="text/javascript" src="<?php echo $jspath . LIBS . JS . JQUERY; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . LIBS . JS . JQUERY_UI . JQUERY_UI_CORE; ?>"></script>
<link rel="stylesheet" href="<?php echo $jspath . LIBS . JS . JQUERY_UI . JQUERY_CSS . JQUERY_UI_CSS; ?>" />
<div  class="span9">
    <h3 class="header-title">
    <?php
    echo JText::_('COM_FITTIZEN_GENDER');
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
        }
        $form->Hidden('id', $obj->id);
        $form->Hidden('mode', 'save');
        $form->Hidden('limitstart', $limitstart);
        $form->LinkButton(JText::_('COM_FITTIZEN_CANCEL'),
                $uri,'cancel',
                'cancel_btn', 'cancel_btn', 
         '<span class="icon-cancel"></span>'
         );
        $form->Button(
         '<span class="icon-save"></span>'.
         JText::_('COM_FITTIZEN_SAVE'), 'save','',
                'btn btn-small', $uri, 'submit');
        echo $form->Render($uri, null, 'submit');
    ?>
</div>