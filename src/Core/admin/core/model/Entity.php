<?php

namespace Obcato\Core\admin\core\model;

abstract class Entity {

    private ?int $_id = null;

    public function getId(): ?int {
        return $this->_id;
    }

    public function setId(?int $id): void {
        $this->_id = $id;
    }

    protected function initFromDb(array $row): void {
        $this->setId($row['id']);
    }

}