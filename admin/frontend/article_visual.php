<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/frontend/frontend_visual.php";
require_once CMS_ROOT . '/database/dao/ElementDaoMysql.php';
require_once CMS_ROOT . '/database/dao/ArticleDaoMysql.php';
require_once CMS_ROOT . '/database/dao/TemplateDaoMysql.php';
require_once CMS_ROOT . '/database/dao/ImageDaoMysql.php';

class ArticleVisual extends FrontendVisual {

    private TemplateDao $templateDao;
    private WebFormDao $webformDao;
    private ArticleDao $articleDao;
    private ImageDao $imageDao;

    public function __construct(Page $page, Article $article) {
        parent::__construct($page, $article);
        $this->webformDao = WebFormDaoMysql::getInstance();
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->templateDao = TemplateDaoMysql::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return FRONTEND_TEMPLATE_DIR . "/" . $this->templateDao->getTemplateFile($this->getArticle()->getTemplate()->getTemplateFileId())->getFileName();
    }

    public function loadVisual(?array &$data): void {
        $data["id"] = $this->getArticle()->getId();
        $data["title"] = $this->getArticle()->getTitle();
        $data["description"] = $this->getArticle()->getDescription();
        $data["publication_date"] = $this->getArticle()->getPublicationDate();
        $data["sort_date"] = explode(' ', $this->getArticle()->getSortDate())[0];
        $data["image"] = $this->getImageData($this->imageDao->getImage($this->getArticle()->getImageId()));
        $data["elements"] = $this->renderElementHolderContent($this->getArticle());
        $data["comments"] = $this->renderArticleComments($this->getArticle());
        $data["comment_webform"] = $this->renderArticleCommentWebForm($this->getArticle());
        foreach ($data as $key => $value) {
            $this->assign($key, $value);
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

    private function renderElementHolderContent(ElementHolder $element_holder): array {
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
        foreach ($this->articleDao->getArticleComments($article->getId()) as $comment) {
            $comment_data = array();
            $comment_data['id'] = htmlspecialchars($comment->getId());
            $comment_data['name'] = htmlspecialchars($comment->getName());
            $comment_data['message'] = htmlspecialchars($comment->getMessage());
            $comment_data['created_at'] = $comment->getCreatedAt();
            $child_comments = array();
            foreach ($this->articleDao->getChildArticleComments($comment->getId()) as $child) {
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
            $comment_webform = $this->webformDao->getWebForm($article->getCommentWebFormId());
            $form_visual = new FormFrontendVisual($this->getPage(), $article, $comment_webform);
            return $form_visual->render();
        }
        return "";
    }
}