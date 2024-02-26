<?php

namespace Obcato\Core\modules\articles\visuals\terms;

use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\InformationMessage;
use Obcato\Core\view\views\Panel;
use Obcato\Core\view\views\SingleCheckbox;

class TermsList extends Panel {

    private ArticleDao $articleDao;

    public function __construct() {
        parent::__construct('Termen', 'term_list_panel');
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/articles/terms/list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("all_terms", $this->getAllTerms());
        $noTermsMessage = new InformationMessage("Geen termen gevonden");
        $data->assign("no_terms_message", $noTermsMessage->render());
    }

    private function getAllTerms(): array {
        $allTermsValues = array();
        $allTerms = $this->articleDao->getAllTerms();

        foreach ($allTerms as $term) {
            $termValue = array();
            $termValue["id"] = $term->getId();
            $termValue["name"] = $term->getName();
            $deleteField = new SingleCheckbox("term_" . $term->getId() . "_delete", "", false, false, "");
            $termValue["delete_field"] = $deleteField->render();

            $allTermsValues[] = $termValue;
        }
        return $allTermsValues;
    }
}
