<?php
    // No direct access
    defined("_ACCESS") or die;

    require_once CMS_ROOT . "/view/form.php";

    class ArticleOverviewElementForm extends Form {

        private $_article_overview_element;

        public function __construct($article_overview_element) {
            $this->_article_overview_element = $article_overview_element;
        }

        public function loadFields() {
        }

    }