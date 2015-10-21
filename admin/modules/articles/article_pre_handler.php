<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "request_handlers/element_holder_request_handler.php";
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "database/dao/element_dao.php";
    require_once CMS_ROOT . "modules/articles/article_form.php";
    require_once CMS_ROOT . 'friendly_urls/friendly_url_manager.php';

    class ArticlePreHandler extends ElementHolderRequestHandler {

        private static $ARTICLE_ID_POST = "element_holder_id";
        private static $ARTICLE_ID_GET = "article";
        private $_current_article;
        private $_element_dao;
        private $_article_dao;
        private $_friendly_url_manager;

        public function __construct() {
            parent::__construct();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_element_dao = ElementDao::getInstance();
            $this->_friendly_url_manager = new FriendlyUrlManager();
        }

        public function handleGet() {
            $this->_current_article = $this->getArticleFromGetRequest();
        }

        public function handlePost() {
            parent::handlePost();
            $this->_current_article = $this->getArticleFromPostRequest();
            if ($this->isUpdateArticleAction())
                $this->updateArticle();
            else if ($this->isDeleteArticleAction())
                $this->deleteArticle();
            else if ($this->isAddArticleAction())
                $this->addArticle();
        }

        public function getCurrentArticle() {
            return $this->_current_article;
        }

        public function getSearchQuery() {
            if (isset($_GET['search_query']))
                return $_GET['search_query'];
        }

        public function getSearchTerm() {
            if (isset($_GET['s_term']))
                return $_GET['s_term'];
        }

        public function isSearchAction() {
            return isset($_GET['action']) && $_GET['action'] == 'search';
        }

        private function updateArticle() {
            $article_form = new ArticleForm($this->_current_article);
            try {
                $article_form->loadFields();
                $this->_element_dao->updateElementOrder($article_form->getElementOrder(), $this->_current_article);
                $this->_article_dao->updateArticle($this->_current_article);
                $this->updateElementHolder($this->_current_article);
                $this->updateSelectedTerms($article_form->getSelectedTerms());
                $this->deleteSelectedTerms($article_form);
                $this->_friendly_url_manager->insertOrUpdateFriendlyUrlForArticle($this->_current_article);
                $this->sendSuccessMessage("Artikel succesvol opgeslagen");
            } catch (FormException $e) {
                $this->sendErrorMessage("Artikel niet opgeslagen, verwerk de fouten");
            }
        }

        private function addArticle() {
            $new_article = $this->_article_dao->createArticle();
            $this->sendSuccessMessage("Artikel succesvol aangemaakt");
            $this->redirectTo('/admin/index.php?article=' . $new_article->getId());
        }

        private function deleteArticle() {
            $this->_article_dao->deleteArticle($this->_current_article);
            $this->sendSuccessMessage("Artikel succesvol verwijderd");
            $this->redirectTo('/admin/index.php');
        }

        private function updateSelectedTerms($selected_terms) {
            if (count($selected_terms) == 0) return;
            $existing_terms = $this->_current_article->getTerms();
            foreach ($selected_terms as $selected_term_id) {
                if (!$this->termAlreadyExists($selected_term_id, $existing_terms)) {
                    $this->_article_dao->addTermToArticle($selected_term_id, $this->_current_article);
                }
            }
        }

        private function deleteSelectedTerms($article_form) {
            foreach ($article_form->getTermsToDelete() as $term_to_delete)
                $this->_article_dao->deleteTermFromArticle($term_to_delete->getId(), $this->_current_article);
        }

        private function getArticleFromGetRequest() {
            if (isset($_GET[self::$ARTICLE_ID_GET]))
                return $this->getArticleFromDatabase($_GET[self::$ARTICLE_ID_GET]);
        }

        private function getArticleFromPostRequest() {
            if (isset($_POST[self::$ARTICLE_ID_POST]))
                return $this->getArticleFromDatabase($_POST[self::$ARTICLE_ID_POST]);
        }

        private function getArticleFromDatabase($article_id) {
            return $this->_article_dao->getArticle($article_id);
        }

        private function termAlreadyExists($term_id, $existing_terms) {
            foreach ($existing_terms as $existing_term)
                if ($existing_term->getId() == $term_id)
                    return true;
            return false;
        }

        private function isUpdateArticleAction() {
            return isset($_POST["action"]) && $_POST["action"] == "update_element_holder";
        }

        private function isDeleteArticleAction() {
            return isset($_POST["action"]) && $_POST["action"] == "delete_article";
        }

        private function isAddArticleAction() {
            return isset($_POST["add_article_action"]);
        }

    }
