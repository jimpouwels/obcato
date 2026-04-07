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
    private ?\Obcato\Core\modules\articles\model\ArticleTerm $currentTerm;

    public function __construct(?\Obcato\Core\modules\articles\model\ArticleTerm $currentTerm = null) {
        parent::__construct('Termen', 'term_list');
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->currentTerm = $currentTerm;
    }

    public function getPanelContentTemplate(): string {
        return "articles/templates/terms/list.tpl";
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
            $termValue["is_active"] = $this->currentTerm && $this->currentTerm->getId() === $term->getId();

            $allTermsValues[] = $termValue;
        }
        return $allTermsValues;
    }
}
