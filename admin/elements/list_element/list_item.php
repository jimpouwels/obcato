<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/Entity.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";

class ListItem extends Entity {

    private ?string $_text = null;
    private int $_indent = 0;
    private int $_elementId;

    public function getText(): ?string {
        return $this->_text;
    }

    public function setText(?string $text): void {
        $this->_text = $text;
    }

    public function getIndent(): int {
        return $this->_indent;
    }

    public function setIndent(int $indent): void {
        $this->_indent = $indent;
    }

    public function getElementId(): int {
        return $this->_elementId;
    }

    public function setElementId(int $element_id): void {
        $this->_elementId = $element_id;
    }

    public function getElement(): Element {
        $element_dao = ElementDaoMysql::getInstance();
        return $element_dao->getElement($this->_elementId);
    }

}

?>