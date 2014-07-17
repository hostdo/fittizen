<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * View
 */
class FittizenViewComplete extends JViewLegacy
{
        
        public $objname = null;
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
                if(filter_has_var(INPUT_GET, 'obj'))
                {
                    $this->objname = filter_input(INPUT_GET, 'obj');
                }
                if(filter_has_var(INPUT_POST, 'obj'))
                {
                    $this->objname = filter_input(INPUT_POST, 'obj');
                }
                $uri=JRoute::_('/index.php?option=com_fittizen',false);
                $langobj="";
                if(filter_has_var(INPUT_POST, 'langobj'))
                {
                    $langobj = filter_input(INPUT_POST, 'langobj');
                }
                if(!class_exists($this->objname))
                {
                    $this->objname=null;
                    JFactory::getApplication()->enqueueMessage(
                        'COM_FITTIZEN_INVALID_PARAMETER', 'error');
                    $con = new FittizenController();
                    $con->setRedirect($uri);
                    $con->redirect();
                }
                else 
                {
                    $class = new $this->objname(-1);
                    if(!method_exists($this->objname, "checkIncomplete"))
                    {
                        $this->objname=null;
                        JFactory::getApplication()->enqueueMessage(
                            'COM_FITTIZEN_INVALID_PARAMETER', 'error');
                        $con = new FittizenController();
                        $con->setRedirect($uri);
                        $con->redirect();
                    }
                }
                if($mode !== null && $this->objname !== null)
                {
                    switch($mode)
                    {
                        case "save":
                            
                            if(!class_exists($langobj))
                            {
                                JFactory::getApplication()->enqueueMessage(
                                    'COM_FITTIZEN_INVALID_PARAMETER', 'error');
                                
                                $con = new FittizenController();
                                $con->setRedirect($uri);
                                $con->redirect();
                            }
                            $this->save($this->objname);
                        break;
                    }
                }
                // Display the template
                parent::display($tpl);
        }
        
        public function save($objname)
        {
            $id=filter_input(INPUT_POST, 'id');
            $obj = new $objname($id);
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
                $type='error';
                $str = JText::_('COM_FITTIZEN_INVALID_ELEMENT_TO_INSERT');
            }
            AuxTools::setJoomlaAlertMessage($str, $type);
        }
}