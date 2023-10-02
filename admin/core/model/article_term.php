<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/entity.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";

class ArticleTerm extends Entity {

    private string $_name = "";

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
        return $this->_name;
    }

    public function setName(string $name): void {
        $this->_name = $name;
    }

}