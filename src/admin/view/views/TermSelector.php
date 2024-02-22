<?php

namespace Obcato\Core\admin\view\views;

use Obcato\Core\admin\database\dao\ArticleDao;
use Obcato\Core\admin\database\dao\ArticleDaoMysql;
use Obcato\Core\admin\view\TemplateData;

class TermSelector extends Panel {

    private array $selectedTerms;
    private ArticleDao $articleDao;
    private int $contextId;

    public function __construct(array $selected_terms, int $contextId) {
        parent::__construct($this->getTextResource("term_selector_title"), 'term_selector');
        $this->selectedTerms = $selected_terms;
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->contextId = $contextId;
    }

    public function getPanelContentTemplate(): string {
        return "system/term_selector.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("terms_to_select", $this->getTermsToSelect());
        $data->assign("selected_terms", $this->getSelectedTermsHtml());
        $data->assign("context_id", $this->contextId);

        $data->assign("label_selected_terms", $this->getTextResource("term_selector_label_selected_terms"));
        $data->assign("label_delete_selected_term", $this->getTextResource("term_selector_label_delete_selected_term"));
        $data->assign("message_no_selected_terms", $this->getTextResource("term_selector_message_no_terms_selected"));
    }

    private function getTermsToSelect(): array {
        $termsToSelect = array();
        foreach ($this->articleDao->getAllTerms() as $term) {
            if (!in_array($term, $this->selectedTerms)) {
                $termToSelect['id'] = $term->getId();
                $termToSelect['name'] = $term->getName();
                $termsToSelect[] = $termToSelect;
            }
        }
        return $termsToSelect;
    }

    private function getSelectedTermsHtml(): array {
        $selectedTerms = array();
        foreach ($this->selectedTerms as $selectedTerm) {
            $selectedTermItem = array();
            $selectedTermItem['name'] = $selectedTerm->getName();
            $deleteField = new SingleCheckbox("term_" . $this->contextId . "_" . $selectedTerm->getId() . "_delete", "", false, false, "");
            $selectedTermItem['delete_field'] = $deleteField->render();
            $selectedTerms[] = $selectedTermItem;
        }
        return $selectedTerms;
    }

}
