<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_field.php";

    class WebFormButton extends WebFormItem {

        public static string $TYPE = "button";
        private static int $SCOPE = 17;
        
        public function __construct(string $label, string $name) {
            parent::__construct(self::$SCOPE, $label, $name);
        }
        
        public function getType(): string {
            return self::$TYPE;
        }

        public static function constructFromRecord(array $row): WebFormButton {
            $field = new WebFormButton($row["label"], $row["name"]);
            $field->setId($row["id"]);
            $field->setScopeId($row["scope_id"]);
            $field->setTemplateId($row["template_id"]);
            return $field;
        }
    }
    
?>