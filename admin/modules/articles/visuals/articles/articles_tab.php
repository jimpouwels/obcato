<?php

    defined('_ACCESS') or die;

    require_once CMS_ROOT . "modules/articles/visuals/articles/article_editor.php";
    require_once CMS_ROOT . "modules/articles/visuals/articles/articles_list.php";
    require_once CMS_ROOT . "modules/articles/visuals/articles/articles_search.php";

    class ArticleTab extends Visual {

        private ?Article $_current_article;
        private ArticleRequestHandler $_article_request_handler;

        public function __construct(ArticleRequestHandler $article_request_handler) {
            parent::__construct();
            $this->_article_request_handler = $article_request_handler;
            $this->_current_article = $article_request_handler->getCurrentArticle();
        }
        public function getTemplateFilename(): string {
            return "modules/articles/articles/root.tpl";
        }

        public function load(): void {
            $this->assign("search", $this->renderArticlesSearchPanel());
            if (!is_null($this->_current_article)) {
                $this->assign("editor", $this->renderArticleEditor());
            } else {
                $this->assign("list", $this->renderArticlesList());
            }
        }

        private function renderArticlesSearchPanel(): string {
            $articles_search_field = new ArticlesSearch($this->_article_request_handler);
            return $articles_search_field->render();
        }

        private function renderArticlesList(): string {
            $articles_list = new ArticlesList($this->_article_request_handler);
            return $articles_list->render();
        }

        private function renderArticleEditor(): string {
            $article_editor = new ArticleEditor($this->_current_article);
            return $article_editor->render();
        }

    }

?>
