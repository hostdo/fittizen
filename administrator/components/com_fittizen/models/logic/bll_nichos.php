<?php
/**
 * Logic layer object of D.B. table fittizen_nichos this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_nichos extends fittizen_nichos
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
     * @return bll_nichos dbobject or false on failure.
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
     * @return bll_nichos dbobject or false on failure.
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
     * @return boolean|bll_nichos Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_nichos not false on success.
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
     * @return bll_nichos not false on success. 
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
        $ofl=new fittizen_nichos_lang(0);
        $ofl->delete('nicho_id',$id);
        //adding lang values
        foreach($langs as $lang)
        {
            $lang_suffix = "_".$lang->lang_id;
            $lv = new fittizen_nichos_lang(0);
            $lv->nicho_id = $oid;
            $lv->description = filter_input(INPUT_POST, 'description'.$lang_suffix);
            $lv->name = filter_input(INPUT_POST, 'name'.$lang_suffix);
            $lv->url = filter_input(INPUT_POST, 'url'.$lang_suffix);
            $lv->image = filter_input(INPUT_POST, 'image'.$lang_suffix);
            $lv->lang_id=$lang->lang_id;
            $lv = $lv->insert();
        }
    }
    
    /**
     * Get the language value
     * @param type $lang_id id of the language
     * @return fittizen_nichos_lang catalogcategorylang value object.
     */
    public function getLanguageValue($lang_id)
    {
        $language = new languages($lang_id);
        if($language->lang_id <= 0)
        {
            return new fittizen_nichos_lang(-1);
        }
        $langval  = new fittizen_nichos_lang(-1);
        return $langval->find(
                              array(
                                  array('nicho_id','='),
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
     * @return fittizen_nichos_lang array of catalogcategorylang value object.
     */
    public function getLanguageValues()
    {
        $langval  = new fittizen_nichos_lang(-1);
        return $langval->findAll(
                              array(
                                  array('nicho_id','=')
                                   ), 
                              array(
                                  array($this->id,null)
                                  )
                             );
    }
    
    /**
     * Gets all the fitinfo related to the nicho
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
        $bl = new fittizen_fitinfo_nichos(-1);
        return self::convertListToArray(
                $bl->findAll('nicho_id', $this->id, 
                        $DESC, $order_field, 
                        $lower_limit, $upper_limit),
                'fitinfo_id');
    }
    
    /**
     * Gets all the language values that are incomplete
     * @param  boolean $DESC false if ascendent
     * @param  string  $order_field Field for the order by
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @return bll_nichos array of nichos
     */
    public function checkIncomplete($DESC = true, $order_field = "id", 
            $lower_limit = null, $upper_limit = null)
    {
        $lval = new fittizen_nichos_lang(-1);
        $main_table = $this->getTableName();
        $objname = $this->getObjectName();
        $lval_table = $lval->getTableName();
        $db = $this->getProvider($this->getDebug());
        $foreing_key = "nicho_id";
        $order_dir = 'ASC';
        if($DESC === true)
        {
            $order_dir = "DESC";
        }
        $limit = "";
        $order_by = $db->escape_string($order_field);
        if($lower_limit !== null && $upper_limit !== null)
        {
            $lower_limit = $db->escape_string($lower_limit);
            $upper_limit = $db->escape_string($upper_limit);
            $limit = " LIMIT $lower_limit,$upper_limit";
        }
        $query = "SELECT D.id FROM `#__$main_table` as D INNER JOIN"
               . " `#__$lval_table` as DL ON D.id = DL.$foreing_key "
                . "where (DL.name = '' OR DL.Description='') OR "
                . "0 NOT IN (select COUNT(lang_id) from `#__languages` where lang_id NOT IN "
                . "(SELECT lang_id FROM `#__$lval_table` where $foreing_key = D.id) )"
                . " ORDER BY `$order_by` $order_dir $limit";
        $db->Query($query);
        $res = array();
        $objlist = $db->getNextObjectList();
        foreach($objlist as $obj)
        {
            $res[]=new $objname($obj->id);
        }
        return $res;
    }
    
    /**
    * Adds a nicho to a content
    * @param integer $cid content_id
    * @param integer $nid nicho id
    * @return fittizen_content_nichos not false on success
    */
   public static function add_nicho_content($cid, $nid)
   {
       $obj = new fittizen_content_nichos(-1);
       $obj->content_id = $cid;
       $obj->nicho_id = $nid;
       return $obj->insert();
   }
   
   /**
    * Searchs the nichos from a content
    * @param integer $cid content_id 
    * @return fittizen_content_nichos array of dbobject not false on success
    */
   public static function get_nichos_content($cid)
   {
       $obj = new fittizen_content_nichos(-1);
       return $obj->findAll('content_id', $cid);
   }
   
   /**
    * Delete the nichos from a content
    * @param integer $cid content_id 
    * @return fittizen_content_nichos not false on success
    */
   public static function remove_nichos_content($cid)
   {
       $obj = new fittizen_content_nichos(-1);
       return $obj->delete('content_id', $cid);
   }
   
   /**
     * Find a nicho in the database
     * @param string $needle string with the needle to find
     * 
     * @return fittizen_nichos_lang  dbobject or false on failure.
     */
    public static function find_nicho($needle)
    {
        $nicho = new fittizen_nichos_lang(-1);
        $lang_id = AuxTools::GetCurrentLanguageIDJoomla();
        $field=array(
                        array('name', '='),
                        array('lang_id', '=')
                    );
        $value=array(
                    array($needle, null),
                    array($lang_id, 'AND')
                );
        return $nicho->find(
                    $field, 
                    $value
                    );
    }
}

