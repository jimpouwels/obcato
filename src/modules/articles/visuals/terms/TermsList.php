<?php

namespace Pageflow\Core\modules\articles\visuals\terms;

use Pageflow\Core\database\dao\ArticleDao;
use Pageflow\Core\database\dao\ArticleDaoMysql;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\InformationMessage;
use Pageflow\Core\view\views\Panel;
use Pageflow\Core\view\views\SingleCheckbox;

class TermsList extends Panel {

    private ArticleDao $articleDao;
    private ?\Pageflow\Core\modules\articles\model\ArticleTerm $currentTerm;

    public function __construct(?\Pageflow\Core\modules\articles\model\ArticleTerm $currentTerm = null) {
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
