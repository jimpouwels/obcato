<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/core/model/element_holder.php";
require_once CMS_ROOT . "/database/dao/ArticleDaoMysql.php";
require_once CMS_ROOT . "/database/dao/PageDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ImageDaoMysql.php";

class Article extends ElementHolder {

    const ElementHolderType = "ELEMENT_HOLDER_ARTICLE";
    private static int $SCOPE = 9;
    private ?string $_description;
    private ?int $_image_id = null;
    private ?string $_keywords = null;
    private string $_publication_date;
    private string $_sort_date;
    private ?int $_target_page_id = null;
    private ?int $_parent_article_id = null;
    private ?int $_comment_webform_id = null;
    private PageDao $_page_dao;

    public function __construct() {
        parent::__construct(self::$SCOPE);
        $this->_page_dao = PageDaoMysql::getInstance();
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
        $this->setKeywords($row['keywords']);
        $this->setPublicationDate($row['publication_date']);
        $this->setSortDate($row['sort_date']);
        $this->setTargetPageId(!is_null($row['target_page']) ? intval($row['target_page']) : null);
        $this->setParentArticleId(!is_null($row['parent_article_id']) ? intval($row['parent_article_id']) : null);
        $this->setCommentWebFormId($row['comment_webform_id']);
        parent::initFromDb($row);
    }

    public function getDescription(): ?string {
        return $this->_description;
    }

    public function setDescription(?string $description): void {
        $this->_description = $description;
    }

    public function getKeywords(): ?string {
        return $this->_keywords;
    }

    public function setKeywords(?string $keywords): void {
        $this->_keywords = $keywords;
    }

    public function getImageId(): ?int {
        return $this->_image_id;
    }

    public function setImageId(?int $image_id): void {
        $this->_image_id = $image_id;
    }

    public function getImage(): ?Image {
        $image = null;
        if ($this->_image_id != '' && !is_null($this->_image_id)) {
            $image_dao = ImageDaoMysql::getInstance();
            $image = $image_dao->getImage($this->_image_id);
        }
        return $image;
    }

    public function getPublicationDate(): string {
        return $this->_publication_date;
    }

    public function setPublicationDate(string $publication_date): void {
        $this->_publication_date = $publication_date;
    }

    public function getSortDate(): string {
        return $this->_sort_date;
    }

    public function setSortDate(string $sort_date): void {
        $this->_sort_date = $sort_date;
    }

    public function getTargetPageId(): ?int {
        return $this->_target_page_id;
    }

    public function setTargetPageId(?int $target_page_id): void {
        $this->_target_page_id = $target_page_id;
    }

    public function getParentArticleId(): ?int {
        return $this->_parent_article_id;
    }

    public function setParentArticleId(?int $parent_article_id): void {
        $this->_parent_article_id = $parent_article_id;
    }

    public function getTargetPage(): ?Page {
        $target_page = null;
        if (!is_null($this->_target_page_id) && $this->_target_page_id != '') {
            $target_page = $this->_page_dao->getPage($this->_target_page_id);
        }
        return $target_page;
    }

    public function getCommentWebFormId(): ?int {
        return $this->_comment_webform_id;
    }

    public function setCommentWebFormId(?int $comment_webform_id): void {
        $this->_comment_webform_id = $comment_webform_id;
    }

    public function getTerms(): array {
        $article_dao = ArticleDaoMysql::getInstance();
        return $article_dao->getTermsForArticle($this->getId());
    }

}