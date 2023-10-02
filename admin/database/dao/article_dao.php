<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/authentication/authenticator.php";
require_once CMS_ROOT . "/database/mysql_connector.php";
require_once CMS_ROOT . "/database/dao/element_dao.php";
require_once CMS_ROOT . "/database/dao/element_holder_dao.php";
require_once CMS_ROOT . "/core/model/article.php";
require_once CMS_ROOT . "/core/model/article_comment.php";
require_once CMS_ROOT . "/core/model/article_term.php";
require_once CMS_ROOT . "/utilities/date_utility.php";

class ArticleDao {
    private static string $myAllColumns = "e.id, e.template_id, e.title, e.published, e.last_modified, e.scope_id,
                      e.created_at, e.created_by, e.type, a.description, a.keywords, a.image_id, a.template_id, a.parent_article_id, a.publication_date, a.sort_date, a.target_page, a.comment_webform_id";

    private static ?ArticleDao $instance = null;
    private PageDao $_page_dao;
    private ElementHolderDao $_element_holder_dao;
    private MysqlConnector $_mysql_connector;

    private function __construct() {
        $this->_page_dao = PageDao::getInstance();
        $this->_element_holder_dao = ElementHolderDao::getInstance();
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public static function getInstance(): ArticleDao {
        if (!self::$instance) {
            self::$instance = new ArticleDao();
        }
        return self::$instance;
    }

    public function getArticle($id): ?Article {
        $statement = $this->_mysql_connector->prepareStatement("SELECT " . self::$myAllColumns . " FROM
                                                                    element_holders e, articles a WHERE e.id = ?
                                                                    AND e.id = a.element_holder_id");
        $statement->bind_param("i", $id);
        $result = $this->_mysql_connector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return Article::constructFromRecord($row);
        }
        return null;
    }

    public function getArticleByElementHolderId($element_holder_id): ?Article {
        $statement = $this->_mysql_connector->prepareStatement("SELECT " . self::$myAllColumns . " FROM
                                                                    element_holders e, articles a WHERE a.element_holder_id = ?
                                                                    AND e.id = a.element_holder_id");
        $statement->bind_param("i", $element_holder_id);
        $result = $this->_mysql_connector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return Article::constructFromRecord($row);
        }
        return null;
    }

    public function getAllArticles(): array {
        $query = "SELECT " . self::$myAllColumns . " FROM element_holders e, articles a WHERE e.id = a.element_holder_id
                      order by title ASC";
        $result = $this->_mysql_connector->executeQuery($query);
        $articles = array();
        while ($row = $result->fetch_assoc()) {
            $articles[] = Article::constructFromRecord($row);
        }
        return $articles;
    }

    public function getAllChildArticles(int $parent_article_id): array {
        $query = "SELECT " . self::$myAllColumns . " FROM element_holders e, articles a WHERE e.id = a.element_holder_id
                      AND parent_article_id = " . $parent_article_id . " order by created_at DESC";
        $result = $this->_mysql_connector->executeQuery($query);
        $articles = array();
        while ($row = $result->fetch_assoc()) {
            $articles[] = Article::constructFromRecord($row);
        }
        return $articles;
    }

    public function searchArticles($keyword, $term_id): array {
        $from = " FROM element_holders e, articles a";
        $where = " WHERE
                      e.id = a.element_holder_id";
        if (!is_null($keyword) && $keyword != "")
            $where = $where . " AND e.title LIKE '" . $keyword . "%'";
        if (!is_null($term_id)) {
            $from = $from . ", articles_terms ats";
            $where = $where . " AND ats.term_id = " . $term_id . " AND ats.article_id = e.id";
        }

        $query = "SELECT DISTINCT " . self::$myAllColumns . $from . $where . " ORDER BY title";
        $result = $this->_mysql_connector->executeQuery($query);
        $articles = array();
        while ($row = $result->fetch_assoc()) {
            $articles[] = Article::constructFromRecord($row);
        }
        return $articles;
    }

    public function searchPublishedArticles($from_date, $to_date, $order_by, $order_type, $terms, $max_results): array {
        $from = " FROM element_holders e, articles a";
        $where = " WHERE
                      e.id = a.element_holder_id";
        $order = '';
        $limit = '';

        $where = $where . " AND publication_date <= now()";
        if (!is_null($to_date) && $to_date != '') {
            $where = $where . " AND publication_date <= '" . DateUtility::stringMySqlDate($to_date) . "'";
        }
        if (!is_null($from_date) && $from_date != '') {
            $where = $where . " AND publication_date > '" . DateUtility::stringMySqlDate($from_date) . "'";
        }
        if (!is_null($terms) && count($terms) > 0) {
            $from = $from . ", articles_terms at";
            foreach ($terms as $term) {
                $where = $where . " AND EXISTS(SELECT * FROM articles_terms at WHERE at.article_id = e.id AND at.term_id = " . $term->getId() . ")";
            }
        }
        if (!is_null($max_results) && $max_results != '') {
            $limit = " LIMIT " . $max_results;
        }

        if (!is_null($order_by) && $order_by != '') {
            switch ($order_by) {
                case "Alphabet":
                    $order = 'e.title';
                    break;
                case "PublicationDate":
                    $order = 'a.publication_date ' . $order_type;
                    break;
                case "SortDate":
                    $order = 'a.sort_date ' . $order_type;
                    break;
            }
        }

        $query = "SELECT DISTINCT " . self::$myAllColumns . $from . $where . " ORDER BY " . $order . $limit;
        $result = $this->_mysql_connector->executeQuery($query);
        $articles = array();
        while ($row = $result->fetch_assoc()) {
            $articles[] = Article::constructFromRecord($row);
        }
        return $articles;
    }

    public function updateArticle(Article $article): void {
        $query = "UPDATE articles SET description = '" . $this->_mysql_connector->realEscapeString($article->getDescription()) . "',
                      publication_date = '" . $article->getPublicationDate() . "',
                      sort_date = '" . $article->getSortDate() . "'";
        if (!is_null($article->getImageId()) && $article->getImageId() != '') {
            $query = $query . ", image_id = " . $article->getImageId();
        } else {
            $query = $query . ", image_id = NULL";
        }
        if (!is_null($article->getTargetPageId()) && $article->getTargetPageId() != '') {
            $query = $query . ", target_page = " . $article->getTargetPageId();
        } else {
            $query = $query . ", target_page = NULL";
        }
        if (!is_null($article->getParentArticleId())) {
            $query = $query . ", parent_article_id = " . $article->getParentArticleId();
        } else {
            $query = $query . ", parent_article_id = NULL";
        }
        if (!is_null($article->getTemplateId())) {
            $query = $query . ", template_id = " . $article->getTemplateId();
        } else {
            $query = $query . ", template_id = NULL";
        }
        if ($article->getKeywords()) {
            $query = $query . ", keywords = '" . $article->getKeywords() . "'";
        } else {
            $query = $query . ", keywords = NULL";
        }
        if (!is_null($article->getCommentWebFormId()) && $article->getCommentWebFormId() != '') {
            $query = $query . ", comment_webform_id = " . $article->getCommentWebFormId();
        } else {
            $query = $query . ", comment_webform_id = NULL";
        }
        $query = $query . " WHERE element_holder_id = " . $article->getId();
        $this->_mysql_connector->executeQuery($query);
        $this->_element_holder_dao->update($article);
    }

    public function deleteArticle($article): void {
        $this->_element_holder_dao->delete($article);
    }

    public function createArticle(): Article {
        $new_article = new Article();
        $new_article->setPublished(false);
        $new_article->setTitle('Nieuw artikel');
        $new_article->setCreatedById(Authenticator::getCurrentUser()->getId());
        $new_article->setType(ELEMENT_HOLDER_ARTICLE);

        $this->persistArticle($new_article);
        return $new_article;
    }

    private function persistArticle($article): void {
        $this->_element_holder_dao->persist($article);
        $query = "INSERT INTO articles (description, image_id, element_holder_id, sort_date, publication_date, target_page) VALUES
                      (NULL, NULL, " . $article->getId() . ", now(), now(), NULL)";
        $this->_mysql_connector->executeQuery($query);
    }

    public function getArticleComments(int $article_id): array {
        $query = "SELECT * FROM article_comments WHERE article_id = ? AND parent IS NULL";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param('i', $article_id);
        $result = $this->_mysql_connector->executeStatement($statement);
        $comments = array();
        while ($row = $result->fetch_assoc()) {
            $comments[] = ArticleComment::constructFromRecord($row);
        }
        return $comments;
    }

    public function getChildArticleComments(int $comment_id): array {
        $query = "SELECT * FROM article_comments WHERE parent = ?";
        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param('i', $comment_id);
        $result = $this->_mysql_connector->executeStatement($statement);
        $comments = array();
        while ($row = $result->fetch_assoc()) {
            $comments[] = ArticleComment::constructFromRecord($row);
        }
        return $comments;
    }

    public function getAllTerms(): array {
        $query = "SELECT * FROM article_terms ORDER by name ASC";
        $terms = array();
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            $terms[] = ArticleTerm::constructFromRecord($row);
        }
        return $terms;
    }

    public function getTerm($id): ?ArticleTerm {
        $query = "SELECT * FROM article_terms WHERE id = " . $id;
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ArticleTerm::constructFromRecord($row);
        }
        return null;
    }

    public function createTerm($term_name): ArticleTerm {
        $new_term = new ArticleTerm();
        $new_term->setName($term_name);
        $postfix = 1;
        while (!is_null($this->getTermByName($new_term->getName()))) {
            $new_term->setName($term_name . " " . $postfix);
            $postfix++;
        }
        $this->persistTerm($new_term);
        return $new_term;
    }

    public function getTermByName($name): ?ArticleTerm {
        $query = "SELECT * FROM article_terms WHERE name = '" . $name . "'";
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return ArticleTerm::constructFromRecord($row);
        }
        return null;
    }

    private function persistTerm($term): void {
        $query = "INSERT INTO article_terms (name) VALUES  ('" . $term->getName() . "')";
        $this->_mysql_connector->executeQuery($query);
        $term->setId($this->_mysql_connector->getInsertId());
    }

    public function updateTerm($term): void {
        $query = "UPDATE article_terms SET name = '" . $term->getName() .
            "' WHERE id = " . $term->getId();
        $this->_mysql_connector->executeQuery($query);
    }

    public function deleteTerm($term): void {
        $query = "DELETE FROM article_terms WHERE id = " . $term->getId();
        $this->_mysql_connector->executeQuery($query);
    }

    public function getTermsForArticle($article_id): array {
        $query = "SELECT at.id, at.name FROM article_terms at, articles_terms ats,
                      element_holders e WHERE ats.article_id = " . $article_id . " AND ats.article_id =
                      e.id AND at.id = ats.term_id";

        $result = $this->_mysql_connector->executeQuery($query);
        $terms = array();
        while ($row = $result->fetch_assoc()) {
            $terms[] = ArticleTerm::constructFromRecord($row);
        }
        return $terms;
    }

    public function addTermToArticle($term_id, $article): void {
        $query = "INSERT INTO articles_terms (article_id, term_id) VALUES (" . $article->getId() . ", " . $term_id . ")";
        $this->_mysql_connector->executeQuery($query);
    }

    public function deleteTermFromArticle($term_id, $article): void {
        $query = "DELETE FROM articles_terms WHERE article_id = " . $article->getId() . "
                      AND term_id = " . $term_id;
        $this->_mysql_connector->executeQuery($query);
    }

    public function addTargetPage($target_page_id): void {
        $duplicate_check_query = "SELECT count(*) AS number_of FROM article_target_pages WHERE element_holder_id = " . $target_page_id;
        $result = $this->_mysql_connector->executeQuery($duplicate_check_query);

        $count = 0;
        while ($row = $result->fetch_assoc()) {
            $count = $row['number_of'];
            break;
        }

        if ($count == 0) {
            $query = "INSERT INTO article_target_pages (element_holder_id, is_default) VALUES (" . $target_page_id . ", 0)";
            $this->_mysql_connector->executeQuery($query);

            // check if only one target page is present
            $this->updateDefaultArticleTargetPage();
        }
    }

    private function updateDefaultArticleTargetPage(): void {
        $target_pages = $this->getTargetPages();
        if (!is_null($target_pages) && count($target_pages) == 1) {
            $query = "UPDATE article_target_pages SET is_default = 1";
            $this->_mysql_connector->executeQuery($query);
        }
    }

    public function getTargetPages(): array {
        $query = "SELECT element_holder_id FROM article_target_pages";
        $result = $this->_mysql_connector->executeQuery($query);
        $pages = array();
        while ($row = $result->fetch_assoc()) {
            $pages[] = $this->_page_dao->getPage($row['element_holder_id']);
        }
        return $pages;
    }

    public function deleteTargetPage($target_page_id): void {
        $query = "DELETE FROM article_target_pages where element_holder_id = " . $target_page_id;
        $this->_mysql_connector->executeQuery($query);

        // check if only one target page is present
        $this->updateDefaultArticleTargetPage();
    }

    public function getDefaultTargetPage(): ?Page {
        $query = "SELECT element_holder_id FROM article_target_pages WHERE is_default = 1";
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return $this->_page_dao->getPage($row["element_holder_id"]);
        }
        return null;
    }

    public function setDefaultArticleTargetPage($target_page_id): void {
        $query1 = "UPDATE article_target_pages SET is_default = 0 WHERE is_default = 1";
        $query2 = "UPDATE article_target_pages SET is_default = 1 WHERE element_holder_id = " . $target_page_id;
        $this->_mysql_connector->executeQuery($query1);
        $this->_mysql_connector->executeQuery($query2);
    }

}

?>
