<?php
/**
 * Logic layer object of D.B. table fittizen_gender this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_gender extends fittizen_gender
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
     * @return bll_gender dbobject or false on failure.
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
     * @return bll_gender dbobject or false on failure.
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
     * @return boolean|bll_gender Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_gender not false on success.
     */
    public function insert() 
    {
        $this->setAttributes(filter_input_array(INPUT_POST, FILTER_DEFAULT));
        $obj= parent::insert();
        if($obj !== false)
        {
            $oid = $obj->id;
            //adding lang values
            $this->addLangValue($oid);
            return $this;
        }
        return false;
    }
    
    /**
     * Updates the object to the database
     * 
     * @return bll_gender not false on success. 
     */
    public function update()
    {
        $this->setAttributes(filter_input_array(INPUT_POST, FILTER_DEFAULT));
        $obj= parent::update();
        if($obj !== false)
        {
            $oid = $obj->id;
            //adding lang values
            $this->addLangValue($oid);
            return $this;
        }
        return false;
    }
    
    /**
     * Add the language values
     * @param int $id id of the main object 
     */
    private function addLangValue($id)
    {
        $oid = $id;
        $langs = languages::GetLanguages();
        //deleting old values
        $ofl=new fittizen_gender_lang(0);
        $ofl->delete('gender_id',$id);
        //adding lang values
        foreach($langs as $lang)
        {
            $lang_suffix = "_".$lang->lang_id;
            $lv = new fittizen_gender_lang(0);
            $lv->gender_id = $oid;
            $lv->name = filter_input(INPUT_POST, 'name'.$lang_suffix);
            $lv->lang_id=$lang->lang_id;
            $lv = $lv->insert();
        }
    }
    
    /**
     * Get the language value
     * @param type $lang_id id of the language
     * @return fittizen_gender_lang catalogcategorylang value object.
     */
    public function getLanguageValue($lang_id)
    {
        $language = new languages($lang_id);
        if($language->lang_id <= 0)
        {
            return new fittizen_gender_lang(-1);
        }
        $langval  = new fittizen_gender_lang(-1);
        return $langval->find(
                              array(
                                  array('gender_id','='),
                                  array('lang_id','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array($lang_id,'AND')
                                  )
                             );
    }
    
    /**
     * Get the language value
     * @param type $LangId id of the language
     * @return fittizen_gender_lang array of catalogcategorylang value object.
     */
    public function getLanguageValues()
    {
        $langval  = new fittizen_gender_lang(-1);
        return $langval->findAll(
                              array(
                                  array('gender_id','=')
                                   ), 
                              array(
                                  array($this->id,null)
                                  )
                             );
    }
    
    /**
     * Gets all the fitinfo related to the gender
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
        $bl = new bll_fitinfos(-1);
        return self::convertListToArray(
                $bl->findAll('gender_id', $this->id, 
                        $DESC, $order_field, 
                        $lower_limit, $upper_limit),
                'fitinfo_id');
    }
    
    /**
     * find the gender by its name
     * @param string $name
     * @return bll_gender gender found
     */
    public static function find_gender($name)
    {
        $lval = new fittizen_gender_lang(-1);
        return new bll_gender($lval->find('name', $name)->gender_id);
    }
}

