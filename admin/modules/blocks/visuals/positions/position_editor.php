<?php
    defined('_ACCESS') or die;

    class PositionEditor extends Panel {

        private BlockPosition $_current_position;

        public function __construct(BlockPosition $current_position) {
            parent::__construct($this->getTextResource('blocks_edit_position_title'));
            $this->_current_position = $current_position;
        }

        public function getPanelContentTemplate(): string {
            return "modules/blocks/positions/editor.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $position_id = $this->_current_position->getId();
            $data->assign("id", $position_id);
            $data->assign("name_field", $this->renderNameField());
            $data->assign("explanation_field", $this->renderExplanationField());
        }

        private function renderNameField(): string {
            $name_field = new TextField("name", $this->getTextResource("blocks_position_name_field"), $this->_current_position->getName(), true, false, null);
            return $name_field->render();
        }

        private function renderExplanationField(): string {
            $explanation_field = new TextField("explanation", $this->getTextResource("blocks_position_explanation_field"), $this->_current_position->getExplanation(), false, false, null);
            return $explanation_field->render();
        }
    }
