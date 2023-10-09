<?php

class TermEditor extends Panel {

    private ArticleTerm $currentTerm;

    public function __construct(ArticleTerm $currentTerm) {
        parent::__construct($this->getTextResource("articles_terms_editor_title"), 'term_editor_panel');
        $this->currentTerm = $currentTerm;
    }

    public function getPanelContentTemplate(): string {
        return "modules/articles/terms/editor.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("id", $this->currentTerm->getId());
        $data->assign("name_field", $this->renderNameField());
    }

    private function renderNameField(): string {
        $nameValue = $this->currentTerm->getName();
        $nameField = new TextField("name", $this->getTextResource("articles_terms_editor_name_field"), $nameValue, true, false, null);
        return $nameField->render();
    }
}
