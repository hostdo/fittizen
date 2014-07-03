<?php
/**
 * @package		Joomla.Site
 * @subpackage	plg_content_rating
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
jimport('joomla.utilities.date');
if(!defined('DS'))
{
    define('DS', '/');
}
require_once JPATH_ROOT.DS."libs/defines.php";
require_once JPATH_ROOT.DS.LIBS.INCLUDES;
$jpathadm = JPATH_ADMINISTRATOR.DS.COMPONENTS."com_fittizen";
oDirectory::loadClassesFromDirectory($jpathadm.DS.MODELS.DS.DATA);
oDirectory::loadClassesFromDirectory($jpathadm.DS.MODELS.DS.LOGIC);
$lang = JFactory::getLanguage();
$extension = 'com_fittizen';
$language_tag = AuxTools::GetCurrentLanguageJoomla();
$reload = true;
$lang->load($extension, $jpathadm, $language_tag, $reload);


/**
 * Content nichos relation plugin
 *
 * @package		Joomla.Plugin
 * 
 * @version		1.6
 */
class plgContentFilters extends JPlugin
{
    
        /**
         * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
         * If you want to support 3.0 series you must override the constructor
         *
         * @var    boolean
         * @since  3.1
         */
        protected $autoloadLanguage = true;
        
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       2.5
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Example after save content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved
	 *
	 * @param	string		The context of the content passed to the plugin (added in 1.6)
	 * @param	object		A JTableContent object
	 * @param	bool		If the content is just about to be created
	 * @since	2.5
	 */
	public function onContentAfterSave($context, $article, $isNew)
	{
		$articleId	= $article->id;
                $input = JFactory::getApplication()->input->getArray();
                
                $nicho=array();
                $location=array();
                if(isset($input['nichos']))
                {
                    $nichos=$input['nichos'];
                    $nichos_arr=explode(',', $nichos);
                    if($nichos_arr !== false)
                    {
                        foreach($nichos_arr as $nicho_str)
                        {
                            $tmp_nicho=new fittizen_nichos_lang($nicho_str);
                            if($tmp_nicho->id > 0)
                            {
                                $nicho[]= $tmp_nicho;
                            }
                        }
                    }
                }
                if(isset($input['location']))
                {
                    $locations=$input['location'];
                    $locations_arr=explode(',', $locations);
                    if($locations_arr !== false)
                    {
                        foreach($locations_arr as $location_str)
                        {
                            $tmp_l=new bll_locations($location_str);
                            if($tmp_l->id > 0)
                            {
                                $location[]= $tmp_l;
                            }
                        }
                    }
                }
                $min_age="";
                $max_age="";
                $gender="";
                if(isset($input['gender']))
                {
                    $gender=$input['gender'];
                }
                if(isset($input['max_age']))
                {
                    $max_age = $input['max_age'];
                }
                if(isset($input['min_age']))
                {
                    $min_age = $input['min_age'];
                }
		if ($articleId && count($nicho) > 0)
		{
			try
			{
                            bll_ads::remove_nichos_banner($articleId);
                            foreach($nicho as $obj_nicho)
                            {
                                bll_ads::add_nicho_banner($articleId, $obj_nicho->nicho_id);
                            }
                            bll_ads::remove_locations_banner($articleId);
                            foreach($location as $obj_lo)
                            {
                                bll_ads::add_location_banner($articleId, $obj_lo->id);
                            }
                            bll_ads::remove_filters_banner($articleId);
                            bll_ads::add_filter_banner($articleId, $gender, $max_age, $min_age);
                            
			}
			catch (Exception $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}

	/**
	 * Finder after delete content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved
	 *
	 * @param	string		The context of the content passed to the plugin (added in 1.6)
	 * @param	object		A JTableContent object
	 * @since   2.5
	 */
	public function onContentAfterDelete($context, $article)
	{
		
		$articleId	= $article->id;
		if ($articleId)
		{
                    try
                    {
                        bll_ads::remove_nichos_banner($articleId);
                        bll_ads::remove_locations_banner($articleId);
                        bll_ads::remove_filters_banner($articleId);
                    }
                    catch (Exception $e)
                    {
                            $this->_subject->setError($e->getMessage());
                            return false;
                    }
		}

		return true;
	}
	
        /**
	 * adds additional fields to the user editing form
	 *
	 * @param   JForm  $form  The form to be altered.
	 * @param   array  $data  The associated data for the form.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function onContentPrepareForm($form, $data)
        {
            if (!($form instanceof JForm))
            {
                    $this->_subject->setError('JERROR_NOT_A_FORM');
                    return false;
            }
            // Check we are manipulating a valid form.
            $name = $form->getName();
            if (!in_array($name, array('com_banners.banner')))
            {
                    return true;
            }
            
            return true;
        }
        
        /**
	 * Runs on content preparation
	 *
	 * @param   string   $context  The context for the data
	 * @param   integer  $data     The user id
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function onContentPrepareData($context, $data)
	{
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_banners.banner')))
		{
                    return true;
		}
		if (is_object($data))
		{
                    $input = JFactory::getApplication()->input;
                    $nichos_arr=array();
                    $location_arr=array();
                    $filter = new fittizen_banner_filter(-1);
                    $age_rate = "5 , 90";
                    if(isset($data->id) && $data->id > 0)
                    {
                        $nichos=bll_ads::get_nichos_banner($data->id);
                        $locations = bll_ads::get_locations_banner($data->id);
                        $filter=bll_ads::get_filters_banner($data->id);
                        $lang_id = AuxTools::GetCurrentLanguageIDJoomla();
                        $gender = new fittizen_gender_lang(-1);
                        $genders = $gender->findAll('lang_id', $lang_id);
                        foreach($nichos as $nicho)
                        {
                            $obj = new bll_nichos($nicho->nicho_id);
                            $lval = $obj->getLanguageValue($lang_id);
                            $nichos_arr[]=$lval;
                        }
                        foreach($locations as $location)
                        {
                            $obj = new bll_locations($location->location_id);
                            $location_arr[]=$obj;
                        }
                    }
                    $input->set('nicho', json_encode($nichos_arr));
                    $input->set('location', json_encode($location_arr));
                    $input->set('filter', json_encode($filter));
                    $input->set('gender', json_encode($genders));
                    return true;
                }
                return false;
        }
}
