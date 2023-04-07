<?php
    defined('_ACCESS') or die;

    class TermEditor extends Panel {

        private static $TEMPLATE = "articles/terms/editor.tpl";

        private $_template_engine;
        private $_current_term;

        public function __construct($current_term) {
            parent::__construct($this->getTextResource("articles_terms_editor_title"), 'term_editor_panel');
            $this->_current_term = $current_term;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign("id", $this->_current_term->getId());
            $this->_template_engine->assign("name_field", $this->renderNameField());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE);
        }

        private function renderNameField() {
            $name_value = null;
            if (isset($this->_current_term)) {
                $name_value = $this->_current_term->getName();
            }
            $name_field = new TextField("name", $this->getTextResource("articles_terms_editor_name_field"), $name_value, true, false, null);
            return $name_field->render();
        }
    }
