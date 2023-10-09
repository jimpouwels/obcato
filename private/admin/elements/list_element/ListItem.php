<?php
require_once CMS_ROOT . "/core/model/Entity.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";

class ListItem extends Entity {

    private ?string $text = null;
    private int $ident = 0;
    private int $elementId;

    public function getText(): ?string {
        return $this->text;
    }

    public function setText(?string $text): void {
        $this->text = $text;
    }

    public function getIndent(): int {
        return $this->ident;
    }

    public function setIndent(int $indent): void {
        $this->ident = $indent;
    }

    public function getElementId(): int {
        return $this->elementId;
    }

    public function setElementId(int $elementId): void {
        $this->elementId = $elementId;
    }

    public function getElement(): Element {
        $elementDao = ElementDaoMysql::getInstance();
        return $elementDao->getElement($this->elementId);
    }

}

?>