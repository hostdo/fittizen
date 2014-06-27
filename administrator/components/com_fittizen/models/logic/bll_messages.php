<?php
/**
 * Logic layer object of D.B. table fittizen_messages this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_messages extends fittizen_messages
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
     * @return bll_messages dbobject or false on failure.
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
     * @return bll_messages dbobject or false on failure.
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
     * @return boolean|bll_messages Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_messages not false on success.
     */
    public function insert() 
    {
        $this->created_date = AuxTools::DateTimeCurrentString();
        return parent::insert();
    }
    
    /**
     * Updates the object to the database
     * 
     * @return bll_messages not false on success. 
     */
    public function update()
    {
        return parent::update();
    }
    
    /**
     * Gets all the messages from conversation 
     * 
     * @param int $receiver_id id of fitinfo profile id from reciever
     * @param int $sender_id   id of fitinfo profile id from sender
     * 
     * @return bll_messages dbobject or false on failure.
     */
    public static function getConversation($receiver_id, $sender_id)
    {
        $msg = new bll_messages(-1);
        return $msg->findAll(array(
                                  array('receiver_id','='),
                                  array('sender_id','=')
                                   ), 
                              array(
                                  array($receiver_id,null),
                                  array($sender_id,'AND')
                                  ),
                              true,
                              'created_date'
                    );
    }
    
    /**
     * Gets all the messages recieved from profile
     * 
     * @param int $receiver_id id of fitinfo profile id from reciever
     * 
     * @return bll_messages dbobject or false on failure.
     */
    public static function getRecievedMessages($receiver_id)
    {
        $msg = new bll_messages(-1);
        return $msg->findAll(array(
                                  array('receiver_id','=')
                                   ), 
                              array(
                                  array($receiver_id,null)
                                  ),
                              true,
                              'created_date'
                    );
    }
    
    /**
     * Gets all the messages send by a profile
     * 
     * @param int $sender_id id of fitinfo profile id from sender
     * 
     * @return bll_messages dbobject or false on failure.
     */
    public static function getSendMessages($sender_id)
    {
        $msg = new bll_messages(-1);
        return $msg->findAll(array(
                                  array('sender_id','=')
                                   ), 
                              array(
                                  array($sender_id,null)
                                  ),
                              true,
                              'created_date'
                    );
    }
    
}

