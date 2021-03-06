<?php 
$jspath = AuxTools::getJSPathFromPHPDir(BASE_DIR); 
$lang_id = AuxTools::GetCurrentLanguageIDJoomla();
$limitstart =0;
if(filter_has_var(INPUT_POST, 'limitstart'))
{
    $limitstart = filter_input(INPUT_POST, 'limitstart');
}
$tmp = new bll_trainers(-1);
$total=count($tmp->findAll(null,null, true,
      $tmp->getPrimaryKeyField()));
$objs  = $tmp->findAll(null,null, true,
      $tmp->getPrimaryKeyField(), $limitstart, NUMBER_ELEMENTS_BY_PAGE);
$data=array();
?>
<div class="span9">
<h3 class="header-title">
<?php
echo JText::_('COM_FITTIZEN_TRAINERS');
?>
</h3>
<ul id="toolbar" class="toolbar-list">
    <li>
        <a href="./index.php?option=com_fittizen" class="btn btn-small">
            <span class="icon-cancel"></span>
            <?php 
                    echo JText::_('COM_FITTIZEN_BACK');
            ?>
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
                    echo JText::_('COM_FITTIZEN_LOCATION');
                    ?>
                </th>
                <th>
                    <?php 
                    echo JText::_('COM_FITTIZEN_RATING');
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
                
                $pro = new bll_fitinfos($obj->fitinfo_id);
                $location = new bll_locations($pro->location_id);
                ?>
            <tr class="row<?php echo ($row_index%2) ?>">
                <td>
                    <?php echo $obj->id; ?>
                </td>
                <td>
                    <?php echo $pro->name." ".$pro->last_name; ?>
                </td>
                <td>
                    <?php echo $location->address; ?>
                </td>
                <td>
                    <?php echo $obj->get_rating(); ?>
                </td>
                <td>
                    <form action="./index.php?option=com_fittizen&view=trainers&layout=edit" method="POST">
                        <input type="hidden" name="id" value="<?php echo $obj->id; ?>" />
                        <input type="hidden" name="limitstart" value="<?php echo $limitstart ?>" />
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
            './index.php?option=com_fittizen&view=trainers', $total, 
            $limitstart, 
            NUMBER_ELEMENTS_BY_PAGE, $data) ?>
</div>