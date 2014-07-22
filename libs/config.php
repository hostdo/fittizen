<?php

if (defined('_JEXEC') == true) 
{
    require_once JPATH_ROOT . "/configuration.php";

    class jdbconfig extends JConfig 
    {

    }

} 
else 
{

    class config 
    {

        
	public $user = 'fittizen_user';
	public $password = 'RL{R~HQ;vC?W';
	public $db = 'fittizen_plugin';
	public $dbprefix = 'mhitx_';
        public $host = 'localhost';
        public $smtpuser = "";
        public $smtpsecure = "";
        public $smtppass = "";
        public $smtpauth = "";
        public $smtpport = "";
        public $smtphost = "";

    }

    
}

