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
            header('Content-type: application/json');
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
            header('Content-type: application/json');
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
            header('Content-type: application/json');
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
            header('Content-type: application/json');
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
            header('Content-type: application/json');
            echo json_encode($response);
            exit;
        }
}
