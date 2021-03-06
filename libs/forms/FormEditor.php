<?php
/**
 * Description of FormEditor
 *
 * @author Gabriel Gonzalez Disla
 */
class FormEditor 
{
    /**
     *
     * @var string
     */
    protected $jscode = null;

    /**
     *
     * @var string
     */
    protected $html = null;

    /**
     *
     * @var string
     */
    public $name = null;

    /**
     *
     * @var string
     */
    public $id = null;

    /**
     *
     * @var string
     */
    public $class = null;

    /**
     *
     * @var string
     */
    public $value = "";

    /**
     *
     * @var boolean
     */
    public $required = false;

    /**
     *
     * @var string
     */
    public $tinyjsurl = "";

    /**
     *
     * @var integer
     */
    public $cols = 75;

    /**
     *
     * @var integer
     */
    public $rows = 15;

    /**
     * @var FormEditor contiene la instancia de la clase
     */
    private static $instance = null;
    

    /**
     * configures the editor
     *
     * @param string  $elemname name of the field
     * @param string  $elemid id of the field
     * @param string  $tinyclass class of the html
     * @param string  $value value of the field
     * @param boolean $required true if the field is required
     * @param string  $tinyjsurl url of the js lib of tinymce look for
     * the file. the default value is the path for the administrator section
     * @param integer $cols number of columns
     * @param integer $rows number of rows
     */
    private function __construct($elemname, $elemid, $tinyclass, $value, $required, $tinyjsurl, $cols, $rows) 
    {
        $this->class = $tinyclass;
        $this->cols = $cols;
        $this->id = $elemid;
        $this->name = $elemname;
        $this->required = $required;
        $this->rows = $rows;
        $this->tinyjsurl = $tinyjsurl;
        $this->value = $value;
        $this->set();
    }

    /**
     * configures the editor
     *
     * @param string  $elemname name of the field
     * @param string  $elemid id of the field
     * @param string  $tinyclass class of the html
     * @param string  $value value of the field
     * @param boolean $required true if the field is required
     * @param string  $tinyjsurl url of the js lib of tinymce look for
     * the file. the default value is the path for the administrator section
     * @param integer $cols number of columns
     * @param integer $rows number of rows
     */
    public static function getInstance($elemname, $elemid, $tinyclass, $value, $required = false, $tinyjsurl = "libs/js/tinymce/tiny_mce.js", $cols = 75, $rows = 15) 
    {
        if (!self::$instance) 
        {
            self::$instance = new FormEditor($elemname, $elemid, $tinyclass, $value, $required, $tinyjsurl, $cols, $rows);
            return self::$instance;
        }
        self::$instance->class = $tinyclass;
        self::$instance->cols = $cols;
        self::$instance->id = $elemid;
        self::$instance->name = $elemname;
        self::$instance->required = $required;
        self::$instance->rows = $rows;
        self::$instance->tinyjsurl = $tinyjsurl;
        self::$instance->value = $value;
        self::$instance->set();
        return array('notnew' => self::$instance);
    }
    
    public function set() 
    {
        $this->jscode = '
                        tinymce.init({
                            selector: "textarea.'.$this->class.'"
                        });

                ';

        $this->html = '<div>';
        if ($this->required == false)
            $this->html.= '<textarea id="' . $this->id . '" name="' . $this->name . '" rows="' . $this->rows . '" cols="' . $this->cols . '" style="width: 80%" class="' . $this->class . '">';
        else {
            $this->html.= '<textarea id="' . $this->id . '" name="' . $this->name . '" rows="' . $this->rows . '" cols="' . $this->cols . '" style="width: 80%" class="' . $this->class . '" required>';
        }
        $this->html.= $this->value . '
			</textarea>
		</div>
		';
    }

    /**
     * Displays the editor
     */
    public function display() 
    {
        $html = '';
        $html.= "<script type=\"text/javascript\">";
        $html.=$this->jscode;
        $html.="</script>";
        $html.=$this->html;
        echo $html;
    }

    /**
     * Gets the html of the generated editor
     * @return string html of the editor
     */
    public function getHtml() 
    {
        $html = '';
        $html.= "<script type=\"text/javascript\">";
        $html.=$this->jscode;
        $html.="</script>";
        $html.="<div>";
        $html.=$this->html;
        $html.="</div>";
        return $html;
    }

}



?>

