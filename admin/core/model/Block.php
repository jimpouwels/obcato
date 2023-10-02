<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/ElementHolder.php";

class Block extends ElementHolder {

    private static int $SCOPE = 6;
    private ?int $_position_id = null;

    public function __construct() {
        parent::__construct(self::$SCOPE);
    }

    public static function constructFromRecord(array $row): Block {
        $block = new Block();
        $block->initFromDb($row);
        return $block;
    }

    protected function initFromDb(array $row): void {
        $this->setPublished($row['published'] == 1 ? true : false);
        $this->setPositionId($row['position_id']);
        parent::initFromDb($row);
    }

    public function getPositionId(): ?int {
        return $this->_position_id;
    }

    public function setPositionId(?int $position_id): void {
        $this->_position_id = $position_id;
    }

    public function getPositionName(): string {
        $position_name = "";
        $position = $this->getPosition();
        if (!is_null($position)) {
            $position_name = $position->getName();
        }
        return $position_name;
    }

    public function getPosition(): ?BlockPosition {
        $dao = BlockDaoMysql::getInstance();
        return $dao->getBlockPosition($this->_position_id);
    }

}