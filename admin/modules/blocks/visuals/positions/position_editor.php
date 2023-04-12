<?php
    defined('_ACCESS') or die;

    class PositionEditor extends Panel {

        private static $TEMPLATE = "blocks/positions/editor.tpl";

        private $_current_position;

        public function __construct($current_position) {
            parent::__construct($this->getTextResource('blocks_edit_position_title'));
            $this->_current_position = $current_position;
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent() {
            $new_position = true;
            $position_id = null;
            if (!is_null($this->_current_position)) {
                $new_position = false;
                $position_id = $this->_current_position->getId();
            }
            $this->getTemplateEngine()->assign("id", $position_id);
            $this->getTemplateEngine()->assign("new_position", $new_position);
            $this->getTemplateEngine()->assign("name_field", $this->renderNameField());
            $this->getTemplateEngine()->assign("explanation_field", $this->renderExplanationField());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }

        private function renderNameField() {
            $name_field = new TextField("name", $this->getTextResource("blocks_position_name_field"), $this->_current_position->getName(), true, false, null);
            return $name_field->render();
        }

        private function renderExplanationField() {
            $explanation_field = new TextField("explanation", $this->getTextResource("blocks_position_explanation_field"), $this->_current_position->getExplanation(), false, false, null);
            return $explanation_field->render();
        }
    }
