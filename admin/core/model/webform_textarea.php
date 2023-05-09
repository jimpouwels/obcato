<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_field.php";

    class WebFormTextArea extends WebFormField {

        public static string $TYPE = "textarea";
        private static int $SCOPE = 14;
        
        public function __construct() {
            parent::__construct(self::$SCOPE);
        }

        public function getType(): string {
            return self::$TYPE;
        }

        public static function constructFromRecord(array $row): WebFormTextArea {
            $field = new WebFormTextArea();
            $field->initFromDb($row);
            return $field;
        }
    }
    
?>