<?php
    // No direct access
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "/view/request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "/elements/article_overview_element/article_overview_element_request_handler.php";
    require_once CMS_ROOT . "/database/dao/element_dao.php";

    class ArticleOverviewElementRequestHandler extends HttpRequestHandler {

        private $_article_overview_element;
        private $_element_dao;
        private $_article_overview_element_form;

        public function __construct($article_overview_element) {
            $this->_article_overview_element = $article_overview_element;
            $this->_element_dao = ElementDao::getInstance();
            $this->_article_overview_element_form = new ArticleOverviewElementForm($article_overview_element);
        }

        public function handleGet() {
        }

        public function handlePost()
        {
            $this->_article_overview_element_form->loadFields();
            // Update other stuff
            $this->_element_dao->updateElement($this->_list_element);
        }
    }
?>