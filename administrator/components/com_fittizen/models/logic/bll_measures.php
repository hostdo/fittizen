<?php
/**
 * Class for measures
 *
 * @author Gabriel
 */
class bll_measures {
    
    /**
     *
     * @var hash of measures values for weight
     * respective to kg
     */
    private static $mass_measures = array('kg'=>1, 'lb'=>2.20462);
    /**
     *
     * @var hash of measures values for height respective to
     * meters
     */
    private static $distance_measures = array('ft'=>3.28084, 'm'=>1, 'in'=>'39.3701', 'cm'=>'100');
    
    public static $kg = 'kg';
    public static $lb = 'lb';
    public static $ft='ft';
    public static $m = 'm';
    public static $in = 'in';
    public static $cm = 'cm';
    
    public static $KG = 'KG';
    public static $LB = 'LB';
    public static $FT='FT';
    public static $M = 'M';
    public static $IN = 'IN';
    public static $CM = 'CM';
    
    /**
     * Convert from one unit to another
     * 
     * @param  string $from base unit
     * @param  string $to unit to convert
     * @param  real $value value to convert
     * @return real|null converted result, null if invalid measure.
     */
    public static function convert($from, $to, $value)
    {
        $result=null;
        $from=strtolower($from);
        $to=strtolower($to);
        if(isset(self::$mass_measures[$from]) && isset(self::$mass_measures[$to]))
        {
            $result=0.0;
            $result = $value/self::$mass_measures[$from];
            $result = $result*self::$mass_measures[$to];
        }
        else if(isset(self::$distance_measures[$from]) && isset(self::$distance_measures[$to]))
        {
            $result=0.0;
            $result = $value/self::$distance_measures[$from];
            $result = $result*self::$distance_measures[$to];
        }
        return $result;
    }
}
