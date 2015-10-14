<?php

    
    defined('_ACCESS') or die;
    
    class DateUtility {
        
        private function __construct() {
        }
        
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

        public static function stringMySqlDate($date_string) {
            if (!is_null($date_string) && $date_string != '') {
                $splitted = explode('-', $date_string);
                return $splitted[2] . '-' . $splitted[1] . '-' . $splitted[0];
            }
        }

    }

?>