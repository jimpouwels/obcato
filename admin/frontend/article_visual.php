<?php
    defined ('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/frontend_visual.php";
    require_once CMS_ROOT . 'database/dao/element_dao.php';
    require_once CMS_ROOT . "database/dao/article_dao.php";
    require_once CMS_ROOT . "database/dao/webform_dao.php";
    require_once CMS_ROOT . 'modules/webforms/webform_item_factory.php';

    class ArticleFrontendVisual extends FrontendVisual {

        private ArticleDao $_article_dao;
        private WebFormDao $_webform_dao;
        private WebFormItemFactory $_webform_item_factory;

        public function __construct(Page $page, Article $article) {
            parent::__construct($page, $article);
            $this->_webform_dao = WebFormDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_webform_item_factory = WebFormItemFactory::getInstance();
        }

        public function getTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->getArticle()->getTemplate()->getFileName();
        }

        public function load(): void {
            $this->assign("id", $this->getArticle()->getId());
            $this->assign("title", $this->getArticle()->getTitle());
            $this->assign("description", $this->getArticle()->getDescription());
            $this->assign("publication_date", $this->getArticle()->getPublicationDate());
            $this->assign("image", $this->getImageData($this->getArticle()->getImage()));
            $this->assign("elements", $this->renderElementHolderContent($this->getArticle()));
            $this->assign("comments", $this->renderArticleComments($this->getArticle()));
            $this->assign("comment_webform", $this->renderArticleCommentWebForm($this->getArticle()));
        }

        private function getImageData($image): ?array {
            $image_data = null;
            if (!is_null($image)) {
                $image_data = array();
                $image_data["title"] = $image->getTitle();
                $image_data["url"] = $this->getImageUrl($image);
            }
            return $image_data;
        }

        private function renderArticleComments(Article $article): array {
            $comments_data = array();
            foreach ($this->_article_dao->getArticleComments($article->getId()) as $comment) {
                $comment_data = array();
                $comment_data['id'] = htmlspecialchars($comment->getId());
                $comment_data['name'] = htmlspecialchars($comment->getName());
                $comment_data['message'] = htmlspecialchars($comment->getMessage());
                $comment_data['created_at'] = $comment->getCreatedAt();
                $child_comments = array();
                foreach ($this->_article_dao->getChildArticleComments($comment->getId()) as $child) {
                    $child_comment_data = array();
                    $child_comment_data['id'] = htmlspecialchars($child->getId());               
                    $child_comment_data['created_at'] = $child->getCreatedAt();
                    $child_comment_data['name'] = htmlspecialchars($child->getName());
                    $child_comment_data['message'] = htmlspecialchars($child->getMessage());
                    $child_comments[] = $child_comment_data;
                }
                $comment_data['children'] = $child_comments;
                $comments_data[] = $comment_data;
            }
            return $comments_data;
        }

        private function renderArticleCommentWebForm($article): string {
            if (!is_null($article->getCommentWebFormId())) {
                $comment_webform = $this->_webform_dao->getWebForm($article->getCommentWebFormId());
                $form_visual = new FormFrontendVisual($this->getPage(), $article, $comment_webform);
                return $form_visual->render();
            }
            return "";
        }

        private function renderElementHolderContent(ElementHolder $element_holder) {
            $elements_content = array();
            foreach ($element_holder->getElements() as $element) {
                if ($element->getTemplate()) {
                    $elements_content[] = $element->getFrontendVisual($this->getPage(), $this->getArticle())->render();
                }
            }
            return $elements_content;
        }
    }