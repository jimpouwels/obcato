<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/visual.php";
    require_once CMS_ROOT . "view/views/form_textfield.php";

    class LabelEditor extends Panel {

        private static $TEMPLATE = "images/labels/editor.tpl";

        private $_template_engine;
        private $_current_label;

        public function __construct($current_label) {
            parent::__construct('Label bewerken');
            $this->_current_label = $current_label;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign("id", $this->_current_label->getId());
            $this->_template_engine->assign("label_name_field", $this->renderLabelNameField());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }

        private function renderLabelNameField() {
            $name_field = new TextField("name", "Naam", $this->_current_label->getName(), true, false, null);
            return $name_field->render();
        }
    }
