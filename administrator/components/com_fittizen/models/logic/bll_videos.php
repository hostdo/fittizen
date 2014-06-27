<?php
/**
 * Logic layer object of D.B. table fittizen_videos this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_videos extends fittizen_videos
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
     * @return bll_videos dbobject or false on failure.
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
     * @return bll_videos dbobject or false on failure.
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
     * @return boolean|bll_videos Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_videos not false on success.
     */
    public function insert() 
    {
        $this->created_date = AuxTools::DateTimeCurrentString();
        return parent::insert();
    }
    
    /**
     * Updates the object to the database
     * 
     * @return bll_videos not false on success. 
     */
    public function update()
    {
        return parent::update();
    }
    
    /**
     * Gets all the videos from a fitinfo profile
     * 
     * @param int $fitinfo_id id of fitinfo profile
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * 
     * @return bll_videos dbobject or false on failure.
     */
    public static function getVideos($fitinfo_id, $DESC=true, 
            $order_field='created_date', $lower_limit=null, $upper_limit=null)
    {
        $video = new bll_videos(-1);
        return $video->findAll('fitinfo_id', $fitinfo_id, 
                $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Gets all the comments from a video
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @return fittizen_comment_video array of comments
     */
    public function get_comments($DESC=true, $order_field='created_date',
            $lower_limit=null, $upper_limit=null)
    {
        return bll_fitinfos::get_comments(bll_fitinfos_constants::VIDEO,
        bll_fitinfos_constants::VIDEO_ID, $this->id,
        $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Gets all the tags from a video
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @return fittizen_videos_tags array of comments
     */
    public function get_tags($DESC=true, $order_field='created_date',
            $lower_limit=null, $upper_limit=null)
    {
        return bll_fitinfos::get_tags(bll_fitinfos_constants::VIDEOS, 
                bll_fitinfos_constants::VIDEO_ID, $this->id,
        $DESC, $order_field, $lower_limit, $upper_limit );
    }
    
    /**
     * Remove a tag of a video
     * @param int $id id of the comment to remove
     * 
     * @return boolean|dbobject Not false on success.
     */
    public function remove_tag($id)
    {
        return bll_fitinfos::remove_tag(bll_fitinfos_constants::VIDEOS, $id);
    }
    
    /**
     * Remove a comment of a video
     * @param int $id id of the comment to remove
     * 
     * @return boolean|dbobject Not false on success.
     */
    public function remove_comment($id)
    {
        return bll_fitinfos::remove_comment(bll_fitinfos_constants::VIDEO, $id);
    }
    
    /**
     * Get a comment from the video
     * @param int $id id of the comment to get
     * 
     * @return fittizen_comment_video the comment
     */
    public function get_comment($id)
    {
        return bll_fitinfos::get_comment(bll_fitinfos_constants::VIDEO, $id);
    }
    
    /**
     * Get the mentions of the comment
     * @param int $comment_id id of the comment
     * 
     * @return fittizen_comment_videos_mentions array of mentions
     */
    public function get_comment_mentions($comment_id)
    {
        return bll_fitinfos::get_mentions(bll_fitinfos_constants::COMMENT_VIDEOS, 
                bll_fitinfos_constants::VIDEO_ID, $comment_id);
    }
    
}

