<?php
/**
 * Logic layer object of D.B. table fittizen_trainers this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_trainers extends fittizen_trainers
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
     * @return bll_trainers dbobject or false on failure.
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
     * @return bll_trainers dbobject or false on failure.
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
     * @return boolean|bll_trainers Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_trainers not false on success.
     */
    public function insert() 
    {
        return parent::insert();
    }
    
    /**
     * Updates the object to the database
     * 
     * @return bll_trainers not false on success. 
     */
    public function update()
    {
        return parent::update();
    }
    
    /**
     * Creates a new trainer
     * @param int $fitinfo_id profile id information.
     *  
     * @return bll_trainers not false on success.
     */
    public static function create($fitinfo_id)
    {
        $obj  = new bll_trainers(-1);
        $obj->fitinfo_id = $fitinfo_id; 
        return $obj->insert();
    }
    
    /**
     * Gets all the fittizens from the trainer
     * @return bll_fittizens array of fittizens
     */
    public function get_fittizens()
    {
        $objs = new fittizen_fittizen_trainers(-1);
        $result=$objs->findAll('trainer_id', $this->id);
        $arr=array();
        foreach($result as $r)
        {
            $obj= new bll_fittizens($r->fittizen_id);
            if($obj->id > 0)
            {
                $obj->addAttribute($r->getObjectName(), $r);
                $arr[]=$obj;
            }
        }
        return $arr;
    }
    
    /**
     * Gets all the fittizens from the trainer
     * @return array list of fittizen_id of this trainer
     */
    public function get_fittizens_id()
    {
        $nichos = new fittizen_fittizen_trainers(-1);
        $result=$nichos->findAll('trainer_id', $this->id);
        return dbobject::convertListToArray($result, "fittizen_id");
    }
    
    /**
     * accept the request from a fittizen
     * 
     * @param int $fittizen_trainer_id id of the request
     * 
     * @return boolean|fittizen_fittizen_trainers not falce on success
     */
    public function accept_fittizen($fittizen_trainer_id)
    {
        $fittizen_trainer = new fittizen_fittizen_trainers($fittizen_trainer_id);
        $fittizen_trainer->accepted=1;
        return $fittizen_trainer->update();
    }
    
    /**
     * remove the fittizen from the fittizen list
     * 
     * @param int $fittizen_trainer_id id of the request
     * @return boolean|fittizen_fittizen_trainers  not falce on success
     */
    public function remove_fittizen($fittizen_trainer_id)
    {
        $fittizen_trainer = new fittizen_fittizen_trainers($fittizen_trainer_id);
        return $fittizen_trainer->delete();
    }
    
    /**
     * Gets the rate of the trainer
     * @return double rating of the trainer
     */
    public function get_rating()
    {
        $obj = new fittizen_fittizen_trainers(-1);
        $fittizens = $obj->findAll('trainer_id', $this->id);
        $rate = 0.0;
        $count=0;
        $acum=0.0;
        foreach($fittizens as $fittizen)
        {
            if($fittizen->rate > 0)
            {
               $acum+=$fittizen->rate;
               $count++;
            }
        }
        return $acum/$count;
    }
    
}

