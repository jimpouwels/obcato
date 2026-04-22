<?php

namespace Pageflow\Core\utilities;

class DateUtility {

    private function __construct() {}

    public static function mysqlDateToString(string $date, string $target_delimiter): string {
        $new_date_value = null;
        if (!is_null($date) && $date != '') {
            $splitted = explode(' ', $date);
            $splitted_date = explode('-', $splitted[0]);
            $new_date_value = $splitted_date[2] . $target_delimiter . $splitted_date[1]
                . $target_delimiter . $splitted_date[0];
        }
        return $new_date_value;
    }

    public static function stringMySqlDate(?string $date_string): ?string {
        return $date_string ?: null;
    }

}