<?php
/**
 * Logic layer object of D.B. table fittizen_timeline_images this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_timeline_images extends fittizen_timeline_images
{
   /**
    * Construct the object and initialize its values.
    *
    * @param int $id id of the entity to initialize 
    *
    */
    public function __construct($id)
    {
        parent::__construct($id);
        
    }
    
    /**
     * Selects one object from the table depending on which
     * attribute you are looking for.
     *
     * @param string|array $field name of the field to search for delete.
     * when $field is an array. field array(array(fieldname , OP)) when value is
     * the statement field[i] of the value value[i] and OP are 
     * the following operators:
     * Op(=, !=, <>).
     * @param string|array $value value of the field to search for delete.
     * when $value is an array. value array(array(val1 , Glue)) when value is
     * the value[i] of the statement field[i] and GLue are logic operators:
     * Logic(AND, OR).
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * 
     * @return bll_timeline_images dbobject or false on failure.
     */
    public function find($field = "", $value = "", $DESC = true, $order_field = "", $lower_limit = null, $upper_limit = null) 
    {
        return parent::find($field, $value, $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Selects one object from the table depending on which
     * attribute you are looking for.
     *
     * @param string|array $field name of the field to search for delete.
     * when $field is an array. field array(array(fieldname , OP)) when value is
     * the statement field[i] of the value value[i] and OP are 
     * the following operators:
     * Op(=, !=, <>).
     * @param string|array $value value of the field to search for delete.
     * when $value is an array. value array(array(val1 , Glue)) when value is
     * the value[i] of the statement field[i] and GLue are logic operators:
     * Logic(AND, OR).
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * 
     * @return bll_timeline_images dbobject or false on failure.
     */
    public function findAll($field = "", $value = "", $DESC = true, $order_field = "", $lower_limit = null, $upper_limit = null) 
    {
        return parent::findAll($field, $value, $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Delete the object instance in the database
     *
     * @param string|array $field name of the field to search for delete.
     * when $field is an array. field array(array(fieldname , OP)) when value is
     * the statement field[i] of the value value[i] and OP are 
     * the following operators:
     * Op(=, !=, <>).
     * @param string|array $value value of the field to search for delete.
     * when $value is an array. value array(array(val1 , Glue)) when value is
     * the value[i] of the statement field[i] and GLue are logic operators:
     * Logic(AND, OR).
     *
     * @warning if the funtion is used without parameters
     * there`s only a successful delete if the object
     * Id is found in the database.
     *
     * @return boolean|bll_timeline_images Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_timeline_images not false on success.
     */
    public function insert() 
    {
        $this->created_date = AuxTools::DateTimeCurrentString();
        return parent::insert();
    }
    
    /**
     * Updates the object to the database
     * 
     * @return bll_timeline_images not false on success. 
     */
    public function update()
    {
        return parent::update();
    }
    
    /**
     * Gets fitinfo Id from the timeline Image
     * 
     * @return int fitinfo_id
     */
    public function getFitInfoId()
    {
        $timeline = new bll_timeline($this->timeline_id);
        return $timeline->fitinfo_id;
    }
    
    /**
     * Sets the main image
     * @param int $img_id id of the image to set as main
     * @param int $x1     x1 relative position of the area of image
     * @param int $x2     x2 relative position of the area of image
     * @param int $y1     y1 relative position of the area of image
     * @param int $y2     y2 relative position of the area of image
     * 
     * @return bll_timeline_images not false on success.
     */
    public static function setMainImage($img_id, $x1, $x2, $y1, $y2)
    {
        $image = new bll_timeline_images($img_id);
        self::unsetMainImage($image->fitinfo_id);
        $image->main = 1;
        $image->x1=$x1;
        $image->x2=$x2;
        $image->y1=$y1;
        $image->y2=$y2;
        return $image->update();
    }
    
    /**
     *
     * Sets the main panoramic image
     * 
     * @param int $img_id id of the image to set as main
     * @param int $x1     x1 relative position of the area of image
     * @param int $x2     x2 relative position of the area of image
     * @param int $y1     y1 relative position of the area of image
     * @param int $y2     y2 relative position of the area of image
     * 
     * @return bll_timeline_images not false on success.
     */
    public static function setParonamicMainImage($img_id, $x1, $x2, $y1, $y2)
    {
        $image = new bll_timeline_images($img_id);
        self::unsetPanoramicMainImage($image->fitinfo_id);
        $image->panoramic_main=1;
        $image->px1=$x1;
        $image->px2=$x2;
        $image->py1=$y1;
        $image->py2=$y2;
        $image->update();
    }
    
    /**
     * Unsets the main image
     * @param int $fitinfo_id id of the image to unset as main
     *
     * @return boolean true on success
     */
    public static function unsetMainImage($fitinfo_id)
    {
        $db = $this->getProvider($this->getDebug());
        $tname = $this->getTableName();
        $query="UPDATE `$tname` SET `main`='',"
             . "`x1`='',`x2`='',`y1`='',`y2`='' "
             . "WHERE timeline_id IN (SELECT id FROM `fittizen_timeline` WHERE fitinfo_id = '$fitinfo_id')";
        return $db->Query($query);
    }
    
    /**
     * Unsets the main panoramic image
     * @param int $fitinfo_id id of the image to unset as main
     *
     * @return boolean true on success
     */
    public static function unsetPanoramicMainImage($fitinfo_id)
    {
        $db = $this->getProvider($this->getDebug());
        $tname = $this->getTableName();
        $query="UPDATE `$tname` SET `panoramic_main`='',"
             . "`px1`='',`px2`='',`py1`='',`py2`='' "
             . "WHERE timeline_id IN (SELECT id FROM `fittizen_timeline` WHERE fitinfo_id = '$fitinfo_id')";
        return $db->Query($query);
    }
    
    /**
     * Gets all the timeline_images from a timeline
     * 
     * @param int $timeline_id id of timeline
     * @param  boolean $DESC false if ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @return bll_timeline_images dbobject or false on failure.
     */
    public static function getImages($timeline_id, $DESC=true, 
            $order_field='created_date', $lower_limit=null, $upper_limit=null)
    {
        $image = new bll_timeline_images(-1);
        return $image->findAll('timeline_id', $timeline_id, 
                $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Gets all the comments from a timeline image
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @return fittizen_comment_timeline_image array of comments
     */
    public function get_comments($DESC=true, $order_field='created_date',
            $lower_limit=null, $upper_limit=null)
    {
        return bll_fitinfos::get_comments(bll_fitinfos_constants::TIMELINE_IMAGE,
        bll_fitinfos_constants::TIMELINE_IMAGE_ID, $this->id,
        $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Gets all the tags from a timeline image
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @return fittizen_timeline_images_tags array of comments
     */
    public function get_tags($DESC=true, $order_field='created_date',
            $lower_limit=null, $upper_limit=null)
    {
        return bll_fitinfos::get_tags(bll_fitinfos_constants::TIMELINE_IMAGES, 
                bll_fitinfos_constants::TIMELINE_IMAGE_ID, $this->id,
        $DESC, $order_field, $lower_limit, $upper_limit );
    }
    
    /**
     * Remove a tag of a timeline image
     * @param int $id id of the comment to remove
     * 
     * @return boolean|dbobject Not false on success.
     */
    public function remove_tag($id)
    {
        return bll_fitinfos::remove_tag(bll_fitinfos_constants::TIMELINE_IMAGES, $id);
    }
    
    /**
     * Remove a comment of a timeline image
     * @param int $id id of the comment to remove
     * 
     * @return boolean|dbobject Not false on success.
     */
    public function remove_comment($id)
    {
        return bll_fitinfos::remove_comment(bll_fitinfos_constants::TIMELINE_IMAGE, $id);
    }
    
    /**
     * Get a comment from the timeline image
     * @param int $id id of the comment to get
     * 
     * @return fittizen_comment_timeline_image the comment
     */
    public function get_comment($id)
    {
        return bll_fitinfos::get_comment(bll_fitinfos_constants::TIMELINE_IMAGE, $id);
    }
    
    /**
     * Get the mentions of the comment
     * @param int $comment_id id of the comment
     * 
     * @return fittizen_comment_timeline_images_mentions array of mentions
     */
    public function get_comment_mentions($comment_id)
    {
        return bll_fitinfos::get_mentions(bll_fitinfos_constants::COMMENT_TIMELINE_IMAGES, 
                bll_fitinfos_constants::TIMELINE_IMAGE_ID, $comment_id);
    }
    
}

