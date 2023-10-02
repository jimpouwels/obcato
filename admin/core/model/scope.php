<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/entity.php";

class Scope extends Entity {

    private string $_identifier;

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
        return $this->_identifier;
    }

    public function setIdentifier(string $identifier): void {
        $this->_identifier = $identifier;
    }

}