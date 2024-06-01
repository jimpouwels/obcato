<?php

namespace Obcato\Core\elements\article_overview_element;

use Obcato\Core\core\form\FormException;
use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\database\dao\ElementDao;
use Obcato\Core\database\dao\ElementDaoMysql;
use Obcato\Core\elements\ElementContainsErrorsException;
use Obcato\Core\request_handlers\HttpRequestHandler;

class ArticleOverviewElementRequestHandler extends HttpRequestHandler {

    private ArticleOverviewElement $articleOverviewElement;
    private ElementDao $elementDao;
    private ArticleDao $articleDao;
    private ArticleOverviewElementForm $articleOverviewElementForm;

    public function __construct(ArticleOverviewElement $articleOverviewElement) {
        $this->articleOverviewElement = $articleOverviewElement;
        $this->elementDao = ElementDaoMysql::getInstance();
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->articleOverviewElementForm = new ArticleOverviewElementForm($articleOverviewElement);
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        try {
            $this->articleOverviewElementForm->loadFields();
            $this->removeSelectedTerms();
            $this->addSelectedTerms();
            $this->elementDao->updateElement($this->articleOverviewElement);
        } catch (FormException) {
            throw new ElementContainsErrorsException("Article overview element contains errors");
        }
    }

    private function addSelectedTerms(): void {
        $selectedTerms = $this->articleOverviewElementForm->getSelectedTerms();
        if ($selectedTerms) {
            foreach ($selectedTerms as $selected_term_id) {
                $term = $this->articleDao->getTerm($selected_term_id);
                $this->articleOverviewElement->addTerm($term);
            }
        }
    }

    private function removeSelectedTerms(): void {
        foreach ($this->articleOverviewElementForm->getTermsToRemove() as $term) {
            $this->articleOverviewElement->removeTerm($term);
        }
    }
}