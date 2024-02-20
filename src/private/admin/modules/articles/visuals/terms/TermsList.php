<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;

class TermsList extends Panel {

    private ArticleDao $articleDao;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine, 'Termen', 'term_list_panel');
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/articles/terms/list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("all_terms", $this->getAllTerms());
        $noTermsMessage = new InformationMessage($this->getTemplateEngine(), "Geen termen gevonden");
        $data->assign("no_terms_message", $noTermsMessage->render());
    }

    private function getAllTerms(): array {
        $allTermsValues = array();
        $allTerms = $this->articleDao->getAllTerms();

        foreach ($allTerms as $term) {
            $termValue = array();
            $termValue["id"] = $term->getId();
            $termValue["name"] = $term->getName();
            $deleteField = new SingleCheckbox($this->getTemplateEngine(), "term_" . $term->getId() . "_delete", "", false, false, "");
            $termValue["delete_field"] = $deleteField->render();

            $allTermsValues[] = $termValue;
        }
        return $allTermsValues;
    }
}
