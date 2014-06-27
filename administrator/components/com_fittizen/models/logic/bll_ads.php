<?php
/**
 *
 * Class for managing vinculation of ads and the fittizen component
 * 
 * @author Gabriel Gonzalez Disla
 */
class bll_ads
{
   /**
    * gets all the clicks from the banners
    * @return int number of clicks
    */
   public static function get_total_clicks()
   {
       $query = "SELECT SUM(count) as `clicks` FROM `#__banner_tracks` where `track_type`=2 ";
       $db = new dbprovider();
       $db->Query($query);
       $obj = $db->getNextObject();
       return $obj->clicks;
   }
   /**
    * gets all the impressions from the banners
    * @return int number of impressions
    */
   public static function get_total_impressions()
   {
       $query = "SELECT SUM(count) as `impressions` FROM `#__banner_tracks` where `track_type`=1 ";
       $db = new dbprovider();
       $db->Query($query);
       $obj = $db->getNextObject();
       return $obj->impressions;
   }
   
   /**
    * gets all the clicks from the banners
    * @param string $date_str string with the date
    * @return int number of clicks
    */
   public static function get_total_clicks_by_day($date_str="now")
   {
        $date = AuxTools::DateTimeGenerate($date_str);
        $date2 = AuxTools::DateTimeGenerate($date_str);
        $date->setTime(0, 0, 0);
        $date2->setTime(23,59,59);
        $query = "select SUM(count) as `clicks` from `#__banner_tracks` where `track_type`=2 AND `track_date` BETWEEN '".
                $date->format('Y-m-d H:i:s')."' AND '".$date2->format('Y-m-d H:i:s')."' ";
        $db = new dbprovider(true);
        $db->Query($query);
       $obj = $db->getNextObject();
       return $obj->clicks;
   }
   
   
   /**
    * gets all the impressions from the banners
    * @param string $date_str string with the date
    * @return int number of impressions
    */
   public static function get_total_impressions_by_day($date_str="now")
   {
        $date = AuxTools::DateTimeGenerate($date_str);
        $date2 = AuxTools::DateTimeGenerate($date_str);
        $date->setTime(0, 0, 0);
        $date2->setTime(23,59,59);
        $query = "select SUM(count) as `impressions` from `#__banner_tracks` where `track_type`=1 AND `track_date` BETWEEN '".
                $date->format('Y-m-d H:i:s')."' AND '".$date2->format('Y-m-d H:i:s')."' ";
        $db = new dbprovider(true);
        $db->Query($query);
        $obj = $db->getNextObject();
        return $obj->impressions;
   }
   
   public static function get_active_ads()
   {
        $query = "select COUNT(id) as total from `#__banners` where `state`=1";
        $db = new dbprovider(true);
        $db->Query($query);
        $obj = $db->getNextObject();
        return $obj->total;
   }
}

