<?php
    defined('_ACCESS') or die;
    
    require_once CMS_ROOT . "view/views/form_field.php";
    
    class Pulldown extends FormField {
    
        private static string $TEMPLATE = "system/form_pulldown.tpl";
        private array $_options;
    
        public function __construct(string $name, string $label, ?string $value, array $options, bool $mandatory, ?string $class_name) {
            parent::__construct($name, $value, $label, $mandatory, false, $class_name);
            $this->_options = $options;
        }
    
        public function render(): string {
            $this->getTemplateEngine()->assign("options", $this->_options);
            return parent::render() . $this->getTemplateEngine()->fetch(self::$TEMPLATE);
        }
    
    }

?>