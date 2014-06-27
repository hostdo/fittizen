<?php 
$lang_id = AuxTools::GetCurrentLanguageIDJoomla();

$limitstart =0;
if(filter_has_var(INPUT_POST, 'limitstart'))
{
    $limitstart = filter_input(INPUT_POST, 'limitstart');
}
$tmp = new $this->objname(-1);
$total=count($tmp->checkIncomplete(true,
      $tmp->getPrimaryKeyField()));
$objs  = $tmp->checkIncomplete( true,
      $tmp->getPrimaryKeyField(), $limitstart, NUMBER_ELEMENTS_BY_PAGE);

$data=array('objname'=>$this->objname);

if($total <= 0)
{
    $jsbase_path_route = AuxTools::getJSPathFromPHPDir(BASE_DIR);
    $uri=$jsbase_path_route.DS."administrator".
            DS.JRoute::_('/index.php?option=com_fittizen');
                
    JFactory::getApplication()->enqueueMessage(
        'COM_FITTIZEN_NO_CONTENT_COMPLETE', 'error');
    JFactory::getApplication()->redirect($uri);
}

?>
<div class="span9">
    <h3 class="header-title">
    <?php
    echo JText::_('COM_FITTIZEN_CONTENT_COMPLETE');
    ?>
    </h3>
    <p class="adm_total">
    <?php 
    echo JText::_('COM_FITTIZEN_TOTAL').":".$total;
    ?>
    </p>
<div>
<div id="list-container" class="span9">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <?php 
                    echo JText::_('COM_FITTIZEN_ID');
                    ?>
                </th>
                <th>
                    <?php 
                    echo JText::_('COM_FITTIZEN_NAME');
                    ?>
                </th>
                <th>
                    <?php 
                    echo JText::_('COM_FITTIZEN_ACTIONS');
                    ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php for($i=0, $row_index=1; $i < count($objs); $i++, $row_index++): 
                $obj = $objs[$i];
                ?>
            <tr class="row<?php echo ($row_index%2) ?>">
                <td>
                    <?php echo $obj->id; ?>
                </td>
                <td>
                    <?php echo $obj->name; ?>
                </td>
                <td>
                    <form action="./index.php?option=com_fittizen&view=complete&layout=edit" method="POST">
                        <input type="hidden" name="id" value="<?php echo $obj->id; ?>" />
                        <input type="hidden" name="limitstart" value="<?php echo $limitstart ?>" />
                        <input type="hidden" name="obj" value="<?php echo $this->objname; ?>"/>
                        <input type="hidden" name="langobj" value="<?php echo $obj->getObjectName(); ?>"/>
                        <button class="btn btn-small" type="submit">
                            <span class="icon-edit"></span>
                            <?php echo JText::_('COM_FITTIZEN_EDIT'); ?>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
    <?php echo HtmlGenerator::GeneratePagination($tmp->getObjectName(),
            './index.php?option=com_fittizen&view=complete', $total, 
            $limitstart, 
            NUMBER_ELEMENTS_BY_PAGE, $data) ?>
</div>