<?php

    
    defined('_ACCESS') or die;
    
    class DateUtility {
        
        /*
            Private constructor.
        */
        private function __construct() {
        }
    
        /*
            Creates a string value for the given date with the given delimiter.
            
            @param $date The date to convert to string
            @param $target_delimiter The delimiter to use for the date
        */
        public static function mysqlDateToString($date, $target_delimiter) {
            $new_date_value = NULL;
            if (!is_null($date) && $date != '') {
                $splitted = explode(' ', $date);
                $splitted_date = explode('-', $splitted[0]);
                $new_date_value = $splitted_date[2] . $target_delimiter . $splitted_date[1] 
                                  . $target_delimiter . $splitted_date[0];
            }
            return $new_date_value;
        }
        
        /*
            Creates a new date for the given string.
            
            @param $date The string to convert
        */
        public static function stringMySqlDate($date_string) {
            $new_value = NULL;
            if (!is_null($date_string) && $date_string != '') {
                $splitted = explode('-', $date_string);
                $new_value = $splitted[2] . '-' . $splitted[1] . '-' . $splitted[0];
            }
            return $new_value;
        }
        
    }
    
?>