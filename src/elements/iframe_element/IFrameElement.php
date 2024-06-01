<?php

namespace Obcato\Core\elements\iframe_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\core\model\ElementMetadataProvider;
use Obcato\Core\database\MysqlConnector;
use Obcato\Core\elements\iframe_element\visuals\IFrameElementEditor;
use Obcato\Core\elements\iframe_element\visuals\IFrameElementStatics;
use Obcato\Core\frontend\IFrameElementFrontendVisual;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\request_handlers\HttpRequestHandler;
use Obcato\Core\view\TemplateEngine;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\Visual;

class IFrameElement extends Element {

    private ?string $url = null;
    private ?int $width = null;
    private ?int $height = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new IFrameElementMetadataProvider($this));
    }

    public function setUrl(?string $url): void {
        $this->url = $url;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function getWidth(): ?int {
        return $this->width;
    }

    public function setWidth(?int $width): void {
        $this->width = $width;
    }

    public function getHeight(): ?int {
        return $this->height;
    }

    public function setHeight(?int $height): void {
        $this->height = $height;
    }

    public function getStatics(): Visual {
        return new IFrameElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new IFrameElementEditor($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article): IFrameElementFrontendVisual {
        return new IFrameElementFrontendVisual($page, $article, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new IFrameElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        return "IFrame";
    }
}

