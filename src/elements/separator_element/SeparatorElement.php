<?php

namespace Obcato\Core\elements\separator_element;

use Obcato\Core\core\model\Element;
use Obcato\Core\elements\separator_element\visuals\SeparatorElementEditor;
use Obcato\Core\elements\separator_element\visuals\SeparatorElementStatics;
use Obcato\Core\frontend\SeparatorElementFrontendVisual;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\request_handlers\HttpRequestHandler;
use Obcato\Core\view\views\ElementVisual;
use Obcato\Core\view\views\Visual;

class SeparatorElement extends Element {

    private ?string $url = null;
    private ?int $width = null;
    private ?int $height = null;

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new SeparatorElementMetadataProvider($this));
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
        return new SeparatorElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new SeparatorElementEditor($this);
    }

    public function getFrontendVisual(Page $page, ?Article $article, ?Block $block = null): SeparatorElementFrontendVisual {
        return new SeparatorElementFrontendVisual($page, $article, $block, $this);
    }

    public function getRequestHandler(): HttpRequestHandler {
        return new SeparatorElementRequestHandler($this);
    }

    public function getSummaryText(): string {
        return "Separator";
    }
}

