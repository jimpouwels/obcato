<?php
    defined('_ACCESS') or die;

    class PositionEditor extends Panel {

        private static $TEMPLATE = "blocks/positions/editor.tpl";

        private $_template_engine;
        private $_current_position;

        public function __construct($current_position) {
            parent::__construct($this->getTextResource('blocks_edit_position_title'));
            $this->_current_position = $current_position;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $new_position = true;
            $position_id = null;
            if (!is_null($this->_current_position)) {
                $new_position = false;
                $position_id = $this->_current_position->getId();
            }
            $this->_template_engine->assign("id", $position_id);
            $this->_template_engine->assign("new_position", $new_position);
            $this->_template_engine->assign("name_field", $this->renderNameField());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }

        private function renderNameField() {
            $name_value = null;
            if (isset($this->_current_position))
                $name_value = $this->_current_position->getName();
            $name_field = new TextField("name", $this->getTextResource("blocks_position_name_field"), $name_value, true, false, null);
            return $name_field->render();
        }
    }
