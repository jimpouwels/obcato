<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/webform_field.php";

    class WebFormTextArea extends WebFormField {

        public static string $TYPE = "textarea";
        private static int $SCOPE = 14;
        
        public function __construct(string $label, string $name, bool $mandatory) {
            parent::__construct(self::$SCOPE, $label, $name, $mandatory);
        }

        public function getType(): string {
            return self::$TYPE;
        }

        public static function constructFromRecord(array $row): WebFormTextArea {
            $field = new WebFormTextArea($row["label"], $row["name"], $row["mandatory"] == 1 ? true : false);
            $field->setId($row["id"]);
            $field->setScopeId($row["scope_id"]);
            $field->setTemplateId($row["template_id"]);
            return $field;
        }
    }
    
?>