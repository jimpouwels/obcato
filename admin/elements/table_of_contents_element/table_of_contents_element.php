<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element.php";
    require_once CMS_ROOT . "core/model/element_metadata_provider.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "elements/table_of_contents_element/visuals/table_of_contents_element_statics.php";
    require_once CMS_ROOT . "elements/table_of_contents_element/visuals/table_of_contents_element_editor.php";
    require_once CMS_ROOT . "elements/table_of_contents_element/table_of_contents_element_request_handler.php";
    require_once CMS_ROOT . "frontend/table_of_contents_element_visual.php";

    class TableOfContentsElement extends Element {
            
        public function __construct(int $scope_id) {
            parent::__construct($scope_id, new TableOfContentsElementMetadataProvider($this));
        }
        
        public function getStatics(): Visual {
            return new TableOfContentsElementStatics();
        }
        
        public function getBackendVisual(): ElementVisual {
            return new TableOfContentsElementEditor($this);
        }

        public function getFrontendVisual(Page $page, ?Article $article): FrontendVisual {
            return new TableOfContentsElementFrontendVisual($page, $article, $this);
        }
        
        public function getRequestHandler(): HttpRequestHandler {
            return new TableOfContentsElementRequestHandler($this);
        }

        public function getSummaryText(): string {
            return $this->getTitle();
        }

    }
    
    class TableOfContentsElementMetadataProvider extends ElementMetadataProvider {

        private $_element;

        public function __construct(TableOfContentsElement $element) {
            parent::__construct($element);
            $this->_element = $element;
        }

        public function getTableName(): string {
            return "table_of_contents_elements_metadata";
        }

        public function constructMetaData(array $row, $element): void {
            $element->setTitle($row['title']);
        }

        public function updateMetaData(Element $element): void {
            $mysql_database = MysqlConnector::getInstance(); 
            
            if ($this->metaDataPersisted($element)) {
                $query = "UPDATE table_of_contents_elements_metadata SET title = '" . $element->getTitle() . "'";
            } else {
                $query = "INSERT INTO table_of_contents_elements_metadata (title, element_id) VALUES ('" . $element->getTitle() . "', " . $element->getId() . ")";
            }
            $mysql_database->executeQuery($query);
        }

        private function metaDataPersisted(Element $element): bool {
            $query = "SELECT t.id, e.id FROM table_of_contents_elements_metadata t, elements e WHERE t.element_id = " 
                    . $element->getId() . " AND e.id = " . $element->getId();
            $mysql_database = MysqlConnector::getInstance(); 
            $result = $mysql_database->executeQuery($query);
            while ($row = $result->fetch_assoc())
                return true;
            return false;
        }
        
    }
    
?>