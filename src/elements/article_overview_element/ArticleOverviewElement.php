<?php

namespace Obcato\Core\elements\article_overview_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\elements\article_overview_element\visuals\ArticleOverviewElementEditor;
use Obcato\Core\elements\article_overview_element\visuals\ArticleOverviewElementStatics;
use Obcato\Core\frontend\ArticleOverviewElementFrontendVisual;
use Obcato\Core\frontend\FrontendVisual;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\articles\model\ArticleTerm;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\request_handlers\HttpRequestHandler;
use Obcato\Core\utilities\DateUtility;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\Visual;

class ArticleOverviewElement extends Element {

    private ?string $showFrom = null;
    private ?string $showTo = null;
    private bool $showUntilToday = false;
    private ?string $orderBy = null;
    private ?string $orderType = null;
    private array $terms;
    private ?int $numberOfResults = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new ArticleOverviewElementMetadataProvider($this));
        $this->terms = array();
    }

    public function setShowFrom(?string $show_from): void {
        $this->showFrom = $show_from;
    }

    public function getShowFrom(): ?string {
        return $this->showFrom;
    }

    public function setShowTo(?string $show_to): void {
        $this->showTo = $show_to;
    }

    public function getShowTo(): ?string {
        return $this->showTo;
    }

    public function setShowUntilToday(bool $show_until_today): void {
        $this->showUntilToday = $show_until_today;
    }

    public function getShowUntilToday(): bool {
        return $this->showUntilToday;
    }

    public function setNumberOfResults(?int $number_of_results): void {
        $this->numberOfResults = $number_of_results;
    }

    public function getNumberOfResults(): ?int {
        return $this->numberOfResults;
    }

    public function setOrderBy(?string $order_by): void {
        $this->orderBy = $order_by;
    }

    public function getOrderBy(): ?string {
        return $this->orderBy;
    }

    public function setOrderType(?string $order_type): void {
        $this->orderType = $order_type;
    }

    public function getOrderType(): ?string {
        return $this->orderType;
    }

    public function addTerm(ArticleTerm $term): void {
        $this->terms[] = $term;
    }

    public function removeTerm(ArticleTerm $term): void {
        if (($key = array_search($term, $this->terms, true)) !== false) {
            unset($this->terms[$key]);
        }
    }

    public function setTerms(array $terms): void {
        $this->terms = $terms;
    }

    public function getTerms(): array {
        return $this->terms;
    }

    public function getArticles(?int $exclude): array {
        $articleDao = ArticleDaoMysql::getInstance();
        $showTo = null;
        if ($this->showUntilToday != 1 && $this->showTo) {
            $showTo = DateUtility::mysqlDateToString($this->showTo, '-');
        }
        $showFrom = null;
        if ($this->showFrom) {
            $showFrom = DateUtility::mysqlDateToString($this->showFrom, '-');
        }
        return $articleDao->searchPublishedArticles($showFrom,
            $showTo, $this->orderBy, $this->getOrderType(), $this->terms,
            $this->numberOfResults, $exclude);
    }

    public function getStatics(): Visual {
        return new ArticleOverviewElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new ArticleOverviewElementEditor($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article, ?Block $block = null): FrontendVisual {
        return new ArticleOverviewElementFrontendVisual($page, $article, $block, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new ArticleOverviewElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        $summary_text = $this->getTitle() ?? "";
        if ($this->getTerms()) {
            $summary_text .= " (Termen:";
            foreach ($this->getTerms() as $term) {
                $summary_text .= " " . $term->getName();
            }
            $summary_text .= ")";
        }
        return $summary_text;
    }

}

