<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_field.php";

    class WebFormTextField extends WebFormField {

        public static string $TYPE = "textfield";

        public function __construct($label, $name, $mandatory) {
            parent::__construct($label, $name, $mandatory);
        }  
        
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