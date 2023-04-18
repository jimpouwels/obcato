<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/presentable.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "database/dao/element_holder_dao.php";

    abstract class Element extends Presentable {

        private int $_index;
        private string $_title = "";
        private int $_element_holder_id;
        private bool $_include_in_table_of_contents;
        private ElementMetadataProvider $_metadata_provider;

        public function __construct(ElementMetadataProvider $metadata_provider) {
            $this->_metadata_provider = $metadata_provider;
        }

        public function setIndex(int $index): void {
            $this->_index = $index;
        }

        public function getIndex(): int {
            return $this->_index;
        }

        public function setTitle(string $title): void {
            $this->_title = $title;
        }

        public function getTitle(): string {
            return $this->_title;
        }

        public function getType(): ElementType {
            $element_dao = ElementDao::getInstance();
            return $element_dao->getElementTypeForElement($this->getId());
        }

        public function setIncludeInTableOfContents(bool $_include_in_table_of_contents): void {
            $this->_include_in_table_of_contents = $_include_in_table_of_contents;
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

        public function getElementHolder(): ElementHolder {
            $element_holder_dao = ElementHolderDao::getInstance();
            return $element_holder_dao->getElementHolder($this->_element_holder_id);
        }

        public function delete(): void {
            $element_dao = ElementDao::getInstance();
            $element_dao->deleteElement($this);
        }

        public function initializeMetaData(): void {
            $this->_metadata_provider->loadMetaData();
        }

        public function updateMetaData(): void {
            $this->_metadata_provider->updateMetaData($this);
        }

        public static function constructFromRecord(array $record): Element {
            include_once CMS_ROOT . 'elements/' . $record['identifier'] . '/' . $record['domain_object'];

            // first get the element type
            $element_type = $record['classname'];

            // the constructor for each type will initialize specific metadata
            $element = new $element_type;

            $element->setId($record['id']);
            $element->setIndex($record['follow_up']);
            $element->setTemplateId($record['template_id']);
            $element->setIncludeInTableOfContents($record['include_in_table_of_contents'] == 1 ? true : false);
            $element->setElementHolderId($record['element_holder_id']);

            // initialize element specific metadata
            $element->initializeMetaData();

            return $element;
        }

        protected function getMetaDataProvider(): ElementMetadataProvider {
            return $this->_metadata_provider;
        }

        public abstract function getStatics(): Visual;
        
        public abstract function getBackendVisual(): ElementVisual;
        
        public abstract function getFrontendVisual(Page $current_page): FrontendVisual;
        
        public abstract function getRequestHandler(): HttpRequestHandler;
        
        public abstract function getSummaryText(): string;
    }
