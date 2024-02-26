<?php

namespace Obcato\Core\modules\images\model;

use Obcato\Core\core\model\Entity;

class ImageLabel extends Entity {

    private string $name;

    public static function constructFromRecord(array $row): ImageLabel {
        $label = new ImageLabel();
        $label->initFromDb($row);
        return $label;
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