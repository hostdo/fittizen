<?php
/**
 * Logic layer object of D.B. table fittizen_fitinfos this object
 * has the basic CRUD functions build-in, for
 * normalized databases tables.
 *
 * @author Gabriel Gonzalez Disla
 */
class bll_fitinfos extends fittizen_fitinfos
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
     * @return bll_fitinfos dbobject or false on failure.
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
     * @return bll_fitinfos dbobject or false on failure.
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
     * @return boolean|bll_fitinfos Not false on success.
     */
    public function delete($field = "", $value = "") 
    {
        return parent::delete($field, $value);
    }
    
    /**
     * Validates values before inserting or update
     */
    private function pre_validate_fields()
    {
        $gender= new bll_gender($this->gender_id);
        if($gender->id <= 0)
        {
            $this->gender_id = NULL;
        }
        $loc= new bll_locations($this->location_id);
        if($loc->id <= 0)
        {
            $this->location_id = NULL;
        }
    }
    
    /**
     * Insert the object to the database
     *
     * @return bll_fitinfos not false on success.
     */
    public function insert() 
    {
        $this->pre_validate_fields();
        return parent::insert();
    }
    
    /**
     * Updates the object to the database
     * 
     * @return bll_fitinfos not false on success. 
     */
    public function update()
    {
        $this->pre_validate_fields();
        return parent::update();
    }
    
    /**
     * String that generates a code
     * @return string generated code
     */
    public function generate_code()
    {
        if($this->name != "")
        {
            $codestr  = $this->name;
            if(strlen($codestr) > 35)
            {
                $codestr=substr($codestr, 0, 34);
            }
            if($this->last_name != "")
            {
                $codestr.='_'.$this->last_name[0];
            }
            $i=0;
            $tmp=$codestr;
            while(!bll_fitinfos::check_profile_code($tmp))
            {
                $i++;
                $tmp = $codestr.'_'.$i;
            }
            return $tmp;
        }
        return $codestr;
    }
    
    /**
     * Checks if the profile code exists
     * @param string $str profile code to evaluate
     * @return boolean true if the profile code is available
     */
    public static function check_profile_code($str)
    {
        $fitinfo = new bll_fitinfos(-1);
        $arr=$fitinfo->findAll('profile_code', $str);
        if($arr !== false && count($arr) > 0)
        {
            return false;
        }
        return true;
    }
    
    /**
     * Gets all the diets from the profile
     * @return bll_diets array of diets
     */
    public function get_diets()
    {
        $objs = new fittizen_fitinfo_diet(-1);
        $result=$objs->findAll('fitinfo_id', $this->id);
        $arr=array();
        foreach($result as $r)
        {
            $obj= new bll_diets($r->diet_id);
            if($obj->id > 0)
            {
                $obj->addAttribute($r->getObjectName(), $r);
                $arr[]=$obj;
            }
        }
        return $arr;
    }
    
    /**
     * Gets all the diets from the profile
     * @return array list of diets_id of this profile
     */
    public function get_diets_id()
    {
        $nichos = new fittizen_fitinfo_diet(-1);
        $result=$nichos->findAll('fitinfo_id', $this->id);
        return dbobject::convertListToArray($result, "diet_id");
    }
    
    /**
     * Remove a diet to the profile
     * 
     * @param int $diet_id id of the diet
     * @return boolean true if the diet was deleted,
     * false otherwise
     */
    public function remove_diet($diet_id)
    {
        $objs = $this->get_diets_id();
        if(array_search($diet_id, $objs) !== false)
        {
            $obj = new fittizen_fitinfo_diet(0);
            if($obj->delete(array(
                                  array('fitinfo_id','='),
                                  array('diet_id','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array($diet_id,'AND')
                                  )) !== false)
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Get the permissions from the profile
     * 
     * @return fittizen_fitinfo_permissions|boolean db object not false on success
     */
    public function get_permissions()
    {
        $obj = new fittizen_fitinfo_permissions(-1);
        return $obj->find('fitinfo_id', $this->id);
    }
    
    /**
     * Sets the permissions of the profile fitinfo
     * @param array $attrs hash of table attributes
     * 
     * @return fittizen_fitinfo_permissions|boolean db object not false on success
     */
    public function set_permissions($attrs)
    {
        $obj = new fittizen_fitinfo_permissions(-1);
        $obj->setAttributes($attrs);
        if($this->get_permissions()->id > 0)
        {
            return $obj->update();
        }
        return $obj->insert();
    }
    
    /**
     * Sets a new diet to the profile
     * 
     * @param int $diet_id id of the diet
     * @return boolean|fittizen_fitinfo_diet true if the diet is already added,
     * false if there was a problem adding the diet.
     */
    public function set_diet($diet_id)
    {
        $objs = $this->get_diets_id();
        if(array_search($diet_id, $objs) == false)
        {
            $new_obj = new fittizen_fitinfo_diet(0);
            $new_obj->diet_id = $diet_id;
            $new_obj->fitinfo_id = $this->id;
            $new_obj->created_date = AuxTools::DateTimeCurrentString();
            return $new_obj->insert();
        }
        return true;
    }
    
    /**
     * Gets all the nichos from the profile
     * @return bll_nichos array of nichos
     */
    public function get_nichos()
    {
        $nichos = new fittizen_fitinfo_nichos(-1);
        $result=$nichos->findAll('fitinfo_id', $this->id);
        $arr=array();
        foreach($result as $r)
        {
            $nicho= new bll_nichos($r->nicho_id);
            if($nicho->id > 0)
            {
                $nicho->addAttribute($r->getObjectName(), $r);
                $arr[]=$nicho;
            }
        }
        return $arr;
    }
    
    /**
     * Gets all the nichos from the profile
     * @return array list of nichos_id of this profile
     */
    public function get_nichos_id()
    {
        $nichos = new fittizen_fitinfo_nichos(-1);
        $result=$nichos->findAll('fitinfo_id', $this->id);
        return dbobject::convertListToArray($result, "nicho_id");
    }
    
    /**
     * Remove a nicho to the profile
     * 
     * @param int $nicho_id id of the nicho
     * @return boolean true if the nicho was deleted,
     * false otherwise
     */
    public function remove_nicho($nicho_id)
    {
        $nichos = $this->get_nichos_id();
        if(array_search($nicho_id, $nichos) !== false)
        {
            $nicho = new fittizen_fitinfo_nichos(0);
            if($nicho->delete(array(
                                  array('fitinfo_id','='),
                                  array('nicho_id','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array($nicho_id,'AND')
                                  )) !== false)
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Sets a new nicho to the profile
     * 
     * @param int $nicho_id id of the nicho
     * @return boolean|fittizen_fitinfo_nichos true if the nicho is already added,
     * false if there was a problem adding the nicho.
     */
    public function set_nicho($nicho_id)
    {
        $nichos = $this->get_nichos_id();
        if(array_search($nicho_id, $nichos) == false)
        {
            $new_nicho = new fittizen_fitinfo_nichos(0);
            $new_nicho->nicho_id = $nicho_id;
            $new_nicho->fitinfo_id = $this->id;
            $new_nicho->created_date = AuxTools::DateTimeCurrentString();
            return $new_nicho->insert();
        }
        return true;
    }
    
    /**
     * Gets all the supplements from the profile
     * @return bll_supplements array of supplements
     */
    public function get_supplements()
    {
        $objs = new fittizen_fitinfo_supplement(-1);
        $result=$objs->findAll('fitinfo_id', $this->id);
        $arr=array();
        foreach($result as $r)
        {
            $obj= new bll_supplements($r->supplement_id);
            if($obj->id > 0)
            {
                $obj->addAttribute($r->getObjectName(), $r);
                $arr[]=$obj;
            }
        }
        return $arr;
    }
    
    /**
     * Gets all the supplements from the profile
     * @return array list of supplements_id of this profile
     */
    public function get_supplements_id()
    {
        $nichos = new fittizen_fitinfo_supplement(-1);
        $result=$nichos->findAll('fitinfo_id', $this->id);
        return dbobject::convertListToArray($result, "supplement_id");
    }
    
    /**
     * Remove a supplement to the profile
     * 
     * @param int $supplement_id id of the supplement
     * @return boolean true if the supplement was deleted,
     * false otherwise
     */
    public function remove_supplement($supplement_id)
    {
        $objs = $this->get_supplements_id();
        if(array_search($supplement_id, $objs) !== false)
        {
            $obj = new fittizen_fitinfo_supplement(0);
            if($obj->delete(array(
                                  array('fitinfo_id','='),
                                  array('supplement_id','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array($supplement_id,'AND')
                                  )) !== false)
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Sets a new supplement to the profile
     * 
     * @param int $supplement_id id of the supplement
     * @return boolean|fittizen_fitinfo_supplement true if the supplement is already added,
     * false if there was a problem adding the supplement.
     */
    public function set_supplement($supplement_id)
    {
        $objs = $this->get_supplements_id();
        if(array_search($supplement_id, $objs) == false)
        {
            $new_obj = new fittizen_fitinfo_supplement(0);
            $new_obj->supplement_id = $supplement_id;
            $new_obj->fitinfo_id = $this->id;
            $new_obj->created_date = AuxTools::DateTimeCurrentString();
            return $new_obj->insert();
        }
        return true;
    }
    
    
    /**
     * Gets all the gyms from the profile
     * @return bll_gyms array of gyms
     */
    public function get_gyms()
    {
        $objs = new fittizen_fitinfo_gym(-1);
        $result=$objs->findAll('fitinfo_id', $this->id);
        $arr=array();
        foreach($result as $r)
        {
            $obj= new bll_gyms($r->gym_id);
            if($obj->id > 0)
            {
                $arr[]=$obj;
            }
        }
        return $arr;
    }
    
    /**
     * Gets all the gyms from the profile
     * @return array list of gyms_id of this profile
     */
    public function get_gyms_id()
    {
        $nichos = new fittizen_fitinfo_gym(-1);
        $result=$nichos->findAll('fitinfo_id', $this->id);
        return dbobject::convertListToArray($result, "gym_id");
    }
    
    /**
     * Remove a gym to the profile
     * 
     * @param int $gym_id id of the gym
     * @return boolean true if the gym was deleted,
     * false otherwise
     */
    public function remove_gym($gym_id)
    {
        $objs = $this->get_gyms_id();
        if(array_search($gym_id, $objs) !== false)
        {
            $obj = new fittizen_fitinfo_gym(0);
            if($obj->delete(array(
                                  array('fitinfo_id','='),
                                  array('gym_id','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array($gym_id,'AND')
                                  )) !== false)
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Sets a new gym to the profile
     * 
     * @param int $gym_id id of the gym
     * @return boolean|fittizen_fitinfo_gym true if the gym is already added,
     * false if there was a problem adding the gym.
     */
    public function set_gym($gym_id)
    {
        $objs = $this->get_gyms_id();
        if(array_search($gym_id, $objs) == false)
        {
            $new_obj = new fittizen_fitinfo_gym(0);
            $new_obj->gym_id = $gym_id;
            $new_obj->fitinfo_id = $this->id;
            $new_obj->created_date = AuxTools::DateTimeCurrentString();
            return $new_obj->insert();
        }
        return true;
    }
    
    
    /**
     * Gets all the goals from the profile
     * @return bll_goals array of goals
     */
    public function get_goals()
    {
        $objs = new fittizen_fitinfo_goal(-1);
        $result=$objs->findAll('fitinfo_id', $this->id);
        $arr=array();
        foreach($result as $r)
        {
            $obj= new bll_goals($r->goal_id);
            if($obj->id > 0)
            {
                $obj->addAttribute($r->getObjectName(), $r);
                $arr[]=$obj;
            }
        }
        return $arr;
    }
    
    /**
     * Gets all the goals from the profile
     * @return array list of goals_id of this profile
     */
    public function get_goals_id()
    {
        $nichos = new fittizen_fitinfo_goal(-1);
        $result=$nichos->findAll('fitinfo_id', $this->id);
        return dbobject::convertListToArray($result, "goal_id");
    }
    
    /**
     * Remove a goal to the profile
     * 
     * @param int $goal_id id of the goal
     * @return boolean true if the goal was deleted,
     * false otherwise
     */
    public function remove_goal($goal_id)
    {
        $objs = $this->get_goals_id();
        if(array_search($goal_id, $objs) !== false)
        {
            $obj = new fittizen_fitinfo_goal(0);
            if($obj->delete(array(
                                  array('fitinfo_id','='),
                                  array('goal_id','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array($goal_id,'AND')
                                  )) !== false)
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Sets a new goal to the profile
     * 
     * @param int $goal_id id of the goal
     * @param bool $achieved true if the goal is achieved.
     * @return boolean|fittizen_fitinfo_goal true if the goal is already added,
     * false if there was a problem adding the goal.
     */
    public function set_goal($goal_id, $achieved=false)
    {
        $objs = $this->get_goals_id();
        $achi_val=0;
        if($achieved)
        {
            $achi_val=1;
        }
        if(array_search($goal_id, $objs) == false)
        {
            $new_obj = new fittizen_fitinfo_goal(0);
            $new_obj->goal_id = $goal_id;
            $new_obj->fitinfo_id = $this->id;
            $new_obj->achieved = $achi_val;
            return $new_obj->insert();
        }
        else
        {
            $fitin_goal = new fittizen_fitinfo_goal(0);
            $fitinfo_goal = $fitin_goal->findAll(array(
                                  array('fitinfo_id','='),
                                  array('goal_id','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array($goal_id,'AND')
                                  ));
            $fitinfo_goal->achieved=$achi_val;
            return $fitin_goal->update();
        }
        return true;
    }
    
    /**
     * Gets all the endurance_exercises from the profile
     * @return bll_endurance_exercise array of endurance_exercises
     */
    public function get_endurance_exercises()
    {
        $objs = new fittizen_fitinfo_endurance_exercise(-1);
        $result=$objs->findAll('fitinfo_id', $this->id);
        $arr=array();
        foreach($result as $r)
        {
            $obj= new bll_endurance_exercise($r->endurance_exercise_id);
            if($obj->id > 0)
            {
                $obj->addAttribute($r->getObjectName(), $r);
                $arr[]=$obj;
            }
        }
        return $arr;
    }
    
    /**
     * Gets all the endurance_exercises from the profile
     * @return array list of endurance_exercise_id of this profile
     */
    public function get_endurance_exercises_id()
    {
        $nichos = new fittizen_fitinfo_endurance_exercise(-1);
        $result=$nichos->findAll('fitinfo_id', $this->id);
        return dbobject::convertListToArray($result, "endurance_exercise_id");
    }
    
    /**
     * Remove a endurance_exercise to the profile
     * 
     * @param int $endurance_exercise_id id of the endurance_exercise
     * @return boolean true if the endurance_exercise was deleted,
     * false otherwise
     */
    public function remove_endurance_exercise($endurance_exercise_id)
    {
        $objs = $this->get_endurance_exercises_id();
        if(array_search($endurance_exercise_id, $objs) !== false)
        {
            $obj = new fittizen_fitinfo_endurance_exercise(0);
            if($obj->delete(array(
                                  array('fitinfo_id','='),
                                  array('endurance_exercise_id','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array($endurance_exercise_id,'AND')
                                  )) !== false)
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Sets a new endurance_exercise to the profile
     * 
     * @param int $endurance_exercise_id id of the endurance_exercise
     * @param int $distance distance in meters
     * 
     * @return boolean|fittizen_fitinfo_endurance_exercise true if the endurance_exercise is already added,
     * false if there was a problem adding the endurance_exercise.
     */
    public function set_endurance_exercise($endurance_exercise_id, $distance=100)
    {
        $objs = $this->get_endurance_exercises_id();
        if(array_search($endurance_exercise_id, $objs) == false)
        {
            $new_obj = new fittizen_fitinfo_endurance_exercise(0);
            $new_obj->endurance_exercise_id = $endurance_exercise_id;
            $new_obj->fitinfo_id = $this->id;
            $new_obj->distance = $distance;
            $new_obj->created_date = AuxTools::DateTimeCurrentString();
            return $new_obj->insert();
        }
        return true;
    }
    
    /**
     * Gets all the routine from the profile
     * @return bll_routine array of routines
     */
    public function get_routines($get_history=false)
    {
        $objs = new fittizen_fitinfo_routine(-1);
        $result=$objs->findAll('fitinfo_id', $this->id);
        $arr=array();
        foreach($result as $r)
        {
            $obj= new bll_routine($r->routine_id);
            if($obj->id > 0)
            {
                $obj->addAttribute($r->getObjectName(), $r);
                $days=$this->get_routine_days($r->routine_id);
                $name="";
                $data=array();
                foreach($days as $day)
                {
                    if($get_history == true)
                    {
                        $day->addAttribute('history', $this->get_routine_history($r->routine_id, $day->day));
                    }
                    $data[]=$day;
                }
                if($name != "")
                {
                    $obj->addAttribute($name, $data);
                }
                $arr[]=$obj;
            }
        }
        return $arr;
    }
    
    /**
     * Gets all the routines from the profile
     * @return array list of routines_id of this profile
     */
    public function get_routines_id()
    {
        $objs = new fittizen_fitinfo_routine(-1);
        $result=$objs->findAll('fitinfo_id', $this->id);
        return dbobject::convertListToArray($result, "routine_id");
    }
    
    /**
     * Remove a routine to the profile
     * 
     * @param int $routine_id id of the routine
     * @return boolean true if the routine was deleted,
     * false otherwise
     */
    public function remove_routine($routine_id)
    {
        $objs = $this->get_routines_id();
        if(array_search($routine_id, $objs) !== false)
        {
            $obj = new fittizen_fitinfo_routine(0);
            if($obj->delete(array(
                                  array('fitinfo_id','='),
                                  array('routine_id','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array($routine_id,'AND')
                                  )) !== false)
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Sets a new routine to the profile
     * 
     * @param int   $routine_id id of the routine
     * @param int   $trainer_id id of the trainer, null if the routine was 
     * choose by the own profile user
     * @param int   $active     1 if the routine is the user is using it, 0 if not.
     * @param array $days array of days, 0 to 6 sunday to saturday.
     * 
     * @return boolean|fittizen_fitinfo_routine true if the routine is already added,
     * false if there was a problem adding the routine.
     */
    public function set_routine($routine_id, $trainer_id, $active, $days=array())
    {
        $objs = $this->get_routines_id();
        if(array_search($routine_id, $objs) == false)
        {
            $new_obj = new fittizen_fitinfo_routine(0);
            $new_obj->routine_id = $routine_id;
            $new_obj->fitinfo_id = $this->id;
            $new_obj->active = $active;
            $new_obj->trainer_id = $trainer_id;
            $new_obj->created_date = AuxTools::DateTimeCurrentString();
            $result= $new_obj->insert();
            foreach($days as $day)
            {
                if($day >= 0 && $day <= 6)
                {
                    $fitinfo_routine_days = new fittizen_fitinfo_routine_days(-1);
                    $fitinfo_routine_days->fitinfo_routine_id = $result->id;
                    $fitinfo_routine_days->day=$day;
                    $fitinfo_routine_days->insert();
                }
            }
        }
        else
        {
            $fitin_obj = new fittizen_fitinfo_routine(0);
            $fitinfo_obj = $fitin_obj->findAll(array(
                                  array('fitinfo_id','='),
                                  array('routine_id','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array($routine_id,'AND')
                                  ));
            $fitinfo_obj->active= $active;
            $fitinfo_obj->trainer_id=$trainer_id;
            $this->set_routine_days($routine_id, $days);
            return $fitinfo_obj->update();
        }
        return true;
    }
    
    /**
     * Gets all the days from a routine
     * @param int $routine_id routine_id to search their days
     * 
     * @return fittizen_fitinfo_routine_days array of routine days
     */
    public function get_routine_days($routine_id)
    {
        $objs = new fittizen_fitinfo_routine(-1);
        $result=$objs->findAll('fitinfo_id', $this->id);
        $routine=new fittizen_fitinfo_routine(-1);
        foreach($result as $obj)
        {
            if($obj->routine_id == $routine_id)
            {
                $routine=$obj;
            }
        }
        if($routine->id > 0)
        {
            $objday=new fittizen_fitinfo_routine_days(-1);
            return $objday->findAll('fitinfo_routine_id', $routine->id);
        }
        return array();
    }
    
    /**
     * Gets a specific day from a routine
     * @param int $routine_id routine_id to search their days
     * @param int $day int of days, 0 to 6 sunday to saturday.
     * 
     * @return fittizen_fitinfo_routine_days routine day
     */
    public function get_routine_day($routine_id, $day)
    {
        $days = $this->get_routine_days($routine_id);
        $ret=new fittizen_fitinfo_routine_days(-1);
        foreach($days as $_objday)
        {
           if($_objday->day == $day)
           {
               $ret= $_objday;
           }
        }
        return $ret;
    }
    
    
    /**
     * Sets all the days from a routine
     * @param int $routine_id routine_id to search their days
     * @param array $days array of days, 0 to 6 sunday to saturday.
     * 
     * @return true if the routine days was set successful
     */
    public function set_routine_days($routine_id, $days=array())
    {
        $objs = new fittizen_fitinfo_routine(-1);
        $result=$objs->findAll('fitinfo_id', $this->id);
        $routine=new fittizen_fitinfo_routine(-1);
        foreach($result as $obj)
        {
            if($obj->routine_id == $routine_id)
            {
                $routine=$obj;
            }
        }
        if($routine->id > 0)
        {
            $objday=new fittizen_fitinfo_routine_days(-1);
            $days_objs= $objday->findAll('fitinfo_routine_id', $routine->id);
            foreach($days as $day)
            {
                $insert=true;
                foreach($days_objs as $day_obj)
                {
                    if($day_obj->day == $day)
                    {
                        $insert=false;
                        break;
                    }
                }
                if($insert == true)
                {
                    $fitinfo_routine_days = new fittizen_fitinfo_routine_days(-1);
                    $fitinfo_routine_days->fitinfo_routine_id = $routine->id;
                    $fitinfo_routine_days->day=$day;
                    $fitinfo_routine_days->insert();
                }
            }
            return true;
        }
        return false;
    }
    
    /**
     * sets all the days from a routine
     * @param int $routine_id routine_id to search their days
     * @param array $days array of days, 0 to 6 sunday to saturday.
     * 
     * @return true if there was at least one routine days removed.
     */
    public function remove_routine_days($routine_id, $days=array())
    {
        $objs = new fittizen_fitinfo_routine(-1);
        $result=$objs->findAll('fitinfo_id', $this->id);
        $routine=new fittizen_fitinfo_routine(-1);
        foreach($result as $obj)
        {
            if($obj->routine_id == $routine_id)
            {
                $routine=$obj;
            }
        }
        if($routine->id > 0)
        {
            $objday=new fittizen_fitinfo_routine_days(-1);
            $days_objs= $objday->findAll('fitinfo_routine_id', $routine->id);
            $remove=false;
            foreach($days_objs as $day_obj)
            {
                if(array_search($day_obj->day, $days)!==false)
                {
                    $day_obj->delete();
                    $remove=true;
                    break;
                }
            }
            return $remove;
        }
        return false;
    }
    
    /**
     * Gets the history for a routine day
     * 
     * @param int $routine_id routine_id to search their days
     * @param int $day int of days, 0 to 6 sunday to saturday.
     * 
     * @return fittizen_fitinfo_routine_history array with the routine history
     */
    public function get_routine_history($routine_id, $day)
    {
        $fitinfo_routine_day = $this->get_routine_day($routine_id, $day);
        $fitinfo_routine_history = new fittizen_fitinfo_routine_history(-1);
        return $fitinfo_routine_history->findAll('fitinfo_routine_days_id', $fitinfo_routine_day->id);
    }
    
    public function set_routine_history($routine_id, $day, $achieved, $trainer_id = null)
    {
        $fitinfo_routine_day = $this->get_routine_day($routine_id, $day);
        $fitinfo_routine_history = new fittizen_fitinfo_routine_history(-1);
        $fitinfo_routine_history->fitinfo_routine_days_id = $fitinfo_routine_day->id;
        $fitinfo_routine_history->created_date = AuxTools::DateTimeCurrentString();
        $fitinfo_routine_history->achieved =$achieved;
        if($trainer_id!==null)
        {
            $fitinfo_routine_history->trainer_id=$trainer_id;
        }
        $fitinfo_routine_history->insert();
    }
    
    /**
     * Deletes a routine history instance
     * 
     * @param int $fitinfo_routine_history_id id of routine history
     * 
     * @return @return boolean|fittizen_fitinfo_routine_history Not false on success.
     */
    public function remove_routine_history($fitinfo_routine_history_id)
    {
        $fitinfo_routine_history = new fittizen_fitinfo_routine_history(-1);
        return $fitinfo_routine_history->delete('id', $fitinfo_routine_history_id);
    }
    
    /**
     * Updates an user specific body part
     * 
     * @param string $type string with the type of body
     * part.('hip','thigh','waist', 'chest', 'height'
     * 'upper_arm', 'weight')
     * @param float $value measurement of the type of body specification.
     * 
     * @return boolean|dbobject dbobject on success, false otherwise 
     */
    public function update_specific_body_part_history($type, $value)
    {
        $cname='fittizen_'.$type.'_history';
        if(class_exists($cname) == true && $value > 0.0)
        {
            $obj = (new $cname());
            $obj->set('fitinfo_id', $this->id);
            $obj->set('created_date', AuxTools::DateTimeCurrentString());
            $obj->set($type,$value);
            return $obj->insert();
        }
        return false;
    }
    
    /**
     * Updates the visit history
     */
    public function update_visit_history()
    {
        $vh = new fittizen_fitinfo_visit_history(-1);
        $vh->fitinfo_id = $this->id;
        $vh->visit_date = AuxTools::DateTimeCurrentString();
        $vh->insert();
        $this->last_visit_date = $vh->visit_date;
        $this->update();
    }
    
    /**
     * gets the history of the fitinfo visits to the system.
     * @return fittizen_fitinfo_visit_history array of visits
     */
    public function get_visit_history()
    {
        $vh = new fittizen_fitinfo_visit_history(-1);
        return $vh->findAll('fitinfo_id',$this->id, true,'visit_date');
    }
    
    /**
     * Check all the visits made in that day
     * 
     * @param string $date_str A date/time string. Valid formats are explained in Date and Time Formats,
     * now for today, it will ignore time values
     * 
     * @return array object array of visits made with the profile id. 
     */
    public static function get_visit_by_day($date_str = "now")
    {
        $date = AuxTools::DateTimeGenerate($date_str);
        $date2 = AuxTools::DateTimeGenerate($date_str);
        $date->setTime(0, 0, 0);
        $date2->setTime(23,59,59);
        $query = "select distinct fitinfo_id from `#__fittizen_fitinfo_visit_history` where `visit_date` BETWEEN '".
                $date->format('Y-m-d H:i:s')."' AND '".$date2->format('Y-m-d H:i:s')."' ";
        $db = new dbprovider(true);
        $db->Query($query);
        return $db->getNextObjectList();
    }
    
    /**
     * Check all the new users of the current month
     * 
     * @param string $date_str A date/time string. Valid formats are explained in Date and Time Formats,
     * now for today, it will ignore time values
     * 
     * @return array object array of profile ids. 
     */
    public static function get_monthly_new_users()
    {
        $date_str = "now";
        $date = new DateTime('now');
        $date->modify('first day of this month');
        $date2 = AuxTools::DateTimeGenerate($date_str);
        $query = "select distinct id from `#__fittizen_fitinfos` where `created_date` BETWEEN '".
                $date->format('Y-m-d H:i:s')."' AND '".$date2->format('Y-m-d H:i:s')."' ";
        $db = new dbprovider(true);
        $db->Query($query);
        return $db->getNextObjectList();
    }
    
    /**
     * Check all the new trainers of the current month
     * 
     * @param string $date_str A date/time string. Valid formats are explained in Date and Time Formats,
     * now for today, it will ignore time values
     * 
     * @return array object array of profile ids. 
     */
    public static function get_monthly_new_trainers()
    {
        $date_str = "now";
        $date = new DateTime('now');
        $date->modify('first day of this month');
        $date2 = AuxTools::DateTimeGenerate($date_str);
        $query = "select distinct `FF`.`id` from `#__fittizen_fitinfos` AS `FF`"
               . " INNER JOIN `#__fittizen_trainers` AS `FT` ON `FT`.`fitinfo_id` = `FF`.`id` where `FF`.`created_date` BETWEEN '".
                $date->format('Y-m-d H:i:s')."' AND '".$date2->format('Y-m-d H:i:s')."' ";
        $db = new dbprovider(true);
        $db->Query($query);
        return $db->getNextObjectList();
    }
    
    /**
     * Check all the new trainers of the current month
     * 
     * @param string $date_str A date/time string. Valid formats are explained in Date and Time Formats,
     * now for today, it will ignore time values
     * 
     * @return array object array of profile ids. 
     */
    public static function get_monthly_new_fittizens()
    {
        $date_str = "now";
        $date = new DateTime('now');
        $date->modify('first day of this month');
        $date2 = AuxTools::DateTimeGenerate($date_str);
        $query = "select distinct `FF`.`id` from `#__fittizen_fitinfos` AS `FF`"
               . " INNER JOIN `#__fittizen_fittizen` AS `FT` ON `FT`.`fitinfo_id` = `FF`.`id` where `FF`.`created_date` BETWEEN '".
                $date->format('Y-m-d H:i:s')."' AND '".$date2->format('Y-m-d H:i:s')."' ";
        $db = new dbprovider(true);
        $db->Query($query);
        return $db->getNextObjectList();
    }
    
    /**
     * Get the average visits in the system
     * @param string $since date string since you want
     * to get the visits history
     * @param string $until until what day you want the history
     * @return array array with the averages
     * array[0] = fittizens, array[1] = trainers, array[2]=users
     */
    public static function get_average_visits($since='now', $until='now')
    {
        $date = AuxTools::DateTimeGenerate($since);
        $date2 = AuxTools::DateTimeGenerate($until);
        $interval = new DateInterval("P1D");
        $fittizens=0;
        $users=0;
        $trainers=0;
        while($date < $date2)
        {
            $users += count(self::get_visit_by_day($date->format('Y-m-d')));
            $fittizens += count(self::get_fittizens_visit_by_day($date->format('Y-m-d')));
            $users += count(self::get_trainers_visit_by_day($date->format('Y-m-d')));
            $date->add($interval);
        }
        return array($fittizens,$trainers,$users);
    }
    
    /**
     * Check all the visits made by trainers in that day
     * 
     * @param string $date_str A date/time string. Valid formats are explained in Date and Time Formats,
     * now for today, it will ignore time values
     * 
     * @return array object array of visits made with the profile id. 
     */
    public static function get_trainers_visit_by_day($date_str = "now")
    {
        $date = AuxTools::DateTimeGenerate($date_str);
        $date2 = AuxTools::DateTimeGenerate($date_str);
        $date->setTime(0, 0, 0);
        $date2->setTime(23,59,59);
        $query = "select distinct `FVH`.`fitinfo_id` from `#__fittizen_fitinfo_visit_history` AS `FVH`"
               . " INNER JOIN `#__fittizen_fittizen` AS `FF` ON `FVH`.`fitinfo_id` = `FF`.fitinfo_id"
               . " where `FVH`.`visit_date` BETWEEN '".$date->format('Y-m-d H:i:s')."' AND"
               . " '".$date2->format('Y-m-d H:i:s')."' ";
        $db = new dbprovider(true);
        $db->Query($query);
        return $db->getNextObjectList();
    }
    
    /**
     * Check all the visits made by fittizens in that day
     * 
     * @param string $date_str A date/time string. Valid formats are explained in Date and Time Formats,
     * now for today, it will ignore time values
     * 
     * @return array object array of visits made with the profile id. 
     */
    public static function get_fittizens_visit_by_day($date_str = "now")
    {
        $date = AuxTools::DateTimeGenerate($date_str);
        $date2 = AuxTools::DateTimeGenerate($date_str);
        $date->setTime(0, 0, 0);
        $date2->setTime(23,59,59);
        $query = "select distinct `FVH`.`fitinfo_id` from `#__fittizen_fitinfo_visit_history` AS `FVH`"
               . " INNER JOIN `#__fittizen_trainers` AS `FF` ON `FVH`.`fitinfo_id` = `FF`.fitinfo_id"
               . " where `FVH`.`visit_date` BETWEEN '".$date->format('Y-m-d H:i:s')."' AND"
               . " '".$date2->format('Y-m-d H:i:s')."' ";
        $db = new dbprovider(true);
        $db->Query($query);
        return $db->getNextObjectList();
    }
    
    /**
     * Removes a specific history record of body_part registry
     *
     * @param string $type string with the type of body
     * part.('hip','thigh','waist', 'chest', 'height'
     * 'upper_arm', 'weight') 
     * @param int $history_id history id
     * 
     * @return boolean|dbobject dbobject on success, false otherwise 
     */
    public function delete_specific_body_part_history($type, $history_id)
    {
        $cname='fittizen_'.$type.'_history';
        if(class_exists($cname) == true)
        {
            $obj = (new $cname());
            return $obj->delete('id', $history_id);
        }
        return false;
    }
    
    /**
     * Gets a specific history record of body_part registry
     *
     * @param string $type string with the type of body
     * part.('hip','thigh','waist', 'chest', 'height'
     * 'upper_arm', 'weight') 
     * 
     * @return boolean|dbobject dbobject on success, false otherwise 
     */
    public function get_specific_body_part_history($type)
    {
        $cname='fittizen_'.$type.'_history';
        if(class_exists($cname) == true)
        {
            $obj = (new $cname());
            return $obj->findAll('fitinfo_id', $this->id);
        }
        return false;
    }
    
    /**
     * Creates a new fitinfo profile
     * @param int    $attributes profile id information.
     * @param string $type type of profile to create.
     * 'fittizen' or 'trainer'
     * @return bll_fitinfos not false on success.
     */
    public static function create($attributes, $type = 'fittizen')
    {
        $obj = new bll_fitinfos(0);
        $obj->setAttributes($attributes);
        $obj->profile_code=$obj->generate_code();
        $obj->created_date = AuxTools::DateTimeCurrentString();
        $obj->last_notification_check = $obj->created_date;
        $obj=$obj->insert();
        if($obj !== false)
        {
            if($type == 'fittizen')
            {
                bll_fittizens::create($obj->id);
                return $obj;
            }
            elseif($type == 'trainer')
            {
                bll_trainers::create($obj->id);
                return $obj;
            }
        }
        return false;
    }
    
    /**
     * Gets a profile
     * 
     * @param type $id fitinfo_id of the profile to view.
     * 
     * @return bll_fitinfos profile
     */
    public static function getProfile($id)
    {
        return new bll_fitinfos($id);
    }
    
    /**
     * Gets a profile
     * 
     * @param integer $id user id of the profile to view.
     * 
     * @return bll_fitinfos profile
     */
    public static function getProfileByUserId($id)
    {
        $obj = new bll_fitinfos(-1);
        return $obj->find('user_id', $id);
    }
    
    /**
     * Perform a comment
     * 
     * @param string $type name of the type of comment
     * use the bll_fitinfos_constants values to define the type.
     * @param string $message string with the message of the comment
     * @param string $key name of the type foreign key
     * use the bll_fitinfos_constants values to define the key name.
     * @param string $key_value value of the foreign key
     * @param string $mention_type name of the type of mention
     * use the bll_fitinfos_constants values to define the type.
     * @param array $fitinfos_mentions array of fitinfo_id mentioned
     * in the message
     * 
     * @return boolean true on success.
     */
    public function comment($type, $message, $key, $key_value, $mention_type, $fitinfos_mentions=array())
    {
        $cname = "fittizen_comment_".$type;
        $comment = new $cname(0);
        $comment->set('created_date' , AuxTools::DateTimeCurrentString());
        $comment->set('fitinfo_id' , $this->id);
        $comment->set('message' , $message);
        $comment->set($key, $key_value);
        if($comment->insert()!==false)
        {
            foreach($fitinfos_mentions as $fitinfo_id)
            {
                $this->mention($mention_type, $fitinfo_id, "comment_".$key, $comment->id);
            }
        }
        return false;
    }
    
    /**
     * Gets all the comments of the type
     * 
     * @param string $type name of the type of comment
     * use the bll_fitinfos_constants values to define the type.
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
     * @return boolean|dbobject Not false on success.
     */
    public static function get_comments($type,$field,$value, $DESC=true, 
            $order_field='created_date', $lower_limit=null, $upper_limit=null)
    {
        $cname="fittizen_comment_".$type;
        $class = new $cname(-1);
        return $class->findAll($field, 
                              $value, 
                $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Get a comment of the type
     * 
     * @param string $type name of the type of comment
     * use the bll_fitinfos_constants values to define the type.
     * @param int $id id of the comment to delete
     * 
     * @return boolean|dbobject Not false on success.
     * 
     */
    public static function get_comment($type,$id)
    {
        $cname="fittizen_comment_".$type;
        $class = new $cname($id);
        return $class;
    }
    
    /**
     * Remove comment of the type
     * 
     * @param string $type name of the type of comment
     * use the bll_fitinfos_constants values to define the type.
     * @param int $id id of the comment to delete
     * 
     * @return boolean|dbobject Not false on success.
     */
    public static function remove_comment($type,$id)
    {
        $cname="fittizen_comment_".$type;
        $class = new $cname($id);
        return $class->delete();
    }
    
    /**
     * Makes a post on the fitinfo profile
     * 
     * @param string $message message to post
     * @param array $fitinfos_mentions array of fitinfo_id mentioned
     * in the message
     * @return boolean true on success, false otherwise
     */
    public function post($message, $fitinfos_mentions=array())
    {
        $post  = new fittizen_post(0);
        $post->message=$message;
        $post->fitinfo_id=$this->id;
        $post->created_date = AuxTools::DateTimeCurrentString();
        if($post->insert()!==false)
        {
            foreach($fitinfos_mentions as $fitinfo_id)
            {
                $this->mention(bll_fitinfos_constants::POSTS, $fitinfo_id, 
                bll_fitinfos_constants::POST_ID, $post->id);
            }
            return true; 
        }
        return false;
    }
    
    /**
     * Removes a post
     * @param int $id id of the post to remove
     * 
     * @return boolean|dbobject Not false on success.
     */
    public function remove_post($id)
    {
        $post = new fittizen_post($id);
        return $post->delete();
    }
    
    /**
     * Removes a fitinfo_post
     * @param int $id id of the post to remove
     * 
     * @return boolean|dbobject Not false on success.
     */
    public function remove_fitinfo_post($id)
    {
        $post = new bll_fitinfo_post($id);
        return $post->delete();
    }
    
    /**
     * Makes a post on an external fitinfo profile
     * 
     * @param string $message message to post
     * @param int    $fitinfo_id profile id to post on
     * @param array  $fitinfos_mentions array of fitinfo_id mentioned
     * in the message
     * @return boolean true on success, false otherwise
     */
    public function fitinfo_post($message,$fitinfo_id, $fitinfos_mentions=array())
    {
        $post  = new bll_fitinfo_post(0);
        $post->message=$message;
        $post->sender_id=$this->id;
        $post->receiver_id = $fitinfo_id;
        $post->created_date = AuxTools::DateTimeCurrentString();
        if($post->insert()!==false)
        {
            foreach($fitinfos_mentions as $fitinfo__id)
            {
                $this->mention(bll_fitinfos_constants::FITINFO_POSTS, $fitinfo__id, 
                bll_fitinfos_constants::FITINFO_POST_ID, $post->id);
            }
            return true; 
        }
        return false;
    }
    
    /**
     * Adds a mention of the type
     * 
     * @param string $type name of the type of mention
     * use the bll_fitinfos_constants values to define the type.
     * @param int    $fitinfo_id profile that its been mentioned.
     * @param string $key name of the type foreign key
     * use the bll_fitinfos_constants values to define the key name.
     * @param string $key_value value of the foreign key
     * 
     * @return boolean|dbobject Not false on success.
     */
    public function mention($type, $fitinfo_id, $key, $key_value)
    {
        $cname = "fittizen_".$type."_mentions";
        $mention= new $cname(-1);
        $mention->set('created_date', AuxTools::DateTimeCurrentString());
        $mention->set('fitinfo_id',$fitinfo_id);
        $mention->set($key,$key_value);
        return $mention->insert();
    }
    
    /**
     * Removes a mention of the type
     * 
     * @param string $type name of the type of mention to remove
     * use the bll_fitinfos_constants values to define the type.
     * @param int    $id id of the mention to remove.
     * 
     * @return boolean|dbobject Not false on success.
     */
    public static function remove_mention($type, $id)
    {
        $cname = "fittizen_".$type."_mentions";
        $mention= new $cname($id);
        return $mention->delete();
    }
    
    /**
     * Gets all the mentions of the type
     * 
     * @param string $type name of the type of mention
     * use the bll_fitinfos_constants values to define the type.
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
     * @return boolean|dbobject Not false on success.
     */
    public static function get_mentions($type,$field,$value, $DESC=true, 
            $order_field='created_date', $lower_limit=null, $upper_limit=null)
    {
        $cname="fittizen_".$type."_mentions";
        $class = new $cname(-1);
        return $class->findAll($field, 
                               $value, 
                $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Remove a tag
     * 
     * @param string $type name of the type of tag to remove
     * use the bll_fitinfos_constants values to define the type.
     * @param int $id id of the tag to remove
     * @return boolean|dbobject Not false on success.
     */
    public static function remove_tag($type, $id)
    {
        $cname="fittizen_".$type."_tags";
        $class = new $cname($id);
        return $class->delete();
    }
    
    /**
     * Adds a tag of the type
     * 
     * @param string $type name of the type of tag
     * use the bll_fitinfos_constants values to define the type.
     * @param int    $fitinfo_id profile that its been tagged.
     * @param string $key name of the type foreign key
     * use the bll_fitinfos_constants values to define the key name.
     * @param string $key_value value of the foreign key
     * 
     * @return boolean|dbobject Not false on success.
     */
    public function tag($type, $fitinfo_id, $key, $key_value)
    {
        $cname="fittizen_".$type."_tags";
        $class = new $cname(-1);
        $class->set('fitinfo_id', $fitinfo_id);
        $class->set('created_date', AuxTools::DateTimeCurrentString());
        $class->set($key,$key_value);
        return $class->insert();
    }
    
    /**
     * Gets all the tags from the type
     * 
     * @param string $type name of the type of tag to remove
     * use the bll_fitinfos_constants values to define the type.
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
     * @return dbobject dbobject collection or false on failure.
     */
    public static function get_tags($type, $field = "", $value = "",$DESC=true, 
            $order_field='created_date', $lower_limit=null, $upper_limit=null)
    {
        $cname="fittizen_".$type."_tags";
        $class = new $cname(-1);
        return $class->findAll($field, $value, 
                $DESC, $order_field, $lower_limit, $upper_limit);
    }
    
    /**
     * Gets the main image
     * @return bll_images|bll_timeline_images
     * dbobject or false if the profile does not have
     * a main image.
     */
    public function get_main_image()
    {
        $class = new bll_images(-1);
        $imgs=$class->findAll(array(
                                  array('fitinfo_id','='),
                                  array('main','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array('1','AND')
                                  ));
        if(count($imgs) > 0)
        {
            return $imgs[0];
        }
        else 
        {
            $db = $this->getProvider($this->getDebug());
            $query="SELECT TI.id as id, TI.main as main, TI.panoramic_main as panoramic_main "
                 . "FROM `fittizen_timeline` as T INNER JOIN `fittizen_timeline_images` as TI "
                 . "ON T.id = TI.timeline_id "
                 . "where T.fitinfo_id = '$this->id' AND TI.main = '1'";
            $db->Query($query);
            $imgs= $db->getNextObjectList();
            if(count($imgs) > 0)
            {
                return new bll_timeline_images($imgs[0]->id);
            }
        }
        return false;
    }
    
    /**
     * Gets the main image
     * @return bll_images|bll_timeline_images
     * dbobject or false if the profile does not have
     * a main image.
     */
    public function get_panoramic_main_image()
    {
        $class = new bll_images(-1);
        $imgs=$class->findAll(array(
                                  array('fitinfo_id','='),
                                  array('panoramic_main','=')
                                   ), 
                              array(
                                  array($this->id,null),
                                  array('1','AND')
                                  ));
        if(count($imgs) > 0)
        {
            return $imgs[0];
        }
        else 
        {
            $db = $this->getProvider($this->getDebug());
            $query="SELECT TI.id as id, TI.main as main, TI.panoramic_main as panoramic_main "
                 . "FROM `fittizen_timeline` as T INNER JOIN `fittizen_timeline_images` as TI "
                 . "ON T.id = TI.timeline_id "
                 . "where T.fitinfo_id = '$this->id' AND TI.panoramic_main = '1'";
            $db->Query($query);
            $imgs= $db->getNextObjectList();
            if(count($imgs) > 0)
            {
                return new bll_timeline_images($imgs[0]->id);
            }
        }
        return false;
    }
    
    /**
     * Add a new friend of the fitinfo profile
     * 
     * @param int $friend_id id of the friend to add
     * 
     * @return fittizen_fitinfo_friends not false on success.
     */
    public function add_friend($friend_id)
    {
        
        $friend = new fittizen_fitinfo_friends(-1);
        $friend->fitinfo_id = $this->id;
        $friend->accepted =0;
        $friend->block =0;
        $friend->friend_id =$friend_id;
        $friend->created_date = AuxTools::DateTimeCurrentString();
        return $friend->insert();
    }
    
    /**
     * remove the friend unaccepting not removing
     * 
     * @param int $fitinfo_friends_id id of the relation between friend 
     * and profile
     * @return fittizen_fitinfo_friends not false on success.
     */
    public function unaccept_friend($fitinfo_friends_id)
    {
        $friend = new fittizen_fitinfo_friends($fitinfo_friends_id);
        $friend->accepted=0;
        return $friend->update();
    }
    
    /**
     * accept a friend request
     * 
     * @param int $fitinfo_friends_id id of the relation between friend 
     * and profile
     * @return fittizen_fitinfo_friends not false on success.
     */
    public function accept_friend($fitinfo_friends_id)
    {
        $friend = new fittizen_fitinfo_friends($fitinfo_friends_id);
        $friend->accepted=1;
        return $friend->update();
    }
    
    /**
     * block a friend
     * 
     * @param int $fitinfo_friends_id id of the relation between friend 
     * and profile
     * @return fittizen_fitinfo_friends not false on success.
     */
    public function block_friend($fitinfo_friends_id)
    {
        $friend = new fittizen_fitinfo_friends($fitinfo_friends_id);
        $friend->block=1;
        return $friend->update();
    }
    
    /**
     * unblock a friend
     * 
     * @param int $fitinfo_friends_id id of the relation between friend 
     * and profile
     * @return fittizen_fitinfo_friends not false on success.
     */
    public function unblock_friend($fitinfo_friends_id)
    {
        $friend = new fittizen_fitinfo_friends($fitinfo_friends_id);
        $friend->block=0;
        return $friend->update();
    }
    
    /**
     * Gets all the active friends
     * 
     * @param int $fitinfo_id id of the profile to find friends
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * FIT for fittizen_fitinfos tables, FRIEND for 
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * 
     * @return bll_fitinfos array of friends profiles
     */
    public static function get_active_friends($fitinfo_id,$DESC=true, 
            $order_field='name', $lower_limit=null, $upper_limit=null)
    {
        $db = new dbprovider(true);
        $fitinfo_id = $db->escape_string($fitinfo_id);
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
        $query="SELECT DISTINCT FIT.id as id, "
            . "FRIEND.created_date as friends_since, FIT.name "
            . "  FROM `#__fittizen_fitinfo_friends` as FRIEND "
            . "INNER JOIN `#__fittizen_fitinfos` as FIT ON "
            . "FRIEND.fitinfo_id = FIT.id where FRIEND.fitinfo_id = $fitinfo_id AND FRIEND.block = '0' AND FRIEND.accepted = '1' "
            . " ORDER BY `$order_by` $order_dir $limit";
        
        $db->Query($query);
        $result = array();
        $list = $db->getNextArray();
        foreach($list as $obj)
        {
            $profile = new bll_fitinfos($obj['id']);
            foreach($obj as $k => $val)
            {
                if($k != "id")
                {
                    $profile->addAttribute($k, $val);
                }
            }
            $result[]=$profile;
        }
        return $result;
    }
    
    /**
     * Gets all the blocked friends
     * 
     * @param int $fitinfo_id id of the profile to find friends
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * FIT for fittizen_fitinfos tables, FRIEND for 
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * 
     * @return bll_fitinfos array of friends profiles
     */
    public static function get_block_friends($fitinfo_id,$DESC=true, 
            $order_field='name', $lower_limit=null, $upper_limit=null)
    {
        $db = new dbprovider(true);
        $fitinfo_id = $db->escape_string($fitinfo_id);
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
        $query="SELECT DISTINCT FIT.id as id, "
            . "FRIEND.created_date as friends_since "
            . "  FROM `#__fittizen_fitinfo_friends` as FRIEND "
            . "INNER JOIN `#__fittizen_fitinfos` as FIT ON "
            . "FRIEND.fitinfo_id = FIT.id where FRIEND.fitinfo_id = $fitinfo_id AND FRIEND.block = '1' "
            . " ORDER BY `$order_by` $order_dir $limit";
        
        $db->Query($query);
        $result = array();
        $list = $db->getNextArray();
        foreach($list as $obj)
        {
            $profile = new bll_fitinfos($obj['id']);
            foreach($obj as $k => $val)
            {
                if($k != "id")
                {
                    $profile->addAttribute($k, $val);
                }
            }
            $result[]=$profile;
        }
        return $result;
    }
    
    /**
     * Gets all the friend requests
     * 
     * @param int $fitinfo_id id of the profile to find friends
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * FIT for fittizen_fitinfos tables, FRIEND for 
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * 
     * @return bll_fitinfos array of friends profiles
     */
    public static function get_friend_requests($fitinfo_id,$DESC=true, 
            $order_field='FRIEND.created_date', $lower_limit=null, $upper_limit=null)
    {
        $db = new dbprovider(true);
        $fitinfo_id = $db->escape_string($fitinfo_id);
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
        $query="SELECT DISTINCT FIT.id as id, "
            . "FRIEND.created_date as friends_since "
            . "  FROM `fittizen_fitinfo_friends` as FRIEND "
            . "INNER JOIN `fittizen_fitinfos` FIT ON "
            . "FRIEND.fitinfo_id = FIT.id where FRIEND.fitinfo_id = $fitinfo_id AND FRIEND.block = '0' AND FRIEND.accepted = '0'"
            . " ORDER BY `$order_by` $order_dir $limit";
        
        $db->Query($query);
        $result = array();
        $list = $db->getNextArray();
        foreach($list as $obj)
        {
            $profile = new bll_fitinfos($obj['id']);
            foreach($obj as $k => $val)
            {
                if($k != "id")
                {
                    $profile->addAttribute($k, $val);
                }
            }
            $result[]=$profile;
        }
        return $result;
    }
    
    /**
     * Get the account specificaly as fittizen or trainer
     * @param integer $fitinfo_id id of the profile to search
     * @return bll_fittizens|bll_trainers
     */
    public static function get_account($fitinfo_id)
    {
        $obj =new bll_fittizens(-1);
        $ret= $obj->find('fitinfo_id', $fitinfo_id);
        if($ret->id > 0)
        {
            return $ret;
        }
        $obj =new bll_trainers(-1);
        $ret= $obj->find('fitinfo_id', $fitinfo_id);
        if($ret->id > 0)
        {
            return $ret;
        }
    }
    
    /**
     * Filter the users in the system
     * 
     * @param array $params hash of params of the search
     * @param  boolean $DESC ascendent
     * @param  string  $order_field Field for the order by
     * FIT for fittizen_fitinfos tables, FRIEND for 
     * @param  integer $lower_limit  lower limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @param  integer $upper_limit higher limit on the query, it must be
     * an integer otherwise is going to be ignored
     * @return bll_fitinfos array of fitinfos
     */
    public static function filter_users($params=array(),$DESC=true, 
            $order_field='id', $lower_limit=null, $upper_limit=null)
    {
        $obj = new bll_fitinfos(-1);
        if(count($params) == 0)
        {
            $tresult= $obj->findAll(null,null, $DESC, $order_field, $lower_limit, $upper_limit);
            $result = array();
            foreach($tresult as $tres)
            {
                $result[]=self::get_account($tres->id);
            }
            return $result;
        }
        else
        {
            $db = new dbprovider(true);
            $gender_id = null;
            $suppl_id = null;
            $nicho_id = null;
            $gym_id=null;
            $facebook="";
            $type="";
            $city="";
            $country="";
            $birth_date="";
            $rate="";
            if(filter_has_var(INPUT_POST, 'rate'))
            {
                $rate = $db->escape_string(filter_input(INPUT_POST, 'rate'));
            }
            if(filter_has_var(INPUT_POST, 'facebook'))
            {
                $facebook = $db->escape_string(filter_input(INPUT_POST, 'facebook'));
            }
            if(filter_has_var(INPUT_POST, 'type'))
            {
                $type = $db->escape_string(filter_input(INPUT_POST, 'type'));
            }
            if(filter_has_var(INPUT_POST, 'birth_date'))
            {
                $birth_date = $db->escape_string(filter_input(INPUT_POST, 'birth_date'));
            }
            if(filter_has_var(INPUT_POST, 'city'))
            {
                $city = $db->escape_string(filter_input(INPUT_POST, 'city'));
            }
            if(filter_has_var(INPUT_POST, 'country'))
            {
                $country = filter_input(INPUT_POST, 'country');
            }
            if(filter_has_var(INPUT_POST, 'gender_id'))
            {
                $gender_id = $db->escape_string(filter_input(INPUT_POST, 'gender_id'));
            }
            if(filter_has_var(INPUT_POST, 'suppl_id'))
            {
                $suppl_id = $db->escape_string(filter_input(INPUT_POST, 'suppl_id'));
            }
            if(filter_has_var(INPUT_POST, 'nicho_id'))
            {
                $nicho_id = $db->escape_string(filter_input(INPUT_POST, 'nicho_id'));
            }
            if(filter_has_var(INPUT_POST, 'gym_id'))
            {
                $gym_id = $db->escape_string(filter_input(INPUT_POST, 'gym_id'));
            }
            $glue = "AND";
            $query = 'SELECT * FROM `#__fittizen_fitinfos` '
                    . 'WHERE 1=1 ';
            if($gender_id > 0)
            {
                $query.=" $glue `gender_id`='$gender_id' ";
            }
            if($city != "")
            {
                $query.=" $glue `location_id` IN (SELECT id FROM `#__fittizen_locations` WHERE `locality` = '$city' AND `country` = '$country')";
            }
            if($city == "" && $country != "")
            {
                $query.=" $glue `location_id` IN (SELECT id FROM `#__fittizen_locations` WHERE `country` = '$country')";
            }
            if($suppl_id > 0)
            {
                $query.=" $glue `id` IN (SELECT `fitinfo_id` FROM `#__fittizen_fitinfo_supplement` WHERE `supplement_id` = $suppl_id) ";
            }
            if($nicho_id > 0)
            {
                $query.=" $glue `id` IN (SELECT `fitinfo_id` FROM `#__fittizen_fitinfo_nichos` WHERE `nicho_id` = $nicho_id) ";
            }
            if($gym_id > 0)
            {
                $query.=" $glue `id` IN (SELECT `fitinfo_id` FROM `#__fittizen_fitinfo_gyms` WHERE `gym_id` = $gym_id) ";
            }
            if($facebook > 0)
            {
                switch($facebook)
                {
                    case "1":
                        $query.=" $glue `fb_id` <> NULL ";
                    break;
                    case "2":
                        $query.=" $glue `gplus_id` <> NULL ";
                    break;
                    case "3":
                        $query.=" $glue `twitter_id` <> NULL ";
                    break;
                }
            }
            
            if($type > 0)
            {
                switch($type)
                {
                    case "1":
                        $query.=" $glue `id` IN (SELECT `fitinfo_id` FROM `#__fittizen_fittizen`) ";
                    break;
                    case "2":
                        $query.=" $glue `id` IN (SELECT `fitinfo_id` FROM `#__fittizen_trainers`) ";
                    break;
                }
            }
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
            $query.=" ORDER BY `$order_by` $order_dir $limit";
            $db->Query($query);
            $result=array();
            foreach($db->getNextObjectList() as $tres)
            {
                $result[]=self::get_account($tres->id);
            }
            return $result;
        }
    }
}

