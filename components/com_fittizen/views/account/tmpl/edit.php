<?php

$user = JFactory::getUser();
$fitinfo = bll_fitinfos::getProfileByUserId($user->id);
$lang = new languages(AuxTools::GetCurrentLanguageIDJoomla());

$languages = $fitinfo->setCheckboxValues('title_native',
        'lang_id',array($lang), 'lang_id', "",
        languages::GetLanguages());
$form = Form::getInstance();
?>
<script>
        jQuery(function() {
            jQuery( "#bday" ).datetimepicker({ format:"Y-m-i'", lang:"<?php echo $lang->sef ?>", timepicker:false,});
        });
</script>

<div class="span12">
    <form method="POST" action="">
            <div class="span6">
                <label><?php echo JText::_('COM_FITTIZEN_NAME') ?></label>
                <input type="text" name="name" value="<?php echo $fitinfo->name; ?>" />
                <label><?php echo JText::_('COM_FITTIZEN_LAST_NAME') ?></label>
                <input type="text" name="last_name" value="<?php echo $fitinfo->last_name; ?>" />
                <label><?php echo JText::_('COM_FITTIZEN_EMAIL') ?></label>
                <input type="text" name="email" value="<?php echo $user->email; ?>" disabled="disabled" />
                <label><?php echo JText::_('COM_FITTIZEN_PASSWORD') ?></label>
                <input type="text" name="pass" value="" />
                <label><?php echo JText::_('COM_FITTIZEN_REPEAT_PASSWORD') ?></label>
                <input type="text" name="pass2" value="" />
                <label><?php echo JText::_('COM_FITTIZEN_BIRTH_DATE') ?></label>
                <input type="text" id="bday" name="birth_date" value="" />
            </div>
            <div class="span6">
                <label><?php echo JText::_('COM_FITTIZEN_DEFAULT_LANGUAGE') ?></label>
                <?php 
                    $form->SelectBox('lid', $languages);
                    echo $form->renderFields();
                ?>
            </div>
    </form>
</div>