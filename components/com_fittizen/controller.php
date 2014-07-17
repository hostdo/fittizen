<?php
/**
 * @version		$Id: controller.php 15 2009-11-02 18:37:15Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * Component Controller
 */
class FittizenController extends JControllerLegacy
{
    
        public function facebook_login()
        {
            $objs =array();
            $params = filter_input(INPUT_GET, 'params');
            parse_str($params,$objs);
            $email=$name=$gname=$lastname=$middlename="";
            if(isset($objs['email']))
            {
                $email = $objs['email'];
            }
            if(isset($objs['first_name']))
            {
                $name = $objs['first_name'];
            }
            if(isset($objs['last_name']))
            {
                $lastname = $objs['last_name'];
            }
            if(isset($objs['middle_name']))
            {
                $middlename = $objs['middle_name'];
            }
            $findedUser = FittizenHelper::findUser($email);
            $type_url=JRoute::_(JText::_('COM_FITTIZEN_ACCOUNT_TYPE_SELECT_URI').'&params='.base64_encode($params),false);
            $newsfeed_url=JRoute::_(JText::_('COM_FITTIZEN_NEWSFEED_URI'),false);
            
            if($findedUser > 0)
            {
                //login and assing fbid to profile
                $uid = FittizenHelper::login($email);
                
                $profile = bll_fitinfos::getProfileByUserId($uid);
                if($profile->id <= 0)
                {
                    //redirect to user type selection
                    $this->setRedirect($type_url);
                    $this->redirect();
                }
                else
                {
                    //redirect to user newsfeed
                    $this->setRedirect($newsfeed_url);
                    $this->redirect();
                }
            }
            else
            {
                //create user
                $jinput = JFactory::getApplication()->input;
                $jinput->set('mail', $email);
                $jinput->set('username', $email);
                $jinput->set('name', $name." ".$middlename." ".$lastname);
                FittizenHelper::RegisterUser();
                //redirect to user type selection
                $this->setRedirect($type_url);
                $this->redirect();
            }
        }
        
        public function create_account()
        {
            //create user
            $jinput = JFactory::getApplication()->input;
            $uid=$jinput->get('userid', 0);
            $acctype= $jinput->get('account_type',null);
            $params= base64_decode($jinput->get('params',""));
            $objs=array();
            parse_str($params,$objs);
            $profile = bll_fitinfos::getProfileByUserId($uid);
            $newsfeed = JRoute::_(JText::_('COM_FITTIZEN_NEWSFEED_URI'), false);
            $type_url=JRoute::_(JText::_('COM_FITTIZEN_ACCOUNT_TYPE_SELECT_URI').'&params='.base64_encode($params),false);
            if($profile->id > 0)
            {
                JFactory::getApplication()->enqueueMessage(
                        JText::_('COM_FITTIZEN_ACCOUNT_ALREADY_EXISTS')
                );
            }
            else
            {
                $email=$name=$gname=$lastname=$middlename="";
                if(isset($objs['email']))
                {
                    $email = $objs['email'];
                }
                if(isset($objs['first_name']))
                {
                    $name = $objs['first_name'];
                }
                if(isset($objs['last_name']))
                {
                    $lastname = $objs['last_name'];
                }
                if(isset($objs['middle_name']))
                {
                    $middlename = $objs['middle_name'];
                }
                if(isset($objs['gender']))
                {
                    $gname = $objs['gender'];
                }
                $gender = bll_gender::find_gender($gname);
                $attributes=array(
                  'name'=> $name.' '.$middlename,
                  'last_name'=>$lastname,
                  'user_id'=>$uid,
                  'gender_id'=>$gender->id,  
                  'location_id'=>NULL
                );
                $profile=bll_fitinfos::create($attributes, $acctype);
                
                if($profile !== false && $profile->id < 0)
                {
                    JFactory::getApplication()->enqueueMessage(
                           JText::_('COM_FITTIZEN_ERROR_CREATING_PROFILE')
                    );   
                    $this->setRedirect($type_url);
                }
            }
            $this->setRedirect($newsfeed);
            $this->redirect();
        }
        
        public function find_locations()
        {
            $needle = filter_input(INPUT_GET, 'address');
            $objs=array();
            if($needle != "")
            {
                $objs=  bll_locations::find_locations($needle, 
                        false,'address');
            }
            ob_clean();
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($objs);
            exit;
        }
        
        public function find_country()
        {
            $needle = filter_input(INPUT_GET, 'country');
            $objs=array();
            $tmp = array();
            if($needle != "")
            {
                $bl = new bll_locations(-1);
                foreach($bl->findAll(
                        array(
                            array('country', 'like')
                        ), 
                        array(
                            array($needle, null)
                        )) as $obj)
                {
                    if(!isset($tmp[$obj->country]))
                    {
                        $tmp[$obj->country]=1;
                        $objs[]=$obj;
                    }
                }
            }
            ob_clean();
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($objs);
            exit;
        }
        
        public function find_city()
        {
            $needle = filter_input(INPUT_GET, 'city');
            $objs=array();
            if($needle != "")
            {
                $bl = new bll_locations(-1);
                $objs=$bl->findAll(
                        array(
                            array('locality', 'like')
                        ), 
                        array(
                            array($needle, null)
                        ));
            }
            ob_clean();
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($objs);
            exit;
        }
        
        public function find_nichos()
        {
            $needle = filter_input(INPUT_GET, 'needle');
            $exclude = filter_input(INPUT_GET, 'exclude');
            $lang_id = filter_input(INPUT_GET, 'lang_id');
            if($lang_id == "")
            {
                $lang_id=  AuxTools::GetCurrentLanguageIDJoomla();
            }
            $objs=array();
            if($needle != "")
            {
                $bl = new fittizen_nichos_lang(-1);
                $field=array(
                            array('name', 'like'),
                            array('lang_id', '=')
                        );
                $value=array(
                            array($needle, null),
                            array($lang_id, 'AND')
                        );
                $exclude_arr = explode(',', $exclude);
                if($exclude_arr != false)
                {
                    foreach($exclude_arr as $excluded)
                    {
                        $name=trim($excluded);
                        if($name != $needle)
                        {
                            $field[]=array('name', '<>');
                            $value[]=array($name, 'AND');
                        }
                    }
                }
                $objs=$bl->findAll(
                        $field, 
                        $value
                        );
            }
            ob_clean();
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($objs);
            exit;
        }
        
        public function validate_profile_code()
        {
            $needle = filter_input(INPUT_GET, 'code');
            $response=false;
            if($needle != "")
            {
                $response= bll_fitinfos::check_profile_code($needle);
            }
            ob_clean();
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($response);
            exit;
        }
}
