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


/**
 * Content nichos relation plugin
 *
 * @package		Joomla.Plugin
 * 
 * @version		1.6
 */
class plgContentNichos extends JPlugin
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
		if ($articleId && isset($article->rating) && (count($article->rating)))
		{
			try
			{
				$db = JFactory::getDbo();
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
				$db = JFactory::getDbo();
				
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
            if (!in_array($name, array('com_content.article')))
            {
                    return true;
            }
            // Add the registration fields to the form.
            JForm::addFormPath(__DIR__ . '/nichos');
            if(!$form->loadFile('nicho', false))
            {
                echo "non-load";
            }

            $fields = array(
                    'nichos',
                    'nichos_id'
            );
            $form->setFieldAttribute('nichos', 'description', 'PLG_CONTENT_NICHOS_FIELD_NICHOS_DESC', 'profile');
            $form->setFieldAttribute('nichos_id', 'description', 'PLG_CONTENT_NICHOS_FIELD_NICHOS_DESC', 'profile');
            
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
		if (!in_array($context, array('com_content.article')))
		{
                    return true;
		}
		if (is_object($data))
		{
                    $nichos_id ="";
                    $nichos_str="";
                    if(isset($data->id) && $data->id > 0)
                    {
                        $nichos=bll_nichos::get_nichos_content($data->id);
                        
                        $lang_id = AuxTools::GetCurrentLanguageIDJoomla();
                        foreach($nichos as $nicho)
                        {
                            $obj = new bll_nichos($nicho->nicho_id);
                            $lval = $obj->getLanguageValue($lang_id);
                            $nichos_id.="$lval->nicho_id,";
                            $nichos_str.="$lval->name,";
                        }
                        $data->nicho=array('nichos'=>$nichos_str,
                        'nichos_id'=>$nichos_id);
                        return true;
                    }
                    else
                    {
                        //is new article
                        $data->nicho=array('nichos'=>$nichos_str,
                        'nichos_id'=>$nichos_id);
                        return true;
                    }
                }
                return false;
        }
}
