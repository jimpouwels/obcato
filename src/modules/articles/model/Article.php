<?php

namespace Obcato\Core\modules\articles\model;

use Obcato\Core\core\model\ElementHolder;

class Article extends ElementHolder {

    const ElementHolderType = "ELEMENT_HOLDER_ARTICLE";
    private static int $SCOPE = 9;
    private ?string $description;
    private ?int $imageId = null;
    private ?int $wallpaperId = null;
    private ?string $keywords = null;
    private ?string $urlTitle = null;
    private string $publicationDate;
    private string $sortDate;
    private ?int $targetPageId = null;
    private ?int $parentArticleId = null;
    private ?int $commentWebformId = null;

    public function __construct() {
        parent::__construct(self::$SCOPE);
        $this->setPublished(false);
    }

    public static function constructFromRecord($row): Article {
        $article = new Article();
        $article->initFromDb($row);
        return $article;
    }

    protected function initFromDb(array $row): void {
        $this->setDescription($row['description']);
        $this->setImageId($row['image_id']);
        $this->setWallpaperId($row['wallpaper_id']);
        $this->setKeywords($row['keywords']);
        $this->setUrlTitle($row['url_title']);
        $this->setPublicationDate($row['publication_date']);
        $this->setSortDate($row['sort_date']);
        $this->setTargetPageId(!is_null($row['target_page']) ? intval($row['target_page']) : null);
        $this->setParentArticleId(!is_null($row['parent_article_id']) ? intval($row['parent_article_id']) : null);
        $this->setCommentWebFormId($row['comment_webform_id']);
        parent::initFromDb($row);
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function setUrlTitle(?string $urlTitle): void {
        $this->urlTitle = $urlTitle;
    }

    public function getUrlTitle(): ?string {
        return $this->urlTitle;
    }

    public function getKeywords(): ?string {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): void {
        $this->keywords = $keywords;
    }

    public function getImageId(): ?int {
        return $this->imageId;
    }

    public function setImageId(?int $image_id): void {
        $this->imageId = $image_id;
    }

    public function getWallpaperId(): ?int {
        return $this->wallpaperId;
    }

    public function setWallpaperId(?int $wallpaperId): void {
        $this->wallpaperId = $wallpaperId;
    }

    public function getPublicationDate(): string {
        return $this->publicationDate;
    }

    public function setPublicationDate(string $publication_date): void {
        $this->publicationDate = $publication_date;
    }

    public function getSortDate(): string {
        return $this->sortDate;
    }

    public function setSortDate(string $sort_date): void {
        $this->sortDate = $sort_date;
    }

    public function getTargetPageId(): ?int {
        return $this->targetPageId;
    }

    public function setTargetPageId(?int $targetPageId): void {
        $this->targetPageId = $targetPageId;
    }

    public function getParentArticleId(): ?int {
        return $this->parentArticleId;
    }

    public function setParentArticleId(?int $parent_article_id): void {
        $this->parentArticleId = $parent_article_id;
    }

    public function getCommentWebFormId(): ?int {
        return $this->commentWebformId;
    }

    public function setCommentWebFormId(?int $comment_webform_id): void {
        $this->commentWebformId = $comment_webform_id;
    }

}