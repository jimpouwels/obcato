<?php

namespace Obcato\Core\view\views;

use Obcato\Core\database\dao\ArticleDao;
use Obcato\Core\database\dao\ArticleDaoMysql;
use Obcato\Core\view\TemplateEngine;

class ArticlePicker extends ObjectPicker {

    private ArticleDao $articleDao;

    public function __construct(string $name, string $labelResourceIdentifier, ?string $value, string $opener_click_id) {
        parent::__construct($name, $labelResourceIdentifier, $value, $opener_click_id);
        $this->articleDao = ArticleDaoMysql::getInstance();
    }

    public function getType(): string {
        return Search::$ARTICLES;
    }

    protected function shouldShowButton(): bool {
        // Only show button if no article is selected
        return empty($this->getValue());
    }

    protected function renderEnhancedDisplay(): string {
        $articleId = $this->getValue();
        if (empty($articleId)) {
            return "";
        }

        $article = $this->articleDao->getArticle((int)$articleId);
        if (!$article) {
            error_log("ArticlePicker: Could not load article with ID: " . $articleId);
            return "";
        }

        $templateEngine = TemplateEngine::getInstance();
        $templateEngine->assign("article_id", $articleId);
        $templateEngine->assign("article_title", $article->getTitle());
        $templateEngine->assign("article_url", $this->getBackendBaseUrl() . "&article=" . $articleId);
        $templateEngine->assign("field_name", $this->getFieldName());
        $templateEngine->assign("delete_field_name", "delete_" . $this->getFieldName());
        $templateEngine->assign("delete_icon_url", $this->getBackendBaseUrlRaw() . "?file=/default/img/default_icons/delete_small.png");
        
        return $templateEngine->fetch("article_picker_enhanced.tpl");
    }

}