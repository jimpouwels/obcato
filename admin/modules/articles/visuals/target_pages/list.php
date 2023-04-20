<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "view/views/page_picker.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";

    class TargetPagesList extends Panel {

        private static string $TEMPLATE = "articles/target_pages/list.tpl";

        private ArticleDao $_article_dao;

        public function __construct() {
            parent::__construct('Beschikbare doelpagina\'s', 'target_pages_fieldset');
            $this->_article_dao = ArticleDao::getInstance();
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $this->getTemplateEngine()->assign("target_pages", $this->getTargetPages());
            $this->getTemplateEngine()->assign("default_target_page", $this->getDefaultTargetPage());

            $page_picker = new PagePicker("", null, "add_target_page_ref", "update_target_pages", "articles");
            $this->getTemplateEngine()->assign("page_picker", $page_picker->render());

            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }

        private function getDefaultTargetPage(): array {
            $target_page = $this->_article_dao->getDefaultTargetPage();
            $target_page_value = null;
            if (!is_null($target_page)) {
                $target_page_value = $this->toArray($target_page);
            }
            return $target_page_value;
        }

        private function getTargetPages(): array {
            $target_pages = array();
            foreach ($this->_article_dao->getTargetPages() as $target_page) {
                $target_pages[] = $this->toArray($target_page);
            }
            return $target_pages;
        }

        private function toArray($target_page): array {
            $target_page_value = array();
            $target_page_value["id"] = $target_page->getId();
            $target_page_value["title"] = $target_page->getTitle();
            return $target_page_value;
        }
    }
