<?php

namespace Pageflow\Core\rest\element_holder;

use Pageflow\Core\database\dao\ElementHolderDaoMysql;
use Pageflow\Core\rest\Handler;
use Pageflow\Core\rest\HttpMethod;

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
