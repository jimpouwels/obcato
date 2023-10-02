<?php
defined('_ACCESS') or die;

class TermEditor extends Panel {

    private ArticleTerm $_current_term;

    public function __construct(ArticleTerm $current_term) {
        parent::__construct($this->getTextResource("articles_terms_editor_title"), 'term_editor_panel');
        $this->_current_term = $current_term;
    }

    public function getPanelContentTemplate(): string {
        return "modules/articles/terms/editor.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("id", $this->_current_term->getId());
        $data->assign("name_field", $this->renderNameField());
    }

    private function renderNameField(): string {
        $name_value = null;
        if (isset($this->_current_term)) {
            $name_value = $this->_current_term->getName();
        }
        $name_field = new TextField("name", $this->getTextResource("articles_terms_editor_name_field"), $name_value, true, false, null);
        return $name_field->render();
    }
}
