<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "core/model/presentable.php";
require_once CMS_ROOT . "database/dao/element_dao.php";
require_once CMS_ROOT . "database/dao/element_holder_dao.php";

abstract class Element extends Presentable {

    private ?string $_title = null;
    private int $_element_holder_id;
    private int $_order_nr;
    private bool $_include_in_table_of_contents;
    private ElementMetadataProvider $_metadata_provider;

    public function __construct(int $scope_id, ElementMetadataProvider $metadata_provider) {
        parent::__construct($scope_id);
        $this->_metadata_provider = $metadata_provider;
    }

    public static function constructFromRecord(array $record): Element {
        include_once CMS_ROOT . 'elements/' . $record['identifier'] . '/' . $record['domain_object'];

        // first get the element type
        $element_type = $record['classname'];

        // the constructor for each type will initialize specific metadata
        $element = new $element_type($record["scope_id"]);

        $element->setId($record['id']);
        $element->setOrderNr($record['follow_up']);
        $element->setTemplateId($record['template_id']);
        $element->setIncludeInTableOfContents($record['include_in_table_of_contents'] == 1 ? true : false);
        $element->setElementHolderId($record['element_holder_id']);

        $element->initializeMetaData();

        return $element;
    }

    public function setIncludeInTableOfContents(bool $_include_in_table_of_contents): void {
        $this->_include_in_table_of_contents = $_include_in_table_of_contents;
    }

    public function initializeMetaData(): void {
        $this->_metadata_provider->loadMetaData();
    }

    public function getTitle(): ?string {
        return $this->_title;
    }

    public function setTitle(?string $title): void {
        $this->_title = $title;
    }

    public function getOrderNr(): int {
        return $this->_order_nr;
    }

    public function setOrderNr(int $order_nr): void {
        $this->_order_nr = $order_nr;
    }

    public function getType(): ElementType {
        $element_dao = ElementDao::getInstance();
        return $element_dao->getElementTypeForElement($this->getId());
    }

    public function includeInTableOfContents(): bool {
        return $this->_include_in_table_of_contents;
    }

    public function getElementHolderId(): int {
        return $this->_element_holder_id;
    }

    public function setElementHolderId(int $element_holder_id): void {
        $this->_element_holder_id = $element_holder_id;
    }

    public function delete(): void {
        $element_dao = ElementDao::getInstance();
        $element_dao->deleteElement($this);
    }

    public function updateMetaData(): void {
        $this->_metadata_provider->upsert($this);
    }

    public abstract function getStatics(): Visual;

    public abstract function getBackendVisual(): ElementVisual;

    public abstract function getFrontendVisual(Page $page, ?Article $article): FrontendVisual;

    public abstract function getRequestHandler(): HttpRequestHandler;

    public abstract function getSummaryText(): string;

    protected function getMetaDataProvider(): ElementMetadataProvider {
        return $this->_metadata_provider;
    }
}
