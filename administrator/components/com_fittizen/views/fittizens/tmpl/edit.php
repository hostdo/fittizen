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
$obj = new bll_fittizens($id);
$pro = new bll_fitinfos($obj->fitinfo_id);
$gender = new fittizen_gender_lang(-1);
$genders=$gender->setSelectValues('name', 'id', $pro->gender_id,
        $gender->findAll('lang_id', $lang_id));
        
$yes = JText::_('COM_FITTIZEN_YES');
$no = JText::_('COM_FITTIZEN_NO');
$array=array(array('id'=>"1",'value'=>$yes), array('id'=>"0",'value'=>$no));
$block_arr = $gender->setSelectValues('value', 'id', $pro->block, 
        dbobject::convertHashToObjArray($array, 'id', 'value'));

$location = new bll_locations($pro->location_id);
$jspath = AuxTools::getJSPathFromPHPDir(BASE_DIR);
$uri="./index.php?option=com_fittizen&view=fittizens";
?>
<script type="text/javascript" src="../<?php echo  LIBS . JS . JQUERY; ?>"></script>
<script type="text/javascript" src="../<?php echo  LIBS . JS . DATE_TIME_JS; ?>"></script>
<script type="text/javascript" src="../<?php echo  LIBS . JS . JQUERY_UI . JQUERY_UI_CORE; ?>"></script>
<link rel="stylesheet" href="../<?php echo  LIBS . JS . JQUERY_UI . JQUERY_CSS . JQUERY_UI_CSS; ?>" />
<link rel="stylesheet" href="../<?php echo LIBS . JS . DATE_TIME_CSS; ?>" />
<div  class="span9">
    <h3 class="header-title">
    <?php
    echo JText::_('COM_FITTIZEN_FITTIZEN');
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
    $("#profile_code").change(function(){
        $.ajax({
          url:"../index.php?option=com_fittizen&task=validate_profile_code&format=json", 
          data:{code:$("#profile_code").val()},
          dataType:"json",
              success: function( data ) {
                  var html="";
                  if($("#profile_code").val() === "<?php echo $pro->profile_code ?>")
                  {
                      html="";
                  }
                  else if(data !== true)
                  {
                      html="<?php echo JText::_('COM_FITTIZEN_PROFILE_CODE_UNAVAILABLE'); ?>";
                  }
                  else
                  {
                      html="<?php echo JText::_('COM_FITTIZEN_PROFILE_CODE_AVAILABLE'); ?>";
                  }
                  $('#profile_code_state').html(html);
              }
         });
    });
});

</script>
<div id="form-container" class="span9">
    <?php
        $form = Form::getInstance();
        $form->setLayout(FormLayouts::FORMS_UL_LAYOUT);
        //fitinfo profile
        $form->Label(JText::_('COM_FITTIZEN_BLOCK'), 'block');
        $form->SelectBox('block', $block_arr);
        $form->Label(JText::_('COM_FITTIZEN_NAME'), 'name');
        $form->Text('name', $pro->name, '', '', false);
        $form->Label(JText::_('COM_FITTIZEN_LAST_NAME'), 'last_name');
        $form->Text('last_name', $pro->last_name, '', '', false);
        $form->Label(JText::_('COM_FITTIZEN_GENDER'), 'gender_id');
        $form->SelectBox('gender_id', $genders);
        $form->Label(JText::_('COM_FITTIZEN_PROFILE_CODE'), 'profile_code');
        $form->Text('profile_code', $pro->profile_code, 'profile_code', '', false);
        $form->HTML("<div id=\"profile_code_state\" class=\"field-message\"></div>");
        $form->Label(JText::_('COM_FITTIZEN_LOCATION'), 'location');
        $form->Text('location', $location->address, 'location', '', false);
        $form->Label(JText::_('COM_FITTIZEN_HEIGHT').'('.bll_measures::$m.')', 'height');
        $form->Text('height', $pro->height, '', '', false);  
        $form->Label(JText::_('COM_FITTIZEN_WEIGHT').'('.bll_measures::$kg.')', 'weight');
        $form->Text('weight', $pro->weight, '', '', false);      
        $form->Label(JText::_('COM_FITTIZEN_HIP').'('.bll_measures::$cm.')', 'hip');
        $form->Text('hip', $pro->hip, '', '', false);    
        $form->Label(JText::_('COM_FITTIZEN_NECK').'('.bll_measures::$cm.')', 'neck');
        $form->Text('neck', $pro->neck, '', '', false);    
        $form->Label(JText::_('COM_FITTIZEN_CHEST').'('.bll_measures::$cm.')', 'chest');
        $form->Text('chest', $pro->chest, '', '', false);    
        $form->Label(JText::_('COM_FITTIZEN_THIGH').'('.bll_measures::$cm.')', 'thigh');
        $form->Text('thigh', $pro->thigh, '', '', false);    
        $form->Label(JText::_('COM_FITTIZEN_UPPER_ARM').'('.bll_measures::$cm.')', 'upper_arm');
        $form->Text('upper_arm', $pro->upper_arm, '', '', false);    
        $form->Label(JText::_('COM_FITTIZEN_WAIST').'('.bll_measures::$cm.')', 'waist');
        $form->Text('waist', $pro->waist, '', '', false);    
        
        $form->Label(JText::_('COM_FITTIZEN_BIRTH_DATE'), 'birth_date');
        $form->Date('birth_date', $pro->birth_date, 'birth_date', '', false, 'Y-m-d', $language->sef);
        
        $form->Hidden('id', $pro->id);
        $form->Hidden('location_id', $location->id, 'location_id');
        
        //end fitinfo profile
        $form->Hidden('mode', 'save');
        $form->Hidden('limitstart', $limitstart);
        
        $html="";
        
        $html.="<div class=\"profile-info\">"
                .JText::_('COM_FITTIZEN_FRIENDS').': '.count(bll_fitinfos::get_active_friends($pro->id))
                . "</div>";
        $html.="<div class=\"profile-info\">"
                .JText::_('COM_FITTIZEN_BLOCKED_FRIENDS').': '.count(bll_fitinfos::get_block_friends($pro->id))
                . "</div>";
        $html.="<div class=\"profile-info\">"
                .JText::_('COM_FITTIZEN_TRAINERS').': '.count($obj->get_trainers_id())
                . "</div>";
        $html.="<div class=\"profile-info\">"
                .JText::_('COM_FITTIZEN_CREATED_DATE').': '.$pro->created_date
                . "</div>";
        $html.="<div class=\"profile-info\">"
                .JText::_('COM_FITTIZEN_LAST_NOTIFICATION_CHECK').': '.$pro->last_notification_check
                . "</div>";
        $html.="<div class=\"profile-info\">"
                .JText::_('COM_FITTIZEN_LAST_VISIT_DATE').': '.$pro->last_visit_date
                . "</div>";
        if($pro->fb_id > 0)
        {
            $val = $yes;
        }
        else
        {
            $val = $no;
        }
        $html.="<div class=\"profile-info\">"
                .JText::_('COM_FITTIZEN_FACEBOOK').': '.$val
                . "</div>";
        if($pro->gplus_id > 0)
        {
            $val = $yes;
        }
        else
        {
            $val = $no;
        }
        $html.="<div class=\"profile-info\">"
                .JText::_('COM_FITTIZEN_GOOGLE').': '.$val
                . "</div>";
        if($pro->twitter_id > 0)
        {
            $val = $yes;
        }
        else
        {
            $val = $no;
        }
        $html.="<div class=\"profile-info\">"
                .JText::_('COM_FITTIZEN_TWITTER').': '.$val
                . "</div>";
        $html.="<br/>";
        
        $form->HTML($html);
        
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