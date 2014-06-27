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
if(filter_has_var(INPUT_POST, 'date'))
{
    $date = filter_input(INPUT_POST, 'date');
}
$averages = bll_fitinfos::get_average_visits('2014-01-01', 'now');
?>
<script type="text/javascript" src="<?php echo $jspath . LIBS . JS . JQUERY; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . LIBS . JS . DATE_TIME_JS; ?>"></script>
<script type="text/javascript" src="<?php echo $jspath . LIBS . JS . JQUERY_UI . JQUERY_UI_CORE; ?>"></script>
<link rel="stylesheet" href="<?php echo $jspath . LIBS . JS . JQUERY_UI . JQUERY_CSS . JQUERY_UI_CSS; ?>" />
<link rel="stylesheet" href="<?php echo $jspath . LIBS . JS . DATE_TIME_CSS; ?>" />
<script>  
  $(function() {
    $( "#tabs" ).tabs();
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
            <p><?php echo JText::_('COM_FITTIZEN_ADS_CLICKS');?>: <?php echo count($trainer->findAll()); ?> </p>
            <p><?php echo JText::_('COM_FITTIZEN_ADS_IMPRESSIONS');?>: <?php echo count($fittizen->findAll()); ?> </p>
            
        </div>
  </div>
  <div id="tabs-3">
  
  </div>
</div>