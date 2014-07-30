<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * Fittizen component helper.
 */
abstract class FittizenHelper
{  
    public static function validate_email($email)
    {
        if(filter_var($email, FILTER_SANITIZE_EMAIL)==false)
        {
            return false;
        }
        return true;
    }
    
    public static function redirect_logged_in_user()
    {
        if(!JFactory::getUser()->guest)
        {
            $newsfeed_url=JRoute::_(JText::_('COM_FITTIZEN_NEWSFEED_URI'),false);
            //login and assing fbid to profile
            $uid = JFactory::getUser()->id;
            $profile = bll_fitinfos::getProfileByUserId($uid);
            $con = new FittizenController();
            if($profile->id > 0)
            {
                //redirect to user newsfeed
                $con->setRedirect($newsfeed_url);
                $con->redirect();
            }
            else
            {
                JFactory::getApplication()->logout($uid);
                $session = JFactory::getSession();
                $session->destroy();
                $session->start();
                //redirect to user newsfeed
                $con->setRedirect(JRoute::_('/',false), JText::_('COM_FITTIZEN_PROFILE_DOES_NOT_EXISTS'));
                $con->redirect();
            }
            return true;
        }
        return false;
    }
    
    public static function RegisterUser()
    {
        jimport( 'joomla.user.helper' );
        JPluginHelper::importPlugin('user');
        $jinput = JFactory::getApplication()->input;
        $password = self::generatePassword();
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
        unset($temp['option']);
        $vars = http_build_query($temp);
        $user_creation_fail_redirect=$jinput->get("user_creation_fail_redirect",
                $language->sef."/");
        $con = new FittizenController();
        $con->setRedirect($user_creation_fail_redirect."?".$vars);
        //validates if the username field is empty
        if ($value == "") {
          $con->setMessage(JText::_("COM_FITTIZEN_INVALID_USERNAME"), 'error');
          $con->redirect();
          return false;
        }
        //validates if is a new or old user.
        if ($id <= 0) {
          $isNew = true;
          $us = JFactory::getUser();
          //Checking if user exist
          $r = self::findUser($email);

          //if user exists we cannot procced
          if ($r > 0) {
            $con->setMessage(JText::_("COM_FITTIZEN_EMAIL_EXISTS"), 'error');
            $con->redirect();
            return false;
          }
          $cache = JFactory::getCache();
          $cache->clean();
        } else {
          $us = JFactory::getUser($id);
          $r = self::findUser($email);
          //if user does not exists we cannot procced
          if ($r > 0) {
            $session = JFactory::getSession();
            $session->destroy();
            $session->start();
            $con->setMessage(JText::_("COM_FITTIZEN_LOGOUT_CLEAN_COOKIES"), 'error');
            $con->redirect();
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
            $con->setMessage(JText::_("COM_FITTIZEN_ERROR_CREATING_PASSWORD"), 'error');
            $con->redirect();
            return false;
        }
        $salt = JUserHelper::genRandomPassword(32);
        $crypt = JUserHelper::getCryptedPassword($pass, $salt);
        $pass = $crypt . ':' . $salt;
        $us->password = $pass;
        if ($us->id <= 0)
        {
            $us->password_clear = $jinput->getString("password", "");
        }
        $us->email = $email;
        if(self::validate_email($us->email)==false)
        {
            $session = JFactory::getSession();
            $session->destroy();
            $session->start();
            $con->setMessage(JText::_("COM_FITTIZEN_INVALID_EMAIL"), 'error');
            $con->redirect();
            return false;
        }
        $us->name = $jinput->getString("name", "");

        if ($us->username == "" || $us->email == "")
        {
            $session = JFactory::getSession();
            $session->destroy();
            $session->start();
            $con->setMessage(JText::_("COM_FITTIZEN_INVALID_USERNAME_EMAIL"), 'error');
            $con->redirect();
            return false;
        }
        //end of getting profile info
        if (!$us->save()) {
          $con->setMessage(JText::sprintf('COM_FITTIZEN_REGISTRATION_SAVE_FAILED', $us->getError()));
          $con->redirect();
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
        
        if ($isNew == true) {
          $dbo = new dbprovider();
          $query = "INSERT INTO `#__user_usergroup_map` (`user_id`, `group_id`) VALUES($us->id, $regis_group) ";
          $dbo->Query($query);
        }
        //$con->setMessage(JText::_('COM_FITTIZEN_ACCOUNT_CREATED'));
        return $us->id;
    }
        
    /**
     * Perform a login in the system
     * @param string $email string with the email of the user to login
     * @return int id of the loggedin user, -1 otherwise
     */
    public static function login($email)
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
              $return = self::findUser($email);
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
              //$app->checkSession();
              //$app->login( Array( 'username' => $user->username, 'password' => $user->password ));
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


    private static function generatePassword() 
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
   
   
        
    /**
     * Find the user by its email
     * @param string $email email of the account
     * @return integer id of the user, -1 if user does not exist
     */
    public static function findUser($email="")
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
}