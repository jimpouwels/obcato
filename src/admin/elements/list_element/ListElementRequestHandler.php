<?php

namespace Obcato\Core\admin\elements\list_element;

use Obcato\Core\admin\database\dao\ElementDao;
use Obcato\Core\admin\database\dao\ElementDaoMysql;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;

class ListElementRequestHandler extends HttpRequestHandler {

    private ListElement $listElement;
    private ListElementForm $listElementForm;
    private ElementDao $elementDao;

    public function __construct($listElement) {
        $this->listElement = $listElement;
        $this->listElementForm = new ListElementForm($this->listElement);
        $this->elementDao = ElementDaoMysql::getInstance();
    }

    public function handleGet(): void {}

    public function handlePost(): void {
        $this->listElementForm->loadFields();
        foreach ($this->listElementForm->getListItemsToDelete() as $list_item_to_delete) {
            $this->listElement->deleteListItem($list_item_to_delete);
        }
        if ($this->isAddListItemAction()) {
            $this->listElement->addListItem();
        }
        $this->elementDao->updateElement($this->listElement);
    }

    private function isAddListItemAction(): bool {
        return isset($_POST['element' . $this->listElement->getId() . '_add_item']) &&
            $_POST['element' . $this->listElement->getId() . '_add_item'] != '';
    }
}