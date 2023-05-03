<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_field.php";

    class WebFormTextArea extends WebFormField {

        public static string $TYPE = "textarea";
        
        public function getType(): string {
            return self::$TYPE;
        }

        public static function constructFromRecord(array $row): WebFormTextField {
            $field = new WebFormTextField($row["label"], $row["name"], $row["mandatory"] == 1 ? true : false);
            $field->setId($row["id"]);
            return $field;
        }
    }
    
?>