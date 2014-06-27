<?php
/**
 * Logic layer object of D.B. table fittizen_fittizen this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_fittizens extends fittizen_fittizen
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
     * @return bll_fittizens dbobject or false on failure.
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
     * @return bll_fittizens dbobject or false on failure.
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
     * @return boolean|bll_fittizens Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_fittizens not false on success.
     */
    public function insert() 
    {
        return parent::insert();
    }
    
    /**
     * Updates the object to the database
     * 
     * @return bll_fittizens not false on success. 
     */
    public function update()
    {
        return parent::update();
    }
    
    /**
     * Creates a new trainer
     * @param int $fitinfo_id profile id information.
     *  
     * @return bll_fittizens not false on success.
     */
    public static function create($fitinfo_id)
    {
        $obj  = new bll_fittizens(-1);
        $obj->fitinfo_id = $fitinfo_id; 
        return $obj->insert();
    }
    
    /**
     * Adds a new trainer
     * 
     * @param int $trainer_id
     * @param array $nichos
     * 
     * @return boolean true if the trainer is set
     */
    public function set_trainer($trainer_id, $nichos)
    {
        $fittizen_trainer = new fittizen_fittizen_trainers(-1);
        $fittizen_trainer->trainer_id=$trainer_id;
        $fittizen_trainer->created_date = AuxTools::DateTimeCurrentString();
        $fittizen_trainer->accepted =0;
        $fittizen_trainer->fittizen_id = $this->id;
        if($fittizen_trainer->insert() !== false)
        {
            foreach($nichos as $nicho_id)
            {
                $fit_trainer_nicho = new fittizen_fittizen_trainers_nichos(-1);
                $fit_trainer_nicho->nicho_id = $nicho_id;
                $fit_trainer_nicho->fittizen_trainers_id = $fittizen_trainer->id;
            }
            return true;
        }
        return false;
    }
    
    /**
     * Gets all the trainers from the fittizen
     * @return bll_trainers array of trainers
     */
    public function get_trainers()
    {
        $objs = new fittizen_fittizen_trainers(-1);
        $result=$objs->findAll('fittizen_id', $this->id);
        $arr=array();
        foreach($result as $r)
        {
            $obj= new bll_trainers($r->trainer_id);
            if($obj->id > 0)
            {
                $obj->addAttribute($r->getObjectName(), $r);
                $arr[]=$obj;
            }
        }
        return $arr;
    }
    
    /**
     * Gets all the trainers from the fittizen
     * @return array list of trainer_id of this profile
     */
    public function get_trainers_id()
    {
        $nichos = new fittizen_fittizen_trainers(-1);
        $result=$nichos->findAll('fittizen_id', $this->id);
        return dbobject::convertListToArray($result, "trainer_id");
    }
    
    /**
     * remove the trainer from the trainer list
     * 
     * @param int $fittizen_trainer_id id of the request
     * @return boolean|fittizen_fittizen_trainers  not falce on success
     */
    public function remove_trainer($fittizen_trainer_id)
    {
        $fittizen_trainer = new fittizen_fittizen_trainers($fittizen_trainer_id);
        return $fittizen_trainer->delete();
    }
    
}

