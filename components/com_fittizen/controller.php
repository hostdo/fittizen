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
        public function quick_register()
        {
            //create user
            $jinput = JFactory::getApplication()->input;
            $email= $jinput->get('email','');
            if(FittizenHelper::validate_email($email)==false)
            {
                //redirect to user newsfeed
                $msg = JText::_('COM_FITTIZEN_INVALID_EMAIL');
                $this->setRedirect('/', $msg, 'error');
                $this->redirect();
            }
            $params=http_build_query(array('email'=>$email));
            $findedUser = FittizenHelper::findUser($email);
            $type_url=JRoute::_(JText::_('COM_FITTIZEN_ACCOUNT_TYPE_SELECT_URI').'&params='.base64_encode($params),false);
            $newsfeed_url=JRoute::_(JText::_('COM_FITTIZEN_NEWSFEED_URI'),false);
            if($findedUser > 0)
            {
                //redirect to user newsfeed
                $this->setRedirect($newsfeed_url);
                $this->redirect();
            }
            else
            {
                //redirect to user account type selection
                $this->setRedirect($type_url);
                $this->redirect();
            }
        }
        public function facebook_login()
        {
            $objs =array();
            $params = filter_input(INPUT_GET, 'params');
            parse_str($params,$objs);
            $email="";
            if(isset($objs['email']))
            {
                $email = $objs['email'];
            }
            if(FittizenHelper::validate_email($email)==false)
            {
                //redirect to user newsfeed
                $msg = JText::_('COM_FITTIZEN_INVALID_EMAIL');
                $this->setRedirect('/', $msg, 'error');
                $this->redirect();
            }
            $findedUser = FittizenHelper::findUser($email);
            $type_url=JRoute::_(JText::_('COM_FITTIZEN_ACCOUNT_TYPE_SELECT_URI').'&params='.base64_encode($params),false);
            $newsfeed_url=JRoute::_(JText::_('COM_FITTIZEN_NEWSFEED_URI'),false);
            
            if($findedUser > 0 && FittizenHelper::login($email) > 0)
            {
                $fitinfo = bll_fitinfos::getProfileByUserId($findedUser);
                $fitinfo->fb_id = $objs['id'];
                $fitinfo->update();
                //redirect to user newsfeed
                $this->setRedirect($newsfeed_url);
                $this->redirect();
            }
            else
            {
                //redirect to user account type selection
                $this->setRedirect($type_url);
                $this->redirect();
            }
        }
        
        public function update_social_account()
        {
            $input = JFactory::getApplication()->input;
            $type = $input->getString('type', "");
            $pid = $input->get('pid', "");
            $val = $input->get('val', NULL);
            $fitinfo = new bll_fitinfos($pid);
            $ret=0;
            if(JFactory::getUser()->id == $fitinfo->user_id)
            {
                switch($type)
                {
                    case "g+":
                        $fitinfo->gplus_id = $val;
                        $fitinfo->update();
                        $ret=1;
                    break;
                    case "fb":
                        $fitinfo->fb_id = $val;
                        $fitinfo->update();
                        $ret=1;
                    break;
                    case "tw":
                        $fitinfo->twitter_id = $val;
                        $fitinfo->update();
                        $ret=1;
                    break;
                }
            }
            ob_clean();
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($ret);
            exit;
        }
        
        public function googleplus_login()
        {
            $objs =array();
            $params = filter_input(INPUT_GET, 'params');
            parse_str($params,$objs);
            $email="";
            if(isset($objs['email']))
            {
                $email = $objs['email'];
            }
            if(filter_var($email, FILTER_SANITIZE_EMAIL)==false)
            {
                //redirect to user newsfeed
                $msg = JText::_('COM_FITTIZEN_INVALID_EMAIL');
                $this->setRedirect('/', $msg, 'error');
                $this->redirect();
            }
            $findedUser = FittizenHelper::findUser($email);
            $type_url=JRoute::_(JText::_('COM_FITTIZEN_ACCOUNT_TYPE_SELECT_URI').'&params='.base64_encode($params),false);
            $newsfeed_url=JRoute::_(JText::_('COM_FITTIZEN_NEWSFEED_URI'),false);
            
            if($findedUser > 0 && FittizenHelper::login($email) > 0)
            {
                //redirect to user newsfeed
                $fitinfo = bll_fitinfos::getProfileByUserId($findedUser);
                $fitinfo->gplus_id = $objs['id'];
                $fitinfo->update();
                $this->setRedirect($newsfeed_url);
                $this->redirect();
            }
            else
            {
                //redirect to user account type selection
                $this->setRedirect($type_url);
                $this->redirect();
            }
        }
        
        public function create_account()
        {
            //create user
            $jinput = JFactory::getApplication()->input;
            $acctype= $jinput->getString('account_type',null);
            $params = base64_decode($jinput->get('params',""));
            $objs=array();
            parse_str($params,$objs);
            $email = $jinput->getString('email','');
            if(FittizenHelper::validate_email($email)==false)
            {
                //redirect to user newsfeed
                $msg = JText::_('COM_FITTIZEN_INVALID_EMAIL');
                $this->setRedirect('/', $msg, 'error');
                $this->redirect();
            }
            $name = $jinput->getString('name','');
            $lastname = $jinput->getString('lastname','');
            $gender = $jinput->get('gender','');
            $objs['name']=$name;
            $objs['email']=$email;
            $objs['lastname']=$lastname;
            $obj_gen=new fittizen_gender_lang($gender);
            $objs['gender']=$obj_gen->name;
            $params = http_build_query($objs);
            //create user
            $jinput->set('mail', $email);
            $jinput->set('username', $email);
            $jinput->set('name', $name." ".$lastname);
            $newsfeed = JRoute::_(JText::_('COM_FITTIZEN_NEWSFEED_URI'), false);
            $type_url=JRoute::_(JText::_('COM_FITTIZEN_ACCOUNT_TYPE_SELECT_URI').'&params='.base64_encode($params),false);
            if($name=="")
            {
                //redirect to user newsfeed
                $msg = JText::_('COM_FITTIZEN_PLEASE_ENTER_YOUR_NAME');
                $this->setRedirect($type_url, $msg, 'error');
                $this->redirect();
            }
            $uid=FittizenHelper::RegisterUser();
            $profile = bll_fitinfos::getProfileByUserId($uid);
            if($profile->id > 0)
            {
                JFactory::getApplication()->enqueueMessage(
                        JText::_('COM_FITTIZEN_ACCOUNT_ALREADY_EXISTS')
                );
            }
            else
            {
                $fb_id=NULL;
                if(isset($params['last_name']))
                {
                    $fb_id = $params['id'];
                }
                $gplus_id=NULL;
                if(isset($params['family_name']))
                {
                    $gplus_id = $params['id'];
                }
                $attributes=array(
                  'name'=> $name,
                  'last_name'=>$lastname,
                  'user_id'=>$uid,
                  'gender_id'=>$gender,  
                  'location_id'=>NULL,
                  'fb_id'=>$fb_id,
                  'gplus_id'=>$gplus_id
                );
                $profile=bll_fitinfos::create($attributes, $acctype);
                
                if($profile !== false && $profile->id < 0)
                {
                    JFactory::getApplication()->enqueueMessage(
                           JText::_('COM_FITTIZEN_ERROR_CREATING_PROFILE')
                    );   
                    $this->setRedirect($type_url);
                }
                else
                {
                    $profile->set_permissions(array("public"=>1));
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
        
        function edit_account()
        {
            $jinput = JFactory::getApplication()->input;
            $pid=$jinput->get('id',0);
            $red_url="";
            $fitinfo = new bll_fitinfos($pid);
            $user = JFactory::getUser();
            $lang = new languages($jinput->get('lid',0));
            if($lang->lang_id > 0 && AuxTools::GetCurrentLanguageIDJoomla() != $lang->lang_id)
            {
                $lang_obj=JFactory::getLanguage();
                $lang_obj->setLanguage($lang->lang_code);
                $lang_obj->load('com_fittizen', JPATH_COMPONENT_ADMINISTRATOR, $lang->lang_code, true);
                //redirect to user newsfeed
                $red_url=JRoute::_("$lang->sef".$lang_obj->_('COM_FITTIZEN_NEWSFEED_URI')."?view=account&layout=edit".$str_params,false);
                $this->setMessage($lang_obj->_('COM_FITTIZEN_LANGUAGE_CHANGED'));
                $this->setRedirect($red_url);
                $this->redirect();
            }
            if($fitinfo->id > 0 && $fitinfo->user_id == $user->id)
            {
                $pass=$jinput->getString('pass',"");
                $pass2=$jinput->getString('pass2',"");
                if($pass != "" && $pass2 != "")
                {
                    FittizenHelper::change_password($pass, $pass2);
                }
                $name=$jinput->getString('name',"");
                $last_name=$jinput->getString('last_name',"");
                $birth_date=$jinput->getString('birth_date',"");
                $profile_code=$jinput->getString('profile_code',"");
                $phone=$jinput->getString('phone',"");
                $public=$jinput->getInt('public',1);
                $gender_id=$jinput->get('gender_id',null);
                $location_id=$jinput->get('location_id',null);
                $user->set('name', $name." ".$last_name);
                $user->save();
                $fitinfo->name = $name;
                $fitinfo->last_name = $last_name;
                $fitinfo->phone = $phone;
                $fitinfo->birth_date = $birth_date;
                $fitinfo->location_id=$location_id;
                $fitinfo->gender_id = $gender_id;
                $fitinfo->profile_code = $profile_code;
                $fitinfo->update();
                $fitinfo->set_permissions(array("public"=>$public));
                
                $this->setMessage(JText::_('COM_FITTIZEN_PROFILE_UPDATE'));
                $red_url=JRoute::_(JText::_('COM_FITTIZEN_NEWSFEED_URI')."?view=account&layout=edit",false);
            }
            else
            {
                $this->setMessage(JText::_('COM_FITTIZEN_INVALID_ACCOUNT'),'error');
            }
            //redirect to user newsfeed
            $this->setRedirect($red_url);
            $this->redirect();
            
        }
        
}

