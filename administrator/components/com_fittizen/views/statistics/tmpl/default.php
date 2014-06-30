<?php 
$language = new languages(AuxTools::GetCurrentLanguageIDJoomla());
$lang = JFactory::getLanguage();
$extension = 'com_fittizen';
$language_tag = AuxTools::GetCurrentLanguageJoomla();
$reload = true;
$lang->load($extension, JPATH_COMPONENT_ADMINISTRATOR, $language_tag, $reload);
$jspath = AuxTools::getJSPathFromPHPDir(BASE_DIR); 
$fitinfo = new bll_fitinfos(-1);
$trainer = new bll_trainers(-1);
$fittizen=new bll_fittizens(-1);
$date = AuxTools::DateTimeCurrentString("Y-m-d");
$active_tab = 0;
$gender_id = null;
$suppl_id = null;
$nicho_id = null;
$gym_id=null;
$city="";
$birth_date="";
$rate="1 , 10";
$averages = bll_fitinfos::get_average_visits('2014-01-01', 'now');
AuxTools::printr(filter_input_array(INPUT_POST));
if(filter_has_var(INPUT_POST, 'date'))
{
    $date = filter_input(INPUT_POST, 'date');
}
if(filter_has_var(INPUT_POST, 'rate'))
{
    $rate = filter_input(INPUT_POST, 'rate');
}
if(filter_has_var(INPUT_POST, 'birth_date'))
{
    $birth_date = filter_input(INPUT_POST, 'birth_date');
}
if(filter_has_var(INPUT_POST, 'user'))
{
    $active_tab=2;
}
if(filter_has_var(INPUT_POST, 'city'))
{
    $city = filter_input(INPUT_POST, 'city');
}

if(filter_has_var(INPUT_POST, 'gender_id'))
{
    $gender_id = filter_input(INPUT_POST, 'gender_id');
}
if(filter_has_var(INPUT_POST, 'suppl_id'))
{
    $suppl_id = filter_input(INPUT_POST, 'suppl_id');
}
if(filter_has_var(INPUT_POST, 'nicho_id'))
{
    $nicho_id = filter_input(INPUT_POST, 'nicho_id');
}
if(filter_has_var(INPUT_POST, 'gym_id'))
{
    $gym_id = filter_input(INPUT_POST, 'gym_id');
}
$nicho = new fittizen_nichos_lang(-1);
$nichos = $nicho->findAll('lang_id', $language->lang_id);
$nichos = dbobject::convertListToHash($nichos,'nicho_id', 'name', $nicho_id,true);

$gym = new fittizen_gyms_lang(-1);
$gyms = $gym->findAll('lang_id', $language->lang_id);
$gyms = dbobject::convertListToHash($gyms,'gym_id', 'name', $gym_id,true);

$supl = new fittizen_supplements_lang(-1);
$supls = $supl->findAll('lang_id', $language->lang_id);
$supls = dbobject::convertListToHash($supls,'supplement_id', 'name', $suppl_id,true);

$gender = new fittizen_gender_lang(-1);
$genders = $gender->findAll('lang_id', $language->lang_id);
$genders = dbobject::convertListToHash($genders,'gender_id', 'name', $gender_id,true);

?>
<script type="text/javascript" src="<?php echo $jspath . LIBS . JS . JQUERY; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . LIBS . JS . DATE_TIME_JS; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . LIBS . JS . JQUERY_UI . JQUERY_UI_CORE; ?>"></script>
<link rel="stylesheet" href="<?php echo $jspath . LIBS . JS . JQUERY_UI . JQUERY_CSS . JQUERY_UI_CSS; ?>" />
<link rel="stylesheet" href="<?php echo $jspath . LIBS . JS . DATE_TIME_CSS; ?>" />
<script>  
  $(function() {
    $( "#tabs" ).tabs({active:<?php echo $active_tab; ?>});
    
    $( "#city" ).autocomplete({
          source: function( request, response ) {
            $.ajax({
              url:"<?php echo $jspath.DS ?>index.php?option=com_fittizen&task=find_city&format=json", 
              data:{city:$("#city").val()},
              dataType:"json",
              success: function( data ) {
                response( $.map( data, function( item ) {
                  return {
                    label: item.locality,
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
            $("#city").val(ui.item.label);
            $("#city_id").val(ui.item.value);
            event.stopPropagation();
            return false;
          }
        });
        
        $( "#country" ).autocomplete({
          source: function( request, response ) {
            $.ajax({
              url:"<?php echo $jspath.DS ?>index.php?option=com_fittizen&task=find_country&format=json", 
              data:{country:$("#country").val()},
              dataType:"json",
              success: function( data ) {
                response( $.map( data, function( item ) {
                  return {
                    label: item.country,
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
            $("#country").val(ui.item.label);
            $("#country_id").val(ui.item.value);
            event.stopPropagation();
            return false;
          }
        });
        $( "#slider-range" ).slider({
      range: true,
      min: 1,
      max: 10,
      values: [<?php echo $rate ?>],
      slide: function( event, ui ) {
        $( "#amount" ).val( "" + ui.values[ 0 ] + " , " + ui.values[ 1 ] );
      }
    });
    $( "#amount" ).val( "" + $( "#slider-range" ).slider( "values", 0 ) +
      " , " + $( "#slider-range" ).slider( "values", 1 ) );
  });
</script>
<h3>
<?php
echo JText::_('COM_FITTIZEN_STATISTICS');
?>
</h3>
<div id="tabs" class="span9 ">
  <ul>
    <li><a href="#tabs-1"><?php echo JText::_('COM_FITTIZEN_USER_REPORTS');?></a></li>
    <li><a href="#tabs-2"><?php echo JText::_('COM_FITTIZEN_ADS');?></a></li>
    <li><a href="#tabs-3"><?php echo JText::_('COM_FITTIZEN_FILTERS');?></a></li>
  </ul>
  <div id="tabs-1" class="span12">
        <div class="span6">
            <h5><b><?php echo JText::_('COM_FITTIZEN_TOTALS');?></b></h5>
            <p><?php echo JText::_('COM_FITTIZEN_TRAINERS');?>: <?php echo count($trainer->findAll()); ?> </p>
            <p><?php echo JText::_('COM_FITTIZEN_FITTIZENS');?>: <?php echo count($fittizen->findAll()); ?> </p>
            <p><?php echo JText::_('COM_FITTIZEN_TOTAL');?>: <?php echo count($fitinfo->findAll()); ?> </p>
        </div>
        <div class="span6">
            <h5><b><?php echo JText::_('COM_FITTIZEN_VISITS_BY_DAY');?></b></h5>
            <?php 
            $form1 = Form::getInstance();
            $form1->setLayout(FormLayouts::FORMS_UL_LAYOUT);
            $form1->Date('date', $date, 'datepicker', '', true, 'Y-m-d', $language->sef);
            $form1->Submit(JText::_('COM_FITTIZEN_SUBMIT'));
            echo ($form1->Render());
            ?>
            <p><?php echo JText::_('COM_FITTIZEN_TRAINERS');?>: <?php echo count(bll_fitinfos::get_trainers_visit_by_day($date)); ?> </p>
            <p><?php echo JText::_('COM_FITTIZEN_FITTIZENS');?>: <?php echo count(bll_fitinfos::get_fittizens_visit_by_day($date)); ?> </p>
            <p><?php echo JText::_('COM_FITTIZEN_TOTAL');?>: <?php echo count(bll_fitinfos::get_visit_by_day($date)); ?> </p>
        </div>
        <div class="span5">
            <h5><b><?php echo JText::_('COM_FITTIZEN_NEW_USERS_THIS_MONTH');?></b></h5>
            <p><?php echo JText::_('COM_FITTIZEN_TRAINERS');?>: <?php echo count(bll_fitinfos::get_monthly_new_trainers()); ?> </p>
            <p><?php echo JText::_('COM_FITTIZEN_FITTIZENS');?>: <?php echo count(bll_fitinfos::get_monthly_new_fittizens()); ?> </p>
            <p><?php echo JText::_('COM_FITTIZEN_TOTAL');?>: <?php echo count(bll_fitinfos::get_monthly_new_users()); ?> </p>
        </div>
        <div class="span12">
            <h5><b><?php echo JText::_('COM_FITTIZEN_DAILY_VISITS_AVERAGES');?></b></h5>
            <p><?php echo JText::_('COM_FITTIZEN_TRAINERS');?>: <?php echo $averages[1]; ?> </p>
            <p><?php echo JText::_('COM_FITTIZEN_FITTIZENS');?>: <?php echo $averages[0]; ?> </p>
            <p><?php echo JText::_('COM_FITTIZEN_TOTAL');?>: <?php echo $averages[2]; ?> </p>
        </div>
  </div>
  <div id="tabs-2">
        <div class="span6">
            <h5><b><?php echo JText::_('COM_FITTIZEN_TOTALS');?></b></h5>
            <p><?php echo JText::_('COM_FITTIZEN_ADS_CLICKS');?>: <?php echo bll_ads::get_total_clicks(); ?> </p>
            <p><?php echo JText::_('COM_FITTIZEN_ADS_IMPRESSIONS');?>: <?php echo bll_ads::get_total_impressions(); ?> </p>            
        </div>
        <div class="span6">
            <h5><b><?php echo JText::_('COM_FITTIZEN_DAILY');?></b></h5>
            <p><?php echo JText::_('COM_FITTIZEN_ADS_CLICKS');?>: <?php echo bll_ads::get_total_clicks_by_day(); ?> </p>
            <p><?php echo JText::_('COM_FITTIZEN_ADS_IMPRESSIONS');?>: <?php echo bll_ads::get_total_impressions_by_day(); ?> </p>            
        </div>
        <div class="span12">
            <h5><b><?php echo JText::_('COM_FITTIZEN_ACTIVE_ADS');?></b></h5>
            <p><?php echo JText::_('COM_FITTIZEN_TRAINERS');?>: <?php echo bll_ads::get_active_ads(); ?> </p>
        </div>
  </div>
  <div id="tabs-3">
      <h5><b><?php echo JText::_('COM_FITTIZEN_FILTER_USERS');?></b></h5>
      <?php 
      $form = Form::getInstance();
      $form->setLayout(FormLayouts::FORMS_UL_LAYOUT);
      $form->Hidden('user', 1);
      
      $form->Label(JText::_('COM_FITTIZEN_GENDER'), 'gender_id');
      $form->SelectBox('gender_id', $genders);
      
      $form->Label(JText::_('COM_FITTIZEN_NICHOS'), 'nicho_id');
      $form->SelectBox('nicho_id', $nichos);
      
      $form->Label(JText::_('COM_FITTIZEN_SUPPLEMENTS'), 'suppl_id');
      $form->SelectBox('suppl_id', $supls);
      
      $form->Label(JText::_('COM_FITTIZEN_CITY'), 'city');
      $form->Text('city', $city, 'city');
      $form->Label(JText::_('COM_FITTIZEN_COUNTRY'), 'country');
      $form->Text('country', $city, 'country');
      
      $form->Label(JText::_('COM_FITTIZEN_GYMS'), 'gym_id');
      $form->SelectBox('gym_id', $gyms);
      
      $form->Label(JText::_('COM_FITTIZEN_BIRTH_DATE'), 'birth_date');
      $form->Date('birth_date', $birth_date, 'birth_date');
      
      $form->Label(JText::_('COM_FITTIZEN_TRAINER_RATE'), 'rate');
      $rate_html="<p>
        <input name=\"rate\" type=\"text\" id=\"amount\" readonly style=\"border:0; color:#f6931f; font-weight:bold;\">
      </p>
      <div id=\"slider-range\"></div>";
      $form->HTML($rate_html);
      
      $form->Submit(JText::_('COM_FITTIZEN_FILTER'));
      echo $form->Render();
      ?>
  
  </div>
</div>