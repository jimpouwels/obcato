<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/entity.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";

    class ArticleTerm extends Entity {
    
        private $_name;
        
        public function getName() {
            return $this->_name;
        }
        
        public function setName($name) {
            $this->_name = $name;
        }
        
        public static function constructFromRecord($record) {
            $term = new ArticleTerm();
            $term->setId($record['id']);
            $term->setName($record['name']);
            return $term;
        }

    }