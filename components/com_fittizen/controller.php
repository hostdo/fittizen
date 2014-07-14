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
            parse_str(filter_input(INPUT_GET, 'params'),$objs );
            ob_clean();
            header('Content-type: application/json; charset=utf-8');
            
            exit;
        }
        
        /**
         * Find the user by its email
         * @param string $email email of the account
         * @return integer id of the user, -1 if user does not exist
         */
        private function findUser($email="")
        {
            $dbo = new dbprovider(true);
            $query = "select * from #__users where email='" . $dbo->escape_string($email) . "'";
            $dbo->Query($query);
            $r = $dbo->getNextObject();
            if ($r != null) {
               return $r->id;
            }
            return -1;
        }
        
        private function RegisterUser()
        {
            jimport( 'joomla.user.helper' );
            JPluginHelper::importPlugin('user');
            $jinput = JFactory::getApplication()->input;
            $password = $this->generatePassword();
            $jinput->set("password", $password);
            $jinput->set("password2", $password);
            $language = new languages(AuxTools::GetCurrentLanguageIDJoomla());
            $lang = JFactory::getLanguage();
            $lang->load('com_users', JPATH_SITE, $language->lang_code, true);
            $email=$jinput->getString("mail", "");
            $id=0;
            $us = null;
            $isNew = false;
            $dbo = new dbprovider(true);
            $value = $jinput->getString("username", "");
            $temp=$jinput->getArray();
            unset($temp['task']);
            $vars = http_build_query($temp);
            $user_creation_fail_redirect=$jinput->get("user_creation_fail_redirect",
                    "");
            $this->setRedirect($user_creation_fail_redirect."?".$vars);
            //validates if the username field is empty
            if ($value == "") {
              JFactory::getApplication()->enqueueMessage(JText::_("COM_FITTIZEN_INVALID_USERNAME"), 'error');
              $this->redirect();
              return false;
            }
            //validates if is a new or old user.
            if ($id <= 0) {
              $isNew = true;
              $us = JFactory::getUser();
              //Checking if user exist
              $r = $this->findUser($email);

              //if user exists we cannot procced
              if ($r > 0) {
                JFactory::getApplication()->enqueueMessage(JText::_("COM_FITTIZEN_USERNAME_EXISTS"), 'error');
                $this->redirect();
                return false;
              }
              $cache = JFactory::getCache();
              $cache->clean();
            } else {
              $us = JFactory::getUser($id);
              $r = $this->findUser($email);
              //if user does not exists we cannot procced
              if ($r > 0) {
                session_destroy();
                JFactory::getApplication()->enqueueMessage(JText::_("COM_FITTIZEN_LOGOUT_CLEAN_COOKIES"), 'error');
                $this->redirect();
                return false;
              }
            }

            $params = JComponentHelper::getParams('com_users');
            $us->username = $value;
            $us->guest=0;
            $regis_group=2;
            $us->groups=array("$regis_group"=>$regis_group);
            $us->activation = "";
            $us->block = 0;
            $pass = $jinput->getString("password", "");
            $pass2 = $jinput->getString("password2", "");

            //password fields must not be an empty string
            if ($pass == "" || $pass2 == "")
            {
                JFactory::getApplication()->enqueueMessage(JText::_("COM_FITTIZEN_ERROR_CREATING_PASSWORD"), 'error');
                $this->redirect();
                return false;
            }
            $salt = JUserHelper::genRandomPassword(32);
            $crypt = JUserHelper::getCryptedPassword($pass, $salt);
            $pass = $crypt . ':' . $salt;
            $us->password = $pass;
            if ($us->id <= 0)
              $us->password_clear = $jinput->getString("password", "");;


            $us->email = $email;
            $re = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
            if(preg_match($re, $us->email) !==1)
            {
                JFactory::getApplication()->enqueueMessage(JText::_("COM_FITTIZEN_INVALID_EMAIL"), 'error');
                $this->redirect();
                return false;
            }
            $us->name = $jinput->getString("name", "");

            if ($us->name == "" || $us->email == "")
            {
                JFactory::getApplication()->enqueueMessage(JText::_("COM_FITTIZEN_INVALID_USERNAME_EMAIL"), 'error');
                $this->redirect();
                return false;
            }
            
            //end of getting profile info
            if (!$us->save()) {
              $this->setMessage(JText::sprintf('COM_FITTIZEN_REGISTRATION_SAVE_FAILED', $us->getError()));
              $this->redirect();
              return false;
            }

            // Compile the notification mail values.
            $data = $us->getProperties();
            $config = new JConfig();
            $data['fromname'] = $config->fromname;
            $data['mailfrom'] = $config->mailfrom;
            $data['sitename'] = $config->sitename;
            $data['siteurl'] = JUri::root();


            $emailSubject = JText::sprintf('COM_FITTIZEN_EMAIL_ACCOUNT_DETAILS', $data['name'], $data['sitename']);
            $emailBody = JText::sprintf('COM_FITTIZEN_EMAIL_REGISTERED_BODY_PW', $data['name'], $data['sitename'], $data['siteurl'], $data['username'], $data['password_clear']);
            $mailer = JFactory::getMailer();
            $mailer->isHtml(true);
            $mailer->useSMTP($config->smtpauth, $config->smtphost,
                    $config->smtpuser, $config->smtppass, $config->smtpsecure, $config->smtpport );

            // Send the registration email.
            $mailer->sendMail(
                    $data['mailfrom'], $data['fromname'], 
                    $data['email'], $emailSubject, $emailBody);
            $mailer->ClearAllRecipients();
            $this->setRedirect("");
            if ($isNew == true) {
              $dbo = new dbprovider();
              $query = "INSERT INTO `#__user_usergroup_map` (`user_id`, `group_id`) VALUES($us->id, $regis_group) ";
              $dbo->Query($query);
            }
            JFactory::getApplication()->enqueueMessage(JText::_('COM_FITTIZEN_ACCOUNT_CREATED'));
            return $us->id;
        }
        
        /**
         * Perform a login in the system
         * @param string $email string with the email of the user to login
         * @return int id of the loggedin user, -1 otherwise
         */
        private function login($email)
        {
            if(JFactory::getUser()->guest==false && JFactory::getUser()->id > 0)
            {
                $us = JFactory::getUser();
                //getting profile info
                $uid = $us->id;
                //end of getting profile info
            }
            else {
                 //create user account
                  
                  //if a problem occurs during registration
                  //function registerUser will redirect and report error.
                  $return = $this->findUser($email);
                  if($return <= 0)
                      return -1;
                  $user = JFactory::getUser($return);

                  //Set free plan as default one on register
                  $app = JFactory::getApplication();
                  $jdb = JFactory::getDbo();
                  // Mark the user as logged in
                  $user->set('guest', 0);

                  // Register the needed session variables
                  $session = JFactory::getSession();
                  $session->set('user', $user);

                  // Check to see the the session already exists.
                  $app->checkSession();
                  $app->login( Array( 'username' => $user->username, 'password' => $user->password ));
                  // Update the user related fields for the Joomla sessions table.
                  $query = $jdb->getQuery(true)
                          ->update($jdb->quoteName('#__session'))
                          ->set($jdb->quoteName('guest') . ' = ' . $jdb->quote($user->guest))
                          ->set($jdb->quoteName('username') . ' = ' . $jdb->quote($user->username))
                          ->set($jdb->quoteName('userid') . ' = ' . (int) $user->id)
                          ->where($jdb->quoteName('session_id') . ' = ' . $jdb->quote($session->getId()));
                  $jdb->setQuery($query)->execute();

                  // Hit the user last visit field
                  $user->setLastVisit();
                  $uid = $user->id;
            }
            return $uid;
        }
        
    
        function generatePassword() 
        {
            $alpha = "abcdefghijklmnopqrstuvwxyz";
            $alpha_upper = strtoupper($alpha);
            $numeric = "0123456789";
            $chars = "";

            $chars = $alpha . $alpha_upper . $numeric;
            $length = 8;

            $len = strlen($chars);
            $pw = '';

            for ($i = 0; $i < $length; $i++)
              $pw .= substr($chars, rand(0, $len - 1), 1);

            $pw = str_shuffle($pw);
            return $pw;
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
            $lang_id=  AuxTools::GetCurrentLanguageIDJoomla();
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
