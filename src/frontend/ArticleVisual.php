<?php

namespace Obcato\Core\frontend;

use Obcato\Core\database\dao\ImageDao;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\database\dao\TemplateDao;
use Obcato\Core\database\dao\TemplateDaoMysql;
use Obcato\Core\database\dao\WebformDao;
use Obcato\Core\database\dao\WebformDaoMysql;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\articles\service\ArticleInteractor;
use Obcato\Core\modules\articles\service\ArticleService;
use Obcato\Core\modules\pages\model\Page;
use Obcato\Core\modules\templates\model\Presentable;
use const Obcato\core\FRONTEND_TEMPLATE_DIR;

class ArticleVisual extends FrontendVisual {

    private TemplateDao $templateDao;
    private WebformDao $webformDao;
    private ArticleService $articleService;
    private ImageDao $imageDao;

    public function __construct(Page $page, Article $article) {
        parent::__construct($page, $article);
        $this->webformDao = WebformDaoMysql::getInstance();
        $this->articleService = ArticleInteractor::getInstance();
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
        $data["wallpaper"] = $this->getImageData($this->imageDao->getImage($this->getArticle()->getWallpaperId()));
        $data["terms"] = $this->getTermList();
        $this->renderElementHolderContent($this->getArticle(), $data);
        $data["comments"] = $this->renderArticleComments($this->getArticle());
        $data["comment_webform"] = $this->renderArticleCommentWebForm($this->getArticle());

        $data["parent_article"] = "";
        if ($this->getArticle()->getParentArticleId()) {
            $parentArticleData = array();
            $parentArticle = $this->articleService->getArticle($this->getArticle()->getParentArticleId());
            $parentArticleData["id"] = $parentArticle->getId();
            $parentArticleData["title"] = $parentArticle->getTitle();
            $parentArticleData["description"] = $parentArticle->getDescription();
            $parentArticleData["url"] = $this->getLinkHelper()->createArticleUrl($parentArticle);
            $data["parent_article"] = $parentArticleData;
        }
    }

    public function getPresentable(): ?Presentable {
        return $this->getArticle();
    }

    private function getImageData($image): ?array {
        $imageData = null;
        if ($image) {
            $imageData = array();
            $imageData["title"] = $image->getTitle();
            $imageData["url"] = $this->getLinkHelper()->createImageUrl($image);
            $imageData["location"] = $image->getLocation();
        }
        return $imageData;
    }

    private function renderArticleComments(Article $article): array {
        $commentsData = array();
        foreach ($this->articleService->getArticleComments($article->getId()) as $comment) {
            $commentData = array();
            $commentData['id'] = htmlspecialchars($comment->getId());
            $commentData['name'] = htmlspecialchars($comment->getName());
            $commentData['message'] = htmlspecialchars($comment->getMessage());
            $commentData['created_at'] = $comment->getCreatedAt();
            $childComments = array();
            foreach ($this->articleService->getChildArticleComments($comment->getId()) as $child) {
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

    private function getTermList(): array {
        $termList = array();
        foreach ($this->articleService->getTermsForArticle($this->getArticle()) as $term) {
            $termList[] = $term->getName();
        }
        return $termList;
    }
}