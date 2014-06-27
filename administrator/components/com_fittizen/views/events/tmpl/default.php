<?php 
$lang_id = AuxTools::GetCurrentLanguageIDJoomla();
$limitstart =0;
if(filter_has_var(INPUT_POST, 'limitstart'))
{
    $limitstart = filter_input(INPUT_POST, 'limitstart');
}
$tmp = new bll_events(-1);
$total=count($tmp->findAll(null,null, true,
      $tmp->getPrimaryKeyField()));
$objs  = $tmp->findAll(null,null, true,
      $tmp->getPrimaryKeyField(), $limitstart, NUMBER_ELEMENTS_BY_PAGE);
$data=array();
?>
<div class="span9">
<h3 class="header-title">
<?php
echo JText::_('COM_FITTIZEN_EVENTS');
?>
</h3>
<ul id="toolbar" class="toolbar-list">
    <li>
        <a href="./index.php?option=com_fittizen&view=events&layout=edit" class="btn btn-small">
            <span class="icon-new"></span>
            <?php echo JText::_('COM_FITTIZEN_NEW'); ?>
        </a>
    </li>
</ul>
<p class="adm_total">
<?php 
echo JText::_('COM_FITTIZEN_TOTAL').":".$total;
?>
</p>
</div>
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
                    echo JText::_('COM_FITTIZEN_EVENT_OWNER');
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
                    <?php 
                    if($obj->fitinfo_id > 0)
                    {
                        $fit = new bll_fitinfos($obj->fitinfo_id);
                        echo $fit->name." ".$fit->last_name;
                    }
                    else
                    {
                        echo JText::_('COM_FITTIZEN_FITTIZEN');
                       
                    }
                    ?>
                </td>
                <td>
                    <form action="./index.php?option=com_fittizen&view=events&layout=edit" method="POST">
                        <input type="hidden" name="id" value="<?php echo $obj->id; ?>" />
                        <input type="hidden" name="limitstart" value="<?php echo $limitstart ?>" />
                        <button class="btn btn-small" type="submit">
                            <span class="icon-edit"></span>
                            <?php echo JText::_('COM_FITTIZEN_EDIT'); ?>
                        </button>
                    </form>
                    <a class="btn btn-small" href="./index.php?option=com_fittizen&view=events&mode=delete&id=<?php echo $obj->id; ?>">
                        <span class="icon-trash"></span>
                        <?php echo JText::_('COM_FITTIZEN_DELETE'); ?>
                    </a>
                </td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>
    <?php echo HtmlGenerator::GeneratePagination($tmp->getObjectName(),
            './index.php?option=com_fittizen&view=events', $total, 
            $limitstart, 
            NUMBER_ELEMENTS_BY_PAGE, $data) ?>
</div>