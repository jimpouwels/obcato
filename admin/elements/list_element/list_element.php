<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element.php";
    require_once CMS_ROOT . "core/model/element_metadata_provider.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "elements/list_element/list_item.php";
    require_once CMS_ROOT . "elements/list_element/visuals/list_element_statics.php";
    require_once CMS_ROOT . "elements/list_element/visuals/list_element_editor.php";
    require_once CMS_ROOT . "frontend/list_element_visual.php";
    require_once CMS_ROOT . "elements/list_element/list_element_request_handler.php";

    class ListElement extends Element {

        private array $_list_items = array();
            
        public function __construct(int $scope_id) {
            parent::__construct($scope_id, new ListElementMetaDataProvider($this));
        }
        
        public function getListItems(): array {
            return $this->_list_items;
        }
        
        public function setListItems(array $list_items): void {
            $this->_list_items = $list_items;
        }
        
        public function addListItem(): void {
            $list_item = new ListItem();
            $list_item->setIndent(0);
            array_push($this->_list_items, $list_item);
        }
        
        public function deleteListItem(ListItem $list_item): void {
            $this->getMetaDataProvider()->deleteListItem($this, $list_item);
        }
        
        public function getStatics(): Visual {
            return new ListElementStatics();
        }
        
        public function getBackendVisual(): ElementVisual {
            return new ListElementEditorVisual($this);
        }

        public function getFrontendVisual(Page $page, ?Article $article): FrontendVisual {
            return new ListElementFrontendVisual($page, $article, $this);
        }
        
        public function getRequestHandler(): HttpRequestHandler {
            return new ListElementRequestHandler($this);
        }

        public function getSummaryText(): string {
            return $this->getTitle() || '';            
        }
    }
    
    class ListElementMetaDataProvider extends ElementMetadataProvider {
        
        // Holds the list of columns that are to be collected
        private static string $myAllColumns = "i.id, i.text, i.indent, i.element_id";
        private MysqlConnector $_mysql_database;

        public function __construct(Element $element) {
            parent::__construct($element);
            $this->_mysql_database = MysqlConnector::getInstance();
        }
        
        public function getTableName(): string {
            return "list_elements_metadata";
        }

        public function constructMetaData(array $row, $element): void {
            $element->setTitle($row['title']);
            $element->setListItems($this->getListItems($element));
        }
        
        public function deleteListItem(Element $element, ListItem $list_item): void {
            // first delete it from the database
            
            $query = "DELETE FROM list_element_items WHERE id = " . $list_item->getId();
            $this->_mysql_database->executeQuery($query);
        }
        
        public function update(Element $element): void {
            // first handle default metadata
            $query = "UPDATE list_elements_metadata SET title = '" . $element->getTitle() . "' WHERE element_id = " . $element->getId();    
            $this->_mysql_database->executeQuery($query);    
            
            $this->storeItems($element->getListItems());
        }
        
        public function insert(Element $element): void {
            // first handle default metadata
            $query = "INSERT INTO list_elements_metadata (title, element_id) VALUES 
                        ('" . $element->getTitle() . "', " . $element->getId() . ")"; 
            dumpVar($query);
            $this->_mysql_database->executeQuery($query);    
        }

        private function storeItems(array $items): void {
            // now handle the list items
            if (!is_null($items)) {
                $query = "";
                foreach ($items as $list_item) {
                    if (!is_null($list_item->getId())) {
                        $query = "UPDATE list_element_items SET `text` = '" . $list_item->getText() . "', indent = " . $list_item->getIndent() . "
                                WHERE id = " . $list_item->getId();
                    } else {
                        $query = "INSERT INTO list_element_items (`text`, indent, element_id) VALUES
                            ('', " . $list_item->getIndent() . ", " . $this->getElement()->getId() . ")";
                    }
                    $this->_mysql_database->executeQuery($query);
                }
            }
        }
        
        // returns all list items
        private function getListItems(Element $element): array {
            $query = "SELECT " . self::$myAllColumns . " FROM list_element_items i, elements e WHERE i.element_id = " . $element->getId() .
                      " AND e.id = " . $element->getId();
            $result = $this->_mysql_database->executeQuery($query);
            $list_items = array();
            $list_item = NULL;
            dumpVar($query);
            while ($row = $result->fetch_assoc()) {
                $list_item = new ListItem();
                $list_item->setId($row['id']);
                $list_item->setText($row['text']);
                $list_item->setIndent($row['indent']);
                $list_item->setElementId($row['element_id']);
                
                array_push($list_items, $list_item);
            }
            
            return $list_items;
        }
        
    }
    
?>