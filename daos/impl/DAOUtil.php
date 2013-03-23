<?php

/*
 * DAOUtil.php
 */

/**
 * Description of DAOUtil
 *
 * @author Luis
 * @since Feb 27, 2013
 */
class DAOUtil {
    private static $format = 'Y-m-d H:i:s';
    
    /**
     * 
     * @param DateTime $dateTime
     * @return string
     */
    public static function toDataBaseTime(DateTime $dateTime){
        return $dateTime->format(self::$format);
    }
    
    /**
     * 
     * @param type $dateTime
     * @return DateTime
     */
    public static function toDateTime($dateTime){
        $date = new DateTime(NULL);
        $time = strtotime($dateTime);
        $date->setTimestamp($time);
        return $date;
    }
    
    
    /**
     * 
     * @param type $dateTime
     * @return string
     */
    public static function listToString($list){
        $str = "";
        if($list != '' && is_array($list)){
            $i = 0;
            foreach( $list as $v){
                if($i != 0){
                    $str .= ",".$v;
                } else{
                    $str .= $v;
                }
                
                $i++;
            }
        }
        return $str;
    }
    
}

?>
