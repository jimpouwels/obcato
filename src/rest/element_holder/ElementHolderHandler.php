<?php

namespace Obcato\Core\rest\element_holder;

use Obcato\Core\database\dao\ElementHolderDaoMysql;
use Obcato\Core\rest\Handler;
use Obcato\Core\rest\HttpMethod;

class ElementHolderHandler extends Handler {

    public function __construct() {
        $this->register(HttpMethod::GET, "/element-holder/version", $this->getVersion(...));
    }

    public function getVersion(): array {
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            return [];
        }
        $holder = ElementHolderDaoMysql::getInstance()->getElementHolder((int)$id);
        if (!$holder) {
            return [];
        }
        return ['id' => $holder->getId(), 'version' => $holder->getVersion()];
    }

}
