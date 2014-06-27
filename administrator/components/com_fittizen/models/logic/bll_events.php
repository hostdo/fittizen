<?php
/**
 * Logic layer object of D.B. table fittizen_events this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_events extends fittizen_events
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
     * @return bll_events dbobject or false on failure.
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
     * @return bll_events dbobject or false on failure.
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
     * @return boolean|bll_events Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_events not false on success.
     */
    public function insert($location_id=null, $fitinfo_id=null) 
    {
        $this->location_id=$location_id;
        $this->fitinfo_id=$fitinfo_id;
        $this->created_date = AuxTools::DateTimeCurrentString();
        $this->setAttributes(filter_input_array(INPUT_POST));
        return parent::insert();
    }
    
    /**
     * Updates the object to the database
     * 
     * @return bll_events not false on success. 
     */
    public function update()
    {
        $this->setAttributes(filter_input_array(INPUT_POST));
        return parent::update();
    }
    
    /**
     * Gets all the fitinfo related to the events
     * @param  boolean $DESC false if ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * 
     * @return array array of fitinfos id
     */
    public function getFitInfos($DESC = true, $order_field = "created_date", 
            $lower_limit = null, $upper_limit = null)
    {
        $bl = new fittizen_events_attendance(-1);
        return self::convertListToArray(
                $bl->findAll('event_id', $this->id, 
                        $DESC, $order_field, 
                        $lower_limit, $upper_limit),
                'fitinfo_id');
    }
    
    /**
     * Invites fitinfos in the event.
     * 
     * @param array $fitinfos_id array of fitinfos id to invite
     */
    public function invite($fitinfos_id=array())
    {
        $dt1 = AuxTools::DateTimeGenerate($this->init_date);
        $dt2 = AuxTools::DateTimeGenerate($this->end_date);
        $current = AuxTools::DateTimeCurrent();
        if($current >= $dt1 && $current<=$dt2)
        {
            foreach($fitinfos_id as $fitinfo_id)
            {
                $obj = new fittizen_events_attendance(-1);
                $obj->event_id=$this->id;
                $obj->fitinfo_id=$fitinfo_id;
                $obj->going='';
                $obj->invite_date = AuxTools::DateTimeCurrentString();
                $obj->insert();
            }
        }
    }
    
    /**
     * Get the attendance for a event
     * @param  integer $event_id id of the event
     * @param  integer $going 1 if the profile is going, 0 if not, empty string maybe
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @return array array of fitinfo_ids
     */
    public static function get_attendance_by($event_id,$going,
            $DESC = true, $order_field = "invite_date", 
            $lower_limit = null, $upper_limit = null)
    {
        $event_attendace=new fittizen_events_attendance(-1);
        $field = array(
            array('event_id', '='),
            array('going', '=')
        );
        $value=array(
            array($event_id, 'AND'),
            array($going, 'AND')
        );
        return self::convertListToArray(
                $event_attendace->findAll($field, $value,
                $DESC, $order_field, $lower_limit, $upper_limit),
                'fitinfo_id');
    }
    
    /**
     * Respond the event invitation
     * 
     * @param int $event_id id of the event
     * @param int $fitinfo_id profile id that is going to the event
     * @param int $going 1 if the profile is going, 0 if not, empty string maybe
     * @return boolean|fittizen_events_attendance not false on success
     */
    public static function respond_invite($event_id, $fitinfo_id, $going=1)
    {
        $event_attendace=new fittizen_events_attendance(-1);
        $field = array(
            array('event_id', '='),
            array('fitinfo_id', '=')
        );
        $value=array(
            array($event_id, 'AND'),
            array($fitinfo_id, 'AND')
        );
        $eval=$event_attendace->find($field, $value);
        if($eval !== false)
        {
            $eval->going=$going;
            if($eval->response_date=="")
            {
                $eval->response_date = AuxTools::DateTimeCurrentString();
            }
            return $eval->update();
        }
    }
}

