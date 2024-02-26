<?php

namespace Obcato\Core\modules\templates\model;

use Obcato\Core\core\model\Entity;

class Scope extends Entity {

    private string $identifier;

    public static function constructFromRecord(array $row): Scope {
        $scope = new Scope();
        $scope->initFromDb($row);
        return $scope;
    }

    protected function initFromDb(array $row): void {
        $this->setIdentifier($row['identifier']);
        parent::initFromDb($row);
    }

    public function getIdentifier(): string {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void {
        $this->identifier = $identifier;
    }

}