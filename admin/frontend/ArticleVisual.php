<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/frontend/FrontendVisual.php";
require_once CMS_ROOT . '/database/dao/ElementDaoMysql.php';
require_once CMS_ROOT . '/database/dao/ArticleDaoMysql.php';
require_once CMS_ROOT . '/database/dao/TemplateDaoMysql.php';
require_once CMS_ROOT . '/database/dao/ImageDaoMysql.php';
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";

class ArticleVisual extends FrontendVisual {

    private TemplateDao $templateDao;
    private WebFormDao $webformDao;
    private ArticleDao $articleDao;
    private ImageDao $imageDao;
    private ElementDao $elementDao;

    public function __construct(Page $page, Article $article) {
        parent::__construct($page, $article);
        $this->webformDao = WebFormDaoMysql::getInstance();
        $this->articleDao = ArticleDaoMysql::getInstance();
        $this->templateDao = TemplateDaoMysql::getInstance();
        $this->imageDao = ImageDaoMysql::getInstance();
        $this->elementDao = ElementDaoMysql::getInstance();
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
        $imageData = null;
        if (!is_null($image)) {
            $imageData = array();
            $imageData["title"] = $image->getTitle();
            $imageData["url"] = $this->getImageUrl($image);
        }
        return $imageData;
    }

    private function renderElementHolderContent(ElementHolder $element_holder): array {
        $elementsContents = array();
        foreach ($element_holder->getElements() as $element) {
            $elementData = array();
            $elementType = $this->elementDao->getElementTypeForElement($element->getId());
            $elementData["type"] = $elementType->getIdentifier();
            if ($element->getTemplate()) {
                $elementData["to_string"] = $element->getFrontendVisual($this->getPage(), $this->getArticle())->render();
            }
            $elementsContents[] = $elementData;
        }
        return $elementsContents;
    }

    private function renderArticleComments(Article $article): array {
        $commentsData = array();
        foreach ($this->articleDao->getArticleComments($article->getId()) as $comment) {
            $commentData = array();
            $commentData['id'] = htmlspecialchars($comment->getId());
            $commentData['name'] = htmlspecialchars($comment->getName());
            $commentData['message'] = htmlspecialchars($comment->getMessage());
            $commentData['created_at'] = $comment->getCreatedAt();
            $childComments = array();
            foreach ($this->articleDao->getChildArticleComments($comment->getId()) as $child) {
                $childCommentData = array();
                $childCommentData['id'] = htmlspecialchars($child->getId());
                $childCommentData['created_at'] = $child->getCreatedAt();
                $childCommentData['name'] = htmlspecialchars($child->getName());
                $childCommentData['message'] = htmlspecialchars($child->getMessage());
                $childComments[] = $childCommentData;
            }
            $commentData['children'] = $childComments;
            $commentsData[] = $commentData;
        }
        return $commentsData;
    }

    private function renderArticleCommentWebForm($article): string {
        if (!is_null($article->getCommentWebFormId())) {
            $commentWebform = $this->webformDao->getWebForm($article->getCommentWebFormId());
            $formVisual = new FormFrontendVisual($this->getPage(), $article, $commentWebform);
            return $formVisual->render();
        }
        return "";
    }
}