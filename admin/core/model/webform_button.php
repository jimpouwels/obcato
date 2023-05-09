<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_field.php";

    class WebFormButton extends WebFormItem {

        public static string $TYPE = "button";
        private static int $SCOPE = 17;
        
        public function __construct() {
            parent::__construct(self::$SCOPE);
        }
        
        public function getType(): string {
            return self::$TYPE;
        }

        public static function constructFromRecord(array $row): WebFormButton {
            $field = new WebFormButton();
            $field->initFromDb($row);
            return $field;
        }
    }
    
?>