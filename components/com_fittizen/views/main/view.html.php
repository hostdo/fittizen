<?php

/**
 * @version		$Id: view.html.php 15 2009-11-02 18:37:15Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org/gf/project/helloworld_1_6/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Component
 */
class FittizenViewMain extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null) 
	{
            switch($this->_layout)
            {
                case "default":
                    FittizenHelper::redirect_logged_in_user();
                break;
                default:
                    
                break;
            }
            // Display the view
            parent::display($tpl);
	}
        
}
