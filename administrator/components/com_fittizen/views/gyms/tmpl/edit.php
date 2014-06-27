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
$obj = new bll_gyms($id);
$location = new fittizen_locations($obj->location_id);
$jspath = AuxTools::getJSPathFromPHPDir(BASE_DIR);
$uri="./index.php?option=com_fittizen&view=gyms";
?>
<script type="text/javascript" src="<?php echo $jspath . LIBS . JS . JQUERY; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . LIBS . JS . JQUERY_UI . JQUERY_UI_CORE; ?>"></script>
<link rel="stylesheet" href="<?php echo $jspath . LIBS . JS . JQUERY_UI . JQUERY_CSS . JQUERY_UI_CSS; ?>" />
<div class="span9">
<h3 class="header-title">
<?php
echo JText::_('COM_FITTIZEN_GYM');
?>
</h3>
</div>
<script type="text/javascript">
$(function(){
    $( "#location" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          url:"<?php echo $jspath.DS ?>index.php?option=com_fittizen&task=find_locations&format=json", 
          data:{address:$("#location").val()},
          dataType:"json",
          success: function( data ) {
            response( $.map( data, function( item ) {
              return {
                label: item.address,
                value: item.id
              }
            }));
          }
        });
      },
      minLength: 2,
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      },
      select: function( event, ui ) {
        $("#location").val(ui.item.label);
        $("#location_id").val(ui.item.value);
        event.stopPropagation();
        return false;
      }
    });
});

</script>
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
        $form->Label(JText::_('COM_FITTIZEN_LOCATION'), 'location');
        $form->Text('location', $location->address, 'location', '', false);    
        $form->Hidden('id', $obj->id);
        $form->Hidden('location_id', $location->id, 'location_id');
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