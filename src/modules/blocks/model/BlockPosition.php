<?php

namespace Obcato\Core\modules\blocks\model;

use Obcato\Core\core\model\Entity;

class BlockPosition extends Entity {

    private string $name = "";
    private string $explanation = "";

    public static function constructFromRecord(array $row): BlockPosition {
        $position = new BlockPosition();
        $position->initFromDb($row);
        return $position;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName($name): void {
        $this->name = $name;
    }

    public function getExplanation(): string {
        return $this->explanation;
    }

    public function setExplanation(string $explanation): void {
        $this->explanation = $explanation;
    }

    protected function initFromDb(array $row): void {
        $this->setName($row['name']);
        $this->setExplanation($row['explanation']);
        parent::initFromDb($row);
    }

}