<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * View
 */
class FittizenViewLocations extends JViewLegacy
{
         /**
         *  view display method
         * @return void
         */
        function display($tpl = null) 
        {
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                $mode = null;
                if(filter_has_var(INPUT_GET, 'mode'))
                {
                    $mode = filter_input(INPUT_GET, 'mode');
                }
                if(filter_has_var(INPUT_POST, 'mode'))
                {
                    $mode = filter_input(INPUT_POST, 'mode');
                }
                if($mode !== null)
                {
                    
                    switch($mode)
                    {
                        case "save":
                            $this->save();
                        break;
                    
                        case "delete":
                            $this->delete();
                        break;
                    }
                }
                // Display the template
                parent::display($tpl);
        }
        
        public function save()
        {
            $id=filter_input(INPUT_POST, 'id');
            $obj = new bll_locations($id);
            $obj->setAttributes(filter_input_array(INPUT_POST, FILTER_DEFAULT));
            if($obj->id > 0)
            {
                $type='message';
                $str = JText::_('COM_FITTIZEN_ELEMENT_UPDATE_SUCCESS');
                if($obj->update()===false)
                {
                    $type='error';
                    $str = JText::_('COM_FITTIZEN_ELEMENT_UPDATE_FAIL');
                    $str.=":".$obj->getErrorMsg();
                }
            }
            else {
                $type='message';
                $str = JText::_('COM_FITTIZEN_ELEMENT_INSERT_SUCCESS');
                if($obj->insert()===false)
                {
                    $type='error';
                    $str = JText::_('COM_FITTIZEN_ELEMENT_INSERT_FAIL');
                    $str.=": ".$obj->getErrorMsg();
                }
            }
            AuxTools::setJoomlaAlertMessage($str, $type);
            $this->redirect_to_main_view();
        }
        
        public function delete()
        {
            $id=filter_input(INPUT_GET, 'id');
            $obj = new bll_locations($id);
            $type='message';
            $str = JText::_('COM_FITTIZEN_ELEMENT_DELETED_SUCCESS');
            if($obj->id > 0)
            {
                $str = JText::_('COM_FITTIZEN_ELEMENT_DELETED_SUCCESS');
                if($obj->delete()===false)
                {
                    $type='error';
                    $str = JText::_('COM_FITTIZEN_ELEMENT_DELETED_FAIL');
                    $str.=": ".$obj->getErrorMsg();
                }
            }
            else {
                $str = JText::_('COM_FITTIZEN_ELEMENT_DELETED_FAIL');
                $type='error';
                $str.=":".$obj->getErrorMsg();
            }
            AuxTools::setJoomlaAlertMessage($str, $type);
            $this->redirect_to_main_view();
        }
        
        public function redirect_to_main_view()
        {
            $jsbase_path_route = AuxTools::getJSPathFromPHPDir(BASE_DIR);
            $uri=$jsbase_path_route.DS."administrator".DS
                        .JRoute::_('./administrator/index.php?option=com_fittizen&view=locations');
            JFactory::getApplication()->redirect($uri);
        }
}