<?php

namespace Obcato\Core\admin\modules\articles\visuals\terms;

use Obcato\ComponentApi\TemplateData;
use Obcato\Core\admin\modules\articles\model\ArticleTerm;
use Obcato\Core\admin\view\views\Panel;
use Obcato\Core\admin\view\views\TextField;

class TermEditor extends Panel {

    private ArticleTerm $currentTerm;

    public function __construct(ArticleTerm $currentTerm) {
        parent::__construct($this->getTextResource("articles_terms_editor_title"), 'term_editor_panel');
        $this->currentTerm = $currentTerm;
    }

    public function getPanelContentTemplate(): string {
        return "modules/articles/terms/editor.tpl";
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
