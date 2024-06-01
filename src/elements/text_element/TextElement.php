<?php

namespace Obcato\Core\elements\text_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\MysqlConnector;
use Obcato\Core\elements\text_element\visuals\TextElementEditor;
use Obcato\Core\elements\text_element\visuals\TextElementStatics;
use Obcato\Core\frontend\FrontendVisual;
use Obcato\Core\frontend\TextElementFrontendVisual;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\request_handlers\HttpRequestHandler;
use Obcato\Core\view\TemplateEngine;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\Visual;

class TextElement extends Element {

    private ?string $text = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new TextElementMetadataProvider($this));
    }

    public function setText(?string $text): void {
        $this->text = $text;
    }

    public function getText(): ?string {
        return $this->text;
    }

    public function getStatics(): Visual {
        return new TextElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new TextElementEditor($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article): FrontendVisual {
        return new TextElementFrontendVisual($page, $article, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new TextElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        $summaryText = $this->getTitle();
        $summaryText .= ' (\'' . substr($this->getText(), 0, 50) . '...\')';
        return $summaryText;
    }

}

