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
       if($obj->clicks == "")
       {
            return 0;
       }
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
       if($obj->impressions == "")
       {
            return 0;
       }
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
        if($obj->clicks == "")
        {
            return 0;
        }
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
        if($obj->impressions == "")
        {
            return 0;
        }
        return $obj->impressions;
   }
   
   public static function get_active_ads()
   {
        $query = "select COUNT(id) as total from `#__banners` where `state`=1";
        $db = new dbprovider(true);
        $db->Query($query);
        $obj = $db->getNextObject();
        if($obj->total == "")
        {
            return 0;
        }
        return $obj->total;
   }
   
   /**
    * Adds a nicho to a banner
    * @param integer $bid banner id
    * @param integer $nid nicho id
    * @return fittizen_banner_nichos not false on success
    */
   public static function add_nicho_banner($bid, $nid)
   {
       $obj = new fittizen_banner_nichos(-1);
       $obj->banner_id = $bid;
       $obj->nicho_id = $nid;
       return $obj->insert();
   }
   
   /**
    * Searchs the nichos from a banner
    * @param integer $bid banner id $bid
    * @return fittizen_banner_nichos array of dbobject not false on success
    */
   public static function get_nichos_banner($bid)
   {
       $obj = new fittizen_banner_nichos(-1);
       return $obj->findAll('banner_id', $bid);
   }
   
   /**
    * Delete the nichos from a banner
    * @param integer $bid banner id $bid
    * @return fittizen_banner_nichos not false on success
    */
   public static function remove_nichos_banner($bid)
   {
       $obj = new fittizen_banner_nichos(-1);
       return $obj->delete('banner_id', $bid);
   }
   
   /**
    * Adds a filter to a banner
    * @param integer $bid banner id
    * @param integer $gid gender id
    * @param integer $max_age max age of the banner
    * @param integer $min_age min age of the banner
    * @return fittizen_banner_filter not false on success
    */
   public static function add_filter_banner($bid, $gid, $max_age, $min_age)
   {
       $obj = new fittizen_banner_filter(-1);
       $obj->banner_id = $bid;
       $obj->gender_id = $gid;
       $obj->max_age = $max_age;
       $obj->min_age = $min_age;
       return $obj->insert();
   }
   
   /**
    * Searchs the filters from a banner
    * @param integer $bid banner id $bid
    * @return fittizen_banner_filter dbobject not false on success
    */
   public static function get_filters_banner($bid)
   {
       $obj = new fittizen_banner_filter(-1);
       return $obj->find('banner_id', $bid);
   }
   
   /**
    * Delete the filters from a banner
    * @param integer $bid banner id $bid
    * @return fittizen_banner_filters not false on success
    */
   public static function remove_filters_banner($bid)
   {
       $obj = new fittizen_banner_filters(-1);
       return $obj->delete('banner_id', $bid);
   }
   
   /**
    * Adds a location to a banner
    * @param integer $bid banner id
    * @param integer $lid location id
    * @return fittizen_banner_locations not false on success
    */
   public static function add_location_banner($bid, $lid)
   {
       $obj = new fittizen_banner_locations(-1);
       $obj->banner_id = $bid;
       $obj->location_id = $lid;
       return $obj->insert();
   }
   
   /**
    * Searchs the locations from a banner
    * @param integer $bid banner id $bid
    * @return fittizen_banner_locations array of dbobject not false on success
    */
   public static function get_locations_banner($bid)
   {
       $obj = new fittizen_banner_locations(-1);
       return $obj->findAll('banner_id', $bid);
   }
   
   /**
    * Delete the locations from a banner
    * @param integer $bid banner id $bid
    * @return fittizen_banner_locations not false on success
    */
   public static function remove_locations_banner($bid)
   {
       $obj = new fittizen_banner_locations(-1);
       return $obj->delete('banner_id', $bid);
   }
}

