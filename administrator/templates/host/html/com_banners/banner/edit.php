<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$jspath = AuxTools::getJSPathFromPHPDir(BASE_DIR);
$fdata=$this->form->getData()->toArray();
$input = JFactory::getApplication()->input->getArray();
$min_age="";
$max_age="";
$gender_id =0;
$lang_id = AuxTools::GetCurrentLanguageIDJoomla();
if(isset($input['nicho']))
{
    $json_nicho=$input['nicho'];
}
if(isset($input['location']))
{
    $json_location=$input['location'];
}
if(isset($input['filter']))
{
    $filter= json_decode($input['filter']);
    if($filter->min_age != "")
    {
        $min_age=$filter->min_age;
    }
    if($filter->max_age != "")
    {
        $max_age=$filter->max_age;
    }
    $gender_id = $filter->gender_id;
}
$genders = array();
if(isset($input['gender']))
{
    $genders = json_decode($input['gender']);
}
$app = JFactory::getApplication();
$script = "
	jQuery(document).ready(function ($){
		$('#jform_type').change(function(){
			if($(this).val() == 1) {
				$('#image').css('display', 'none');
				$('#custom').css('display', 'block');
			} else {
				$('#image').css('display', 'block');
				$('#custom').css('display', 'none');
			}
		}).trigger('change');
	});";
// Add the script to the document head.
JFactory::getDocument()->addScriptDeclaration($script);
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'banner.cancel' || document.formvalidator.isValid(document.id('banner-form')))
		{
			Joomla.submitform(task, document.getElementById('banner-form'));
		}
	}
</script>
<script type="text/javascript" src="../<?php echo  LIBS . JS . TINY_INPUT_JS; ?>"></script>
<link rel="stylesheet" href="../<?php  echo LIBS . JS . TINY_INPUT_CSS; ?>" />
<script type="text/javascript">


function _validate_age()
{
    var val_min = document.getElementById('jform_min_age').value;
    var val_max = document.getElementById('jform_max_age').value;
    var bool = (val_min > val_max);
    if(bool)
    {
        val_min = val_max-1;
        if(val_min <= 0)
            val_min = 1;
        
        jQuery("#jform_min_age").val(val_min);
        jQuery("#jform_max_age").val(val_max);
        setScreenAge(jQuery("#jform_max_age")[0]);
        setScreenAge(jQuery("#jform_min_age")[0]);
    }
}

function setScreenAge(context)
{
    var elem = context.nextSibling.firstChild.firstChild;
    elem.innerHTML = context.value;
}

jQuery(document).ready(function() {
    
    jQuery( "#jform_nicho_nichos" ).tokenInput(
         "../index.php?option=com_fittizen&task=find_nichos&format=json&lang_id=<?php echo $lang_id ?>",
         {
         preventDuplicates: true, queryParam:"needle", tokenDelimiter:'|',
         prePopulate:<?php echo ($json_nicho); ?>, theme: "mac", 
         hintText:"<?php echo JText::_('COM_FITTIZEN_AUTO_COMPLETE_HINT_TEXT') ?>",
         noResultsText:"<?php echo JText::_('COM_FITTIZEN_AUTO_COMPLETE_NO_RESULTS_TEXT') ?>",
         searchingText:"<?php echo JText::_('COM_FITTIZEN_AUTO_COMPLETE_SEARCHING_TEXT') ?>"
         }
    );
    
    jQuery( "#jform_location" ).tokenInput(
         "../index.php?option=com_fittizen&task=find_locations&format=json",
         {
         preventDuplicates: true, queryParam:"address",tokenDelimiter:'|',minChars:3, 
         prePopulate:<?php echo ($json_location); ?>, theme: "mac", propertyToSearch:"address",
         hintText:"<?php echo JText::_('COM_FITTIZEN_AUTO_COMPLETE_HINT_TEXT') ?>",
         noResultsText:"<?php echo JText::_('COM_FITTIZEN_AUTO_COMPLETE_NO_RESULTS_TEXT') ?>",
         searchingText:"<?php echo JText::_('COM_FITTIZEN_AUTO_COMPLETE_SEARCHING_TEXT') ?>"
         }
    );
     
});
</script>
<form action="<?php echo JRoute::_('index.php?option=com_banners&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="banner-form" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_BANNERS_BANNER_DETAILS', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<?php echo $this->form->getControlGroup('type'); ?>
				<div id="image">
					<?php echo $this->form->getControlGroups('image'); ?>
				</div>
				<div id="custom">
					<?php echo $this->form->getControlGroup('custombannercode'); ?>
				</div>
				<?php
				echo $this->form->getControlGroup('clickurl');
				echo $this->form->getControlGroup('description');
				?>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'filters', JText::_('PLG_CONTENT_FILTERS_SLIDER_LABEL', true)); ?>
		<div class="row-fluid">
                    <div class="control-group">
                        <div class="control-label">
                            <label id="jform_nicho_nichos-lbl" for="jform_nicho_nichos" 
                                   class="hasTooltip" title=""
                                   data-original-title="<strong><?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_NICHOS_LABEL'); ?></strong><br /><?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_NICHOS_DESC'); ?>">
                                <?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_NICHOS_LABEL'); ?>
                            </label>

                        </div>
                        <div class="controls">
                            <input type="text" name="nichos" autocomplete="off" id="jform_nicho_nichos">    
                        </div>
                    </div>
                    <!-- Location -->
                    <div class="control-group">
                        <div class="control-label">
                            <label id="jform_location-lbl" for="jform_location" 
                                   class="hasTooltip" title=""
                                   data-original-title="<strong><?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_LOCATION_LABEL'); ?></strong><br /><?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_LOCATION_DESC'); ?>">
                                <?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_LOCATION_LABEL'); ?>
                            </label>

                        </div>
                        <div class="controls">
                            <input type="text" name="location" autocomplete="off" id="jform_location">    
                        </div>
                    </div>
                    <!-- Age Range -->
                    <div class="control-group">
                        <div class="control-label">
                            <label id="jform_age-lbl" for="jform_age" 
                                   class="hasTooltip" title=""
                                   data-original-title="<strong><?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_AGE_LABEL'); ?></strong><br /><?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_AGE_DESC'); ?>">
                                <?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_AGE_LABEL'); ?>
                            </label>

                        </div>
                        <div class="controls">
                              <p>
                                  <select name="min_age" id="jform_min_age" onchange="return _validate_age();">
                                      <?php 
                                      $opt_html="";
                                      for($i=1; $i <= 100; $i++)
                                      {
                                            $sel = "";
                                            if($min_age == $i)
                                            {
                                                $sel = "selected";
                                            }
                                            $opt_html.= "<option value=\"$i\" $sel >$i</option>";
                                      }
                                      echo $opt_html;
                                      ?>
                                  </select>
                                  -
                                  <select name="max_age" id="jform_max_age" onchange="return _validate_age();">
                                      <?php 
                                      $opt_html="";
                                      for($i=1; $i <= 100; $i++)
                                      {
                                            $sel = "";
                                            if($max_age == $i)
                                            {
                                                $sel = "selected";
                                            }
                                            $opt_html.= "<option value=\"$i\" $sel >$i</option>";
                                      }
                                      echo $opt_html;
                                      ?>
                                  </select>
                              </p>
                        </div>
                    </div>
                    <!-- Gender -->
                    <div class="control-group">
                        <div class="control-label">
                            <label id="jform_gender-lbl" for="jform_gender" 
                                   class="hasTooltip" title=""
                                   data-original-title="<strong><?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_GENDER_LABEL'); ?></strong><br /><?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_GENDER_DESC'); ?>">
                                <?php echo JText::_('PLG_CONTENT_FILTERS_FIELD_GENDER_LABEL'); ?>
                            </label>
                        </div>
                        <div class="controls">
                            <select name="gender">
                                <option value=""></option>
                                <?php
                                $opt_html = "";
                                foreach($genders as $gender)
                                {
                                    $sel = "";
                                    if($gender->gender_id == $gender_id)
                                    {
                                        $sel = "selected";
                                    }
                                    $opt_html.= "<option value=\"$gender->id\" $sel >$gender->name</option>";
                                }
                                echo $opt_html;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>    
                    
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'otherparams', JText::_('COM_BANNERS_GROUP_LABEL_BANNER_DETAILS', true)); ?>
		<?php echo $this->form->getControlGroups('otherparams'); ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo $this->form->getControlGroups('metadata'); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>