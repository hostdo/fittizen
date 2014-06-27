<?php
/**
 * Logic layer object of D.B. table fittizen_post this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_post extends fittizen_post
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
     * @return bll_post dbobject or false on failure.
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
     * @return bll_post dbobject or false on failure.
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
     * @return boolean|bll_post Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_post not false on success.
     */
    public function insert() 
    {
        $this->created_date = AuxTools::DateTimeCurrentString();
        return parent::insert();
    }
    
    /**
     * Updates the object to the database
     * 
     * @return bll_post not false on success. 
     */
    public function update()
    {
        return parent::update();
    }
    
    /**
     * Gets all the post from a fitinfo profile
     * 
     * @param int $fitinfo_id id of fitinfo profile
     * 
     * @return bll_post dbobject or false on failure.
     */
    public static function getPosts($fitinfo_id)
    {
        $post = new bll_post(-1);
        return $post->findAll('fitinfo_id', $fitinfo_id, 
                true, 'created_date');
    }
    
    /**
     * Get the mentions of the post
     * 
     * @return fittizen_posts_mentions array of mentions
     */
    public function get_mentions()
    {
        return bll_fitinfos::get_mentions(bll_fitinfos_constants::POSTS, 
                bll_fitinfos_constants::POST_ID, $this->id);
    }
    
    /**
     * Gets all the comments from a post
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @return fittizen_comment_post array of comments
     */
    public function get_comments($DESC=true, $order_field='created_date',
            $lower_limit=null, $upper_limit=null)
    {
        return bll_fitinfos::get_comments(bll_fitinfos_constants::POST,
        bll_fitinfos_constants::POST_ID, $this->id,
        $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Remove a comment of a post
     * @param int $id id of the comment to remove
     * 
     * @return boolean|dbobject Not false on success.
     */
    public function remove_comment($id)
    {
        return bll_fitinfos::remove_comment(bll_fitinfos_constants::POST, $id);
    }
    
    /**
     * Get a comment from the post
     * @param int $id id of the comment to get
     * 
     * @return fittizen_comment_post the comment
     */
    public function get_comment($id)
    {
        return bll_fitinfos::get_comment(bll_fitinfos_constants::POST, $id);
    }
    
    /**
     * Get the mentions of the comment
     * @param int $comment_id id of the comment
     * 
     * @return fittizen_comment_posts_mentions array of mentions
     */
    public function get_comment_mentions($comment_id)
    {
        return bll_fitinfos::get_mentions(bll_fitinfos_constants::COMMENT_POSTS, 
                bll_fitinfos_constants::POST_ID, $comment_id);
    }
    
}

