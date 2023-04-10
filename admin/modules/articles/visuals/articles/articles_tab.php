<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "modules/articles/visuals/articles/article_editor.php";
    require_once CMS_ROOT . "modules/articles/visuals/articles/articles_list.php";
    require_once CMS_ROOT . "modules/articles/visuals/articles/articles_search.php";

    class ArticleTab extends Visual {

        private static $TEMPLATE = "articles/articles/root.tpl";

        private $_current_article;
        private $_article_request_handler;

        public function __construct($article_request_handler) {
            parent::__construct();
            $this->_article_request_handler = $article_request_handler;
            $this->_current_article = $article_request_handler->getCurrentArticle();
        }

        public function render(): string {
            $this->getTemplateEngine()->assign("search", $this->renderArticlesSearchPanel());
            if (!is_null($this->_current_article))
                $this->getTemplateEngine()->assign("editor", $this->renderArticleEditor());
            else
                $this->getTemplateEngine()->assign("list", $this->renderArticlesList());

            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE);
        }

        private function renderArticlesSearchPanel() {
            $articles_search_field = new ArticlesSearch($this->_article_request_handler);
            return $articles_search_field->render();
        }

        private function renderArticlesList() {
            $articles_list = new ArticlesList($this->_article_request_handler);
            return $articles_list->render();
        }

        private function renderArticleEditor() {
            $article_editor = new ArticleEditor($this->_current_article);
            return $article_editor->render();
        }

    }

?>
