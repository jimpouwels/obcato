<?php
require_once CMS_ROOT . "/core/model/Element.php";
require_once CMS_ROOT . "/core/model/ElementMetadataProvider.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/elements/list_element/ListItem.php";
require_once CMS_ROOT . "/elements/list_element/visuals/ListElementStatics.php";
require_once CMS_ROOT . "/elements/list_element/visuals/ListElementEditor.php";
require_once CMS_ROOT . "/frontend/ListElementFrontendVisual.php";
require_once CMS_ROOT . "/elements/list_element/ListElementRequestHandler.php";

class ListElement extends Element {

    private array $_list_items = array();

    public function __construct(int $scopeId) {
        parent::__construct($scopeId, new ListElementMetaDataProvider($this));
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
        $this->_list_items[] = $list_item;
    }

    public function deleteListItem(ListItem $list_item_to_delete): void {
        $this->_list_items = array_filter($this->_list_items, function ($list_item) use ($list_item_to_delete) {
            return $list_item->getId() !== $list_item_to_delete->getId();
        });
        $this->getMetaDataProvider()->deleteListItem($this, $list_item_to_delete);
    }

    public function getStatics(): Visual {
        return new ListElementStatics();
    }

    public function getBackendVisual(): ElementVisual {
        return new ListElementEditor($this);
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

    private static string $myAllColumns = "i.id, i.text, i.indent, i.element_id";
    private MysqlConnector $_mysql_connector;

    public function __construct(Element $element) {
        parent::__construct($element);
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public function getTableName(): string {
        return "list_elements_metadata";
    }

    public function constructMetaData(array $record, $element): void {
        $element->setTitle($record['title']);
        $element->setListItems($this->getListItems($element));
    }

    public function deleteListItem(Element $element, ListItem $list_item): void {
        $query = "DELETE FROM list_element_items WHERE id = " . $list_item->getId();
        $this->_mysql_connector->executeQuery($query);
    }

    public function update(Element $element): void {
        $query = "UPDATE list_elements_metadata SET title = '" . $element->getTitle() . "' WHERE element_id = " . $element->getId();
        $this->_mysql_connector->executeQuery($query);

        $this->storeItems($element->getListItems());
    }

    public function insert(Element $element): void {
        $query = "INSERT INTO list_elements_metadata (title, element_id) VALUES 
                        ('" . $element->getTitle() . "', " . $element->getId() . ")";
        $this->_mysql_connector->executeQuery($query);
    }

    private function storeItems(array $items): void {
        foreach ($items as $list_item) {
            $statement = null;
            $id = $list_item->getId();
            $indent = $list_item->getIndent();
            $text = $list_item->getText();
            $element_id = $this->getElement()->getId();
            if (!is_null($list_item->getId())) {
                $query = "UPDATE list_element_items SET `text` = ?, indent = ? WHERE id = ?";
                $statement = $this->_mysql_connector->prepareStatement($query);
                $statement->bind_param('sii', $text, $indent, $id);
            } else {
                $query = "INSERT INTO list_element_items (`text`, indent, element_id) VALUES ('', ?, ?)";
                $statement = $this->_mysql_connector->prepareStatement($query);
                $statement->bind_param('ii', $indent, $element_id);
            }
            $this->_mysql_connector->executeStatement($statement);
        }
    }

    private function getListItems(Element $element): array {
        $query = "SELECT " . self::$myAllColumns . " FROM list_element_items i, elements e WHERE i.element_id = " . $element->getId() .
            " AND e.id = " . $element->getId();
        $result = $this->_mysql_connector->executeQuery($query);
        $list_items = array();
        while ($row = $result->fetch_assoc()) {
            $list_item = new ListItem();
            $list_item->setId($row['id']);
            $list_item->setText($row['text']);
            $list_item->setIndent($row['indent']);
            $list_item->setElementId($row['element_id']);

            $list_items[] = $list_item;
        }

        return $list_items;
    }

}

?>