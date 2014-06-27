<?php

defined('_JEXEC') or die;

if (!defined('DS'))
{
  define('DS', DIRECTORY_SEPARATOR);
}
$admin_root=JPATH_ROOT.'/administrator/components/com_fittizen/';

require_once $admin_root.'libs/defines.php';
require_once $admin_root.LIBS.INCLUDES;
oDirectory::loadClassesFromDirectory($admin_root.MODELS.DATA);
oDirectory::loadClassesFromDirectory($admin_root.MODELS.LOGIC);

$lang = JFactory::getLanguage();
$extension = 'com_fittizen';
$language_tag = AuxTools::GetCurrentLanguageJoomla();
$reload = true;
$lang->load($extension, $admin_root, $language_tag, $reload);

//function catalogBuildRoute(&$query) {
//  
//  
//}
//
//function CatalogParseRoute($segments) 
//{
//    
//}