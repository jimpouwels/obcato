<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_field.php";

    class WebFormTextField extends WebFormField {

        public static string $TYPE = "textfield";
        private static int $SCOPE = 13;
        
        public function __construct() {
            parent::__construct(self::$SCOPE);
        }
        
        public function getType(): string {
            return self::$TYPE;
        }

        public static function constructFromRecord(array $row): WebFormTextField {
            $field = new WebFormTextField();
            $field->initFromDb($row);
            return $field;
        }

        protected function initFromDb(array $row): void {
            parent::initFromDb($row);
        }
    }
    
?>