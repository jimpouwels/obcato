<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/form_field.php";

    class TextField extends FormField {

        private static string $TEMPLATE = "system/form_textfield.tpl";
        private bool $_is_visible;

        public function __construct(string $name, string $label, ?string $value, bool $mandatory, bool $linkable, ?string $class_name, bool $is_visible = true) {
            parent::__construct($name, $value, $label, $mandatory, $linkable, $class_name);
            $this->_is_visible = $is_visible;
        }

        public function render(): string {
            $this->getTemplateEngine()->assign("is_visible", $this->_is_visible);
            return parent::render() . $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }

    }
