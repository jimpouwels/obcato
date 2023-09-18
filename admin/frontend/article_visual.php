<?php
    defined ('_ACCESS') or die;

    require_once CMS_ROOT . "frontend/frontend_visual.php";
    require_once CMS_ROOT . 'database/dao/element_dao.php';
    require_once CMS_ROOT . 'database/dao/article_dao.php';
    require_once CMS_ROOT . 'database/dao/template_dao.php';
    
    class ArticleVisual extends FrontendVisual {
        
        private TemplateDao $_template_dao;
        private WebFormDao $_webform_dao;
        private ArticleDao $_article_dao;
        private WebFormItemFactory $_webform_item_factory;

        public function __construct(Page $page, Article $article) {
            parent::__construct($page, $article);
            $this->_webform_dao = WebFormDao::getInstance();
            $this->_article_dao = ArticleDao::getInstance();
            $this->_template_dao = TemplateDao::getInstance();
            $this->_webform_item_factory = WebFormItemFactory::getInstance();
        }

        public function getTemplateFilename(): string {
            return FRONTEND_TEMPLATE_DIR . "/" . $this->_template_dao->getTemplateFile($this->getArticle()->getTemplate()->getTemplateFileId())->getFileName();
        }

        public function loadVisual(Smarty_Internal_Data $template_data, ?array &$data): void {
            $data["id"] = $this->getArticle()->getId();
            $data["title"] = $this->getArticle()->getTitle();
            $data["description"] = $this->getArticle()->getDescription();
            $data["publication_date"] = $this->getArticle()->getPublicationDate();
            $data["sort_date"] = explode(' ', $this->getArticle()->getSortDate())[0];
            $data["image"] = $this->getImageData($this->getArticle()->getImage());
            $data["elements"] = $this->renderElementHolderContent($this->getArticle());
            $data["comments"] = $this->renderArticleComments($this->getArticle());
            $data["comment_webform"] = $this->renderArticleCommentWebForm($this->getArticle());
            foreach ($data as $key => $value) {
                $template_data->assign($key, $value);
            }
        }
        
        public function getPresentable(): ?Presentable {
            return $this->getArticle();
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

        private function renderElementHolderContent(ElementHolder $element_holder) {
            $elements_content = array();
            foreach ($element_holder->getElements() as $element) {
                $element_data = array();
                $element_data["type"] = $element->getType()->getIdentifier();
                if ($element->getTemplate()) {
                    $element_data["to_string"] = $element->getFrontendVisual($this->getPage(), $this->getArticle())->render();
                }
                $elements_content[] = $element_data;
            }
            return $elements_content;
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
    }