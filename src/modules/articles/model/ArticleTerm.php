<?php

namespace Obcato\Core\modules\articles\model;

use Obcato\Core\core\model\Entity;

class ArticleTerm extends Entity {

    private string $name = "";

    public static function constructFromRecord(array $row): ArticleTerm {
        $term = new ArticleTerm();
        $term->initFromDb($row);
        return $term;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        parent::initFromDb($row);
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

}