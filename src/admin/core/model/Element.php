<?php

namespace Obcato\Core\admin\core\model;

use Obcato\ComponentApi\Visual;
use Obcato\Core\Article;
use Obcato\Core\ElementVisual;
use Obcato\Core\FrontendVisual;
use Obcato\Core\HttpRequestHandler;
use Obcato\Core\Page;
use Obcato\Core\Presentable;
use const Obcato\Core\CMS_ROOT;

abstract class Element extends Presentable {

    private ?string $title = null;
    private int $elementHolderId;
    private int $orderNr;
    private bool $includeInTableOfContents = false;
    private ElementMetadataProvider $metadataProvider;

    public function __construct(int $scopeId, ElementMetadataProvider $metadataProvider) {
        parent::__construct($scopeId);
        $this->metadataProvider = $metadataProvider;
    }

    public static function constructFromRecord(array $record): Element {
        require_once CMS_ROOT . '/elements/' . $record['identifier'] . '/' . $record['domain_object'];

        // first get the element type
        $elementType = $record['classname'];

        // the constructor for each type will initialize specific metadata
        $element = new $elementType($record["scope_id"]);

        $element->setId($record['id']);
        $element->setOrderNr($record['follow_up']);
        $element->setTemplateId($record['template_id']);
        $element->setIncludeInTableOfContents($record['include_in_table_of_contents'] == 1);
        $element->setElementHolderId($record['element_holder_id']);

        $element->initializeMetaData();
        return $element;
    }

    public function setIncludeInTableOfContents(bool $includeInTableOfContents): void {
        $this->includeInTableOfContents = $includeInTableOfContents;
    }

    public function initializeMetaData(): void {
        $this->metadataProvider->loadMetaData();
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(?string $title): void {
        $this->title = $title;
    }

    public function getOrderNr(): int {
        return $this->orderNr;
    }

    public function setOrderNr(int $order_nr): void {
        $this->orderNr = $order_nr;
    }

    public function includeInTableOfContents(): bool {
        return $this->includeInTableOfContents;
    }

    public function getElementHolderId(): int {
        return $this->elementHolderId;
    }

    public function setElementHolderId(int $element_holder_id): void {
        $this->elementHolderId = $element_holder_id;
    }

    public function updateMetaData(): void {
        $this->metadataProvider->upsert($this);
    }

    public abstract function getStatics(): Visual;

    public abstract function getBackendVisual(): ElementVisual;

    public abstract function getFrontendVisual(Page $page, ?Article $article): FrontendVisual;

    public abstract function getRequestHandler(): HttpRequestHandler;

    public abstract function getSummaryText(): string;

    protected function getMetaDataProvider(): ElementMetadataProvider {
        return $this->metadataProvider;
    }
}
