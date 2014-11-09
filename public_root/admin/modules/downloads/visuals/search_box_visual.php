<?php
    defined('_ACCESS') or die;

    class SearchBoxVisual extends Visual {

        private static $TEMPLATE = "downloads/search_box.tpl";
        private $_download_request_handler;

        public function __construct($download_request_handler) {
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_download_request_handler = $download_request_handler;
        }

        public function render() {
            $this->_template_engine->assign('search_query_field', $this->renderSearchQueryField());
            $this->_template_engine->assign('search_button', $this->renderSearchButton());
            return $this->_template_engine->fetch('modules/' . self::$TEMPLATE);
        }

        private function renderSearchQueryField() {
            $default_search_value = $this->_download_request_handler->getSearchQuery();
            $search_query_field = new TextField('search_query', 'Zoekterm', $default_search_value, false, false, null);
            return $search_query_field->render();
        }

        private function renderSearchButton() {
            $search_button = new Button('', 'Zoeken', 'document.getElementById(\'download_search\').submit(); return false;');
            return $search_button->render();
        }
    }