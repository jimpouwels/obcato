<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";

    class ArticleTerm extends Entity {
    
        private string $_name = "";
        
        public function getName(): string {
            return $this->_name;
        }
        
        public function setName(string $name): void {
            $this->_name = $name;
        }
        
        public static function constructFromRecord(array $record): ArticleTerm {
            $term = new ArticleTerm();
            $term->setId($record['id']);
            $term->setName($record['name']);
            return $term;
        }

    }