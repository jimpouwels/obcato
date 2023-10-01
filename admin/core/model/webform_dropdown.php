<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_dropdown_option.php";
    require_once CMS_ROOT . "core/model/webform_field.php";

    class WebFormDropDown extends WebFormField {
        
        public static string $TYPE = "dropdown";
        private array $_options = array();
        private static int $SCOPE = 15;

        public function __construct() {
            parent::__construct(self::$SCOPE);
        }

        public function setOptions(array $options): void {
            $this->_options = $options;
        }

        public function getOptions(): array {
            return $this->_options;
        }
        
        public function addOption(WebFormDropDownOption $option) {
            $this->_options[] = $option;
        }

        public function getType(): string {
            return self::$TYPE;
        }

        public static function constructFromRecord(array $row): WebFormDropDown {
            $field = new WebFormDropDown();
            $field->initFromDb($row);
            return $field;
        }

    }
    
?>