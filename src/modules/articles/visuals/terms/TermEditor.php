<?php

namespace Pageflow\Core\modules\articles\visuals\terms;

use Pageflow\Core\modules\articles\model\ArticleTerm;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\Panel;
use Pageflow\Core\view\views\TextField;

class TermEditor extends Panel {

    private ArticleTerm $currentTerm;

    public function __construct(ArticleTerm $currentTerm) {
        parent::__construct($this->getTextResource("articles_terms_editor_title"), 'term_editor_panel');
        $this->currentTerm = $currentTerm;
    }

    public function getPanelContentTemplate(): string {
        return "articles/templates/terms/editor.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("id", $this->currentTerm->getId());
        $data->assign("name_field", $this->renderNameField());
    }

    private function renderNameField(): string {
        $nameValue = $this->currentTerm->getName();
        $nameField = new TextField("name", $this->getTextResource("articles_terms_editor_name_field"), $nameValue, true, false, null);
        return $nameField->render();
    }
}
