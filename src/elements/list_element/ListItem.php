<?php

namespace Obcato\Core\elements\list_element;

use Obcato\Core\core\model\Entity;

class ListItem extends Entity {

    private ?string $text = null;
    private int $ident = 0;
    private int $elementId;
    private int $orderNr = 0;

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

    public function getOrderNr(): int {
        return $this->orderNr;
    }

    public function setOrderNr(int $orderNr): void {
        $this->orderNr = $orderNr;
    }

}