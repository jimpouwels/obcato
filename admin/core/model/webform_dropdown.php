<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_dropdown_option.php";
    require_once CMS_ROOT . "core/model/webform_field.php";

    class WebFormDropDown extends WebFormField {
        
        public static string $TYPE = "dropdown";
        private array $_options = array();
        private static int $SCOPE = 15;

        public function __construct(string $label, string $name, bool $mandatory, array $options) {
            parent::__construct(self::$SCOPE, $label, $name, $mandatory);
            $this->_options = $options;
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

        public static function constructFromRecord(array $row, array $options): WebFormDropDown {
            $field = new WebFormDropDown($row["label"], $row["name"], $row["mandatory"] == 1 ? true : false, $options);
            $field->setId($row["id"]);
            $field->setScopeId($row["scope_id"]);
            $field->setTemplateId($row["template_id"]);
            return $field;
        }
    }
    
?>