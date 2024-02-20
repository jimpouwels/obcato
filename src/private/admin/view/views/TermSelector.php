<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

require_once CMS_ROOT . "/database/dao/BlockDaoMysql.php";

class TermSelector extends Panel {

    private array $selectedTerms;
    private ArticleDao $articleDao;
    private int $contextId;

    public function __construct(TemplateEngine $templateEngine, array $selected_terms, int $contextId) {
        parent::__construct($templateEngine, $this->getTextResource("term_selector_title"), 'term_selector');
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
            $deleteField = new SingleCheckbox($this->getTemplateEngine(), "term_" . $this->contextId . "_" . $selectedTerm->getId() . "_delete", "", false, false, "");
            $selectedTermItem['delete_field'] = $deleteField->render();
            $selectedTerms[] = $selectedTermItem;
        }
        return $selectedTerms;
    }

}
