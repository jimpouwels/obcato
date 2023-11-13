<?php

require_once CMS_ROOT . '/database/dao/ArticleDao.php';
require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ElementHolderDaoMysql.php";
require_once CMS_ROOT . "/modules/articles/model/Article.php";
require_once CMS_ROOT . "/modules/articles/model/ArticleComment.php";
require_once CMS_ROOT . "/modules/articles/model/ArticleTerm.php";
require_once CMS_ROOT . "/utilities/DateUtility.php";

class ArticleDaoMysql implements ArticleDao {
    private static string $myAllColumns = "e.id, e.template_id, e.title, e.published, e.last_modified, e.scope_id,
                      e.created_at, e.created_by, e.type, a.description, a.url_title, a.keywords, a.image_id, a.template_id, a.parent_article_id, a.publication_date, a.sort_date, a.target_page, a.comment_webform_id";

    private static ?ArticleDaoMysql $instance = null;
    private PageDao $pageDao;
    private ElementHolderDao $elementHolderDao;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->pageDao = PageDaoMysql::getInstance();
        $this->elementHolderDao = ElementHolderDaoMysql::getInstance();
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): ArticleDaoMysql {
        if (!self::$instance) {
            self::$instance = new ArticleDaoMysql();
        }
        return self::$instance;
    }

    public function getArticle($id): ?Article {
        $statement = $this->mysqlConnector->prepareStatement("SELECT " . self::$myAllColumns . " FROM
                                                                    element_holders e, articles a WHERE e.id = ?
                                                                    AND e.id = a.element_holder_id");
        $statement->bind_param("i", $id);
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return Article::constructFromRecord($row);
        }
        return null;
    }

    public function getArticleByElementHolderId($elementHolderId): ?Article {
        $statement = $this->mysqlConnector->prepareStatement("SELECT " . self::$myAllColumns . " FROM
                                                                    element_holders e, articles a WHERE a.element_holder_id = ?
                                                                    AND e.id = a.element_holder_id");
        $statement->bind_param("i", $elementHolderId);
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return Article::constructFromRecord($row);
        }
        return null;
    }

    public function getAllArticles(): array {
        $query = "SELECT " . self::$myAllColumns . " FROM element_holders e, articles a WHERE e.id = a.element_holder_id
                      order by title ASC";
        $result = $this->mysqlConnector->executeQuery($query);
        $articles = array();
        while ($row = $result->fetch_assoc()) {
            $articles[] = Article::constructFromRecord($row);
        }
        return $articles;
    }

    public function getAllChildArticles(int $parentArticleId): array {
        $query = "SELECT " . self::$myAllColumns . " FROM element_holders e, articles a WHERE e.id = a.element_holder_id
                      AND parent_article_id = " . $parentArticleId . " order by created_at DESC";
        $result = $this->mysqlConnector->executeQuery($query);
        $articles = array();
        while ($row = $result->fetch_assoc()) {
            $articles[] = Article::constructFromRecord($row);
        }
        return $articles;
    }

    public function searchArticles(string $keyword, ?int $termId): array {
        $from = " FROM element_holders e, articles a";
        $where = " WHERE
                      e.id = a.element_holder_id";
        if ($keyword)
            $where = $where . " AND e.title LIKE '" . $keyword . "%'";
        if ($termId) {
            $from = $from . ", articles_terms ats";
            $where = $where . " AND ats.term_id = " . $termId . " AND ats.article_id = e.id";
        }

        $query = "SELECT DISTINCT " . self::$myAllColumns . $from . $where . " ORDER BY title";
        $result = $this->mysqlConnector->executeQuery($query);
        $articles = array();
        while ($row = $result->fetch_assoc()) {
            $articles[] = Article::constructFromRecord($row);
        }
        return $articles;
    }

    public function searchPublishedArticles(?string $fromDate, ?string $toDate, ?string $orderBy, ?string $orderType, ?array $terms, ?int $maxResults): array {
        $queryWhere = " WHERE e.id = a.element_holder_id";
        $queryWhere = $queryWhere . " AND publication_date <= now()";
        if ($toDate) {
            $queryWhere = $queryWhere . " AND publication_date <= '" . DateUtility::stringMySqlDate($toDate) . "'";
        }
        if ($fromDate) {
            $queryWhere = $queryWhere . " AND publication_date > '" . DateUtility::stringMySqlDate($fromDate) . "'";
        }
        $queryFrom = " FROM element_holders e, articles a";
        if ($terms && count($terms) > 0) {
            $queryFrom = $queryFrom . ", articles_terms at";
            foreach ($terms as $term) {
                $queryWhere = $queryWhere . " AND EXISTS(SELECT * FROM articles_terms at WHERE at.article_id = e.id AND at.term_id = " . $term->getId() . ")";
            }
        }
        $limitQueryPart = '';
        if ($maxResults) {
            $limitQueryPart = " LIMIT " . $maxResults;
        }

        $orderQueryPart = '';
        if ($orderBy) {
            switch ($orderBy) {
                case "Alphabet":
                    $orderQueryPart = 'e.title';
                    break;
                case "PublicationDate":
                    $orderQueryPart = 'a.publication_date ' . $orderType;
                    break;
                case "SortDate":
                    $orderQueryPart = 'a.sort_date ' . $orderType;
                    break;
            }
        }

        $query = "SELECT DISTINCT " . self::$myAllColumns . $queryFrom . $queryWhere . " ORDER BY " . $orderQueryPart . $limitQueryPart;
        $result = $this->mysqlConnector->executeQuery($query);
        $articles = array();
        while ($row = $result->fetch_assoc()) {
            $articles[] = Article::constructFromRecord($row);
        }
        return $articles;
    }

    public function updateArticle(Article $article): void {
        $description = $this->mysqlConnector->realEscapeString($article->getDescription());
        $publicationDate = $article->getPublicationDate();
        $sortDate = $article->getSortDate();
        $imageId = $article->getImageId();
        $targetPage = $article->getTargetPageId();
        $parentArticleId = $article->getParentArticleId();
        $templateId = $article->getTemplateId();
        $keywords = $article->getKeywords();
        $urlTitle = $article->getUrlTitle();
        $commentWebformId = $article->getCommentWebFormId();
        $elementHolderId = $article->getId();

        $statement = $this->mysqlConnector->prepareStatement("UPDATE articles SET description = ?, publication_date = ?, url_title = ?, sort_date = ?, image_id = ?, target_page = ?, parent_article_id = ?, template_id = ?, keywords = ?, comment_webform_id = ? WHERE element_holder_id = ?");
        $statement->bind_param('ssssiiiisii', $description, $publicationDate, $urlTitle, $sortDate, $imageId, $targetPage, $parentArticleId, $templateId, $keywords, $commentWebformId, $elementHolderId);

        $this->mysqlConnector->executeStatement($statement);
        $this->elementHolderDao->update($article);
    }

    public function deleteArticle($article): void {
        $this->elementHolderDao->delete($article);
    }

    public function createArticle(Article $article): void {
        $this->elementHolderDao->persist($article);
        $query = "INSERT INTO articles (description, image_id, element_holder_id, sort_date, publication_date, target_page) VALUES
                      (NULL, NULL, " . $article->getId() . ", now(), now(), NULL)";
        $this->mysqlConnector->executeQuery($query);
    }

    public function getArticleComments(int $articleId): array {
        $query = "SELECT * FROM article_comments WHERE article_id = ? AND parent IS NULL";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $statement->bind_param('i', $articleId);
        $result = $this->mysqlConnector->executeStatement($statement);
        $comments = array();
        while ($row = $result->fetch_assoc()) {
            $comments[] = ArticleComment::constructFromRecord($row);
        }
        return $comments;
    }

    public function getChildArticleComments(int $commentId): array {
        $query = "SELECT * FROM article_comments WHERE parent = ?";
        $statement = $this->mysqlConnector->prepareStatement($query);
        $statement->bind_param('i', $commentId);
        $result = $this->mysqlConnector->executeStatement($statement);
        $comments = array();
        while ($row = $result->fetch_assoc()) {
            $comments[] = ArticleComment::constructFromRecord($row);
        }
        return $comments;
    }

    public function getAllTerms(): array {
        $query = "SELECT * FROM article_terms ORDER by name ASC";
        $terms = array();
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            $terms[] = ArticleTerm::constructFromRecord($row);
        }
        return $terms;
    }

    public function getTerm($id): ?ArticleTerm {
        $query = "SELECT * FROM article_terms WHERE id = " . $id;
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ArticleTerm::constructFromRecord($row);
        }
        return null;
    }

    public function createTerm($termName): ArticleTerm {
        $newTerm = new ArticleTerm();
        $newTerm->setName($termName);
        $postfix = 1;
        while (!is_null($this->getTermByName($newTerm->getName()))) {
            $newTerm->setName($termName . " " . $postfix);
            $postfix++;
        }
        $this->persistTerm($newTerm);
        return $newTerm;
    }

    public function getTermByName($name): ?ArticleTerm {
        $query = "SELECT * FROM article_terms WHERE name = '" . $name . "'";
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ArticleTerm::constructFromRecord($row);
        }
        return null;
    }

    private function persistTerm($term): void {
        $query = "INSERT INTO article_terms (name) VALUES  ('" . $term->getName() . "')";
        $this->mysqlConnector->executeQuery($query);
        $term->setId($this->mysqlConnector->getInsertId());
    }

    public function updateTerm($term): void {
        $query = "UPDATE article_terms SET name = '" . $term->getName() .
            "' WHERE id = " . $term->getId();
        $this->mysqlConnector->executeQuery($query);
    }

    public function deleteTerm($term): void {
        $query = "DELETE FROM article_terms WHERE id = " . $term->getId();
        $this->mysqlConnector->executeQuery($query);
    }

    public function getTermsForArticle(int $articleId): array {
        $query = "SELECT at.id, at.name FROM article_terms at, articles_terms ats,
                      element_holders e WHERE ats.article_id = " . $articleId . " AND ats.article_id =
                      e.id AND at.id = ats.term_id";

        $result = $this->mysqlConnector->executeQuery($query);
        $terms = array();
        while ($row = $result->fetch_assoc()) {
            $terms[] = ArticleTerm::constructFromRecord($row);
        }
        return $terms;
    }

    public function addTermToArticle($termId, $article): void {
        $query = "INSERT INTO articles_terms (article_id, term_id) VALUES (" . $article->getId() . ", " . $termId . ")";
        $this->mysqlConnector->executeQuery($query);
    }

    public function deleteTermFromArticle($termId, $article): void {
        $query = "DELETE FROM articles_terms WHERE article_id = " . $article->getId() . "
                      AND term_id = " . $termId;
        $this->mysqlConnector->executeQuery($query);
    }

    public function addTargetPage($targetPageId): void {
        $statement = $this->mysqlConnector->prepareStatement("SELECT count(*) AS number_of FROM article_target_pages WHERE element_holder_id = ?");
        $statement->bind_param("i", $targetPageId);
        $result = $this->mysqlConnector->executeStatement($statement);
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            $count = $row['number_of'];
            break;
        }

        if ($count == 0) {
            $statement = $this->mysqlConnector->prepareStatement("INSERT INTO article_target_pages (element_holder_id, is_default) VALUES (?, 0)");
            $statement->bind_param("i", $targetPageId);
            $this->mysqlConnector->executeStatement($statement);

            // check if only one target page is present
            $this->updateDefaultArticleTargetPage();
        }
    }

    private function updateDefaultArticleTargetPage(): void {
        $target_pages = $this->getTargetPages();
        if (count($target_pages) == 1) {
            $query = "UPDATE article_target_pages SET is_default = 1";
            $this->mysqlConnector->executeQuery($query);
        }
    }

    public function getTargetPages(): array {
        $query = "SELECT element_holder_id FROM article_target_pages";
        $result = $this->mysqlConnector->executeQuery($query);
        $pages = array();
        while ($row = $result->fetch_assoc()) {
            $pages[] = $this->pageDao->getPage($row['element_holder_id']);
        }
        return $pages;
    }

    public function deleteTargetPage($targetPageId): void {
        $query = "DELETE FROM article_target_pages where element_holder_id = " . $targetPageId;
        $this->mysqlConnector->executeQuery($query);

        // check if only one target page is present
        $this->updateDefaultArticleTargetPage();
    }

    public function getDefaultTargetPage(): ?Page {
        $query = "SELECT element_holder_id FROM article_target_pages WHERE is_default = 1";
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return $this->pageDao->getPage($row["element_holder_id"]);
        }
        return null;
    }

    public function setDefaultArticleTargetPage($targetPageId): void {
        $query1 = "UPDATE article_target_pages SET is_default = 0 WHERE is_default = 1";
        $query2 = "UPDATE article_target_pages SET is_default = 1 WHERE element_holder_id = " . $targetPageId;
        $this->mysqlConnector->executeQuery($query1);
        $this->mysqlConnector->executeQuery($query2);
    }

}

?>
