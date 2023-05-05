<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_field.php";

    class WebFormTextField extends WebFormField {

        public static string $TYPE = "textfield";
        private static int $SCOPE = 13;
        
        public function __construct(string $label, string $name, bool $mandatory) {
            parent::__construct(self::$SCOPE, $label, $name, $mandatory);
        }
        
        public function getType(): string {
            return self::$TYPE;
        }

        public static function constructFromRecord(array $row): WebFormTextField {
            $field = new WebFormTextField($row["label"], $row["name"], $row["mandatory"] == 1 ? true : false);
            $field->setId($row["id"]);
            $field->setScopeId($row["scope_id"]);
            $field->setTemplateId($row["template_id"]);
            return $field;
        }
    }
    
?>