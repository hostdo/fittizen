<?php 
$lang_id = AuxTools::GetCurrentLanguageIDJoomla();
$language = new languages($lang_id);
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
$obj = new bll_events($id);
$location = new bll_locations($obj->location_id);
$jspath = AuxTools::getJSPathFromPHPDir(BASE_DIR);
$uri="./index.php?option=com_fittizen&view=events";
?>
<script type="text/javascript" src="../<?php echo LIBS . JS . JQUERY; ?>"></script>
<script type="text/javascript" src="../<?php echo LIBS . JS . DATE_TIME_JS; ?>"></script>
<script type="text/javascript" src="../<?php echo LIBS . JS . JQUERY_UI . JQUERY_UI_CORE; ?>"></script>
<link rel="stylesheet" href="../<?php echo  LIBS . JS . JQUERY_UI . JQUERY_CSS . JQUERY_UI_CSS; ?>" />
<link rel="stylesheet" href="../<?php echo  LIBS . JS . DATE_TIME_CSS; ?>" />
<div  class="span9">
    <h3 class="header-title">
    <?php
    echo JText::_('COM_FITTIZEN_EVENT');
    ?>
    </h3>
</div>
<script type="text/javascript">
$(function(){
    $( "#location" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          url:"../index.php?option=com_fittizen&task=find_locations&format=json", 
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
        $form->Label(JText::_('COM_FITTIZEN_NAME'), 'name');
        $form->Text('name', $obj->name, '', '', true);
        $form->Label(JText::_('COM_FITTIZEN_DESCRIPTION'), 'description');
        $form->JEditor('description', $obj->description, 350, 200, 50, 25);
        $form->Label(JText::_('COM_FITTIZEN_LOCATION'), 'location');
        $form->Text('location', $location->address, 'location', '', false);    
        $form->Label(JText::_('COM_FITTIZEN_START_DATE'), 'init_date');
        $form->DateTime('init_date', $obj->init_date, 'init_date', '', true, 'Y-m-d', $language->sef);
        $form->Label(JText::_('COM_FITTIZEN_END_DATE'), 'end_date');
        $form->DateTime('end_date', $obj->end_date, 'end_date', '', true, 'Y-m-d H:i', $language->sef);
        $form->Hidden('id', $obj->id);
        $form->Hidden('mode', 'save');
        $form->Hidden('location_id', $location->id, 'location_id');
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