<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element.php";
    require_once CMS_ROOT . "core/model/element_metadata_provider.php";
    require_once CMS_ROOT . "elements/text_element/visuals/text_element_editor.php";
    require_once CMS_ROOT . "elements/text_element/visuals/text_element_statics.php";
    require_once CMS_ROOT . "elements/text_element/text_element_request_handler.php";
    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "frontend/text_element_visual.php";

    class TextElement extends Element {
            
        private ?string $_text = null;
            
        public function __construct(int $scope_id) {
            parent::__construct($scope_id, new TextElementMetadataProvider($this));
        }
        
        public function setText(?string $text): void {
            $this->_text = $text;
        }
        
        public function getText(): ?string {
            return $this->_text;
        }
        
        public function getStatics(): Visual {
            return new TextElementStatics();
        }
        
        public function getBackendVisual(): ElementVisual {
            return new TextElementEditorVisual($this);
        }

        public function getFrontendVisual(Page $page, ?Article $article): FrontendVisual {
            return new TextElementFrontendVisual($page, $article, $this);
        }
        
        public function getRequestHandler(): HttpRequestHandler {
            return new TextElementRequestHandler($this);
        }

        public function getSummaryText(): string {
            $summary_text = $this->getTitle();
            $summary_text .= ' (\'' . substr($this->getText(), 0, 50) . '...\')';
            return $summary_text;
        }
        
    }
    
    class TextElementMetadataProvider extends ElementMetadataProvider {
        
        private MysqlConnector $_mysql_connector;

        public function __construct(TextElement $text_element) {
            parent::__construct($text_element);
            $this->_mysql_connector = MysqlConnector::getInstance();
        }

        public function getTableName(): string {
            return "text_elements_metadata";
        }

        public function constructMetaData(array $row, $element): void {
            $element->setTitle($row['title']);
            $element->setText($row['text']);
        }

        public function update(Element $element): void {
            $title = $element->getTitle();
            $text = $element->getText();
            $element_id = $element->getId();
            $query = "UPDATE text_elements_metadata SET title = ?, text = ? WHERE element_id = ?";
            $statement = $this->_mysql_connector->prepareStatement($query);
            $statement->bind_param('ssi', $title, $text, $element_id);
            $this->_mysql_connector->executeStatement($statement);  
        }

        public function insert(Element $element): void {
            $title = $element->getTitle();
            $text = $element->getText();
            $element_id = $element->getId();
            $query = "INSERT INTO text_elements_metadata (title, `text`, element_id) VALUES (?, ?, ?)"; 
            $statement = $this->_mysql_connector->prepareStatement($query);
            $statement->bind_param('ssi', $title, $text, $element_id);
            $this->_mysql_connector->executeStatement($statement);         
        }
        
    }
    
?>