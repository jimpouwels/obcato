<?php


defined('_ACCESS') or die;

require_once CMS_ROOT . '/database/dao/PageDao.php';
require_once CMS_ROOT . "/database/mysql_connector.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ElementHolderDaoMysql.php";
require_once CMS_ROOT . "/database/dao/BlockDaoMysql.php";
require_once CMS_ROOT . "/database/dao/TemplateDaoMysql.php";
require_once CMS_ROOT . "/core/model/page.php";
require_once CMS_ROOT . "/database/dao/AuthorizationDaoMysql.php";

class PageDaoMysql implements PageDao {

    private static string $myAllColumns = "e.id, e.template_id, e.last_modified, e.title, e.published, e.scope_id,
                      e.created_at, e.created_by, e.type, p.navigation_title, p.keywords, p.description, p.parent_id, p.show_in_navigation,
                      p.include_in_searchindex, p.follow_up, p.is_homepage";
    private static ?PageDaoMysql $instance = null;
    private MysqlConnector $_mysql_connector;
    private ElementHolderDao $_element_holder_dao;

    private function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
        $this->_element_holder_dao = ElementHolderDaoMysql::getInstance();
    }

    public static function getInstance(): PageDaoMysql {
        if (!self::$instance) {
            self::$instance = new PageDaoMysql();
        }
        return self::$instance;
    }

    public function getAllPages(): array {
        $pages = array();
        $statement = $this->_mysql_connector->prepareStatement("SELECT " . self::$myAllColumns . " FROM pages p,
                                                                     element_holders e WHERE e.id = p.element_holder_id");
        $result = $this->_mysql_connector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            $pages[] = Page::constructFromRecord($row);
        }
        return $pages;
    }

    public function getPage(?int $id): ?Page {
        if (!$id) {
            return null;
        }
        $statement = $this->_mysql_connector->prepareStatement("SELECT " . self::$myAllColumns . " FROM pages p,
            element_holders e WHERE e.id = ? AND e.id = p.element_holder_id");
        $statement->bind_param("i", $id);
        $result = $this->_mysql_connector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return Page::constructFromRecord($row);
        }
        return null;
    }

    public function getPageByElementHolderId(?int $element_holder_id): ?Page {
        if ($element_holder_id) {
            $statement = $this->_mysql_connector->prepareStatement("SELECT " . self::$myAllColumns . " FROM pages p,
                                                                        element_holders e WHERE p.element_holder_id = ? AND e.id = p.element_holder_id");
            $statement->bind_param("i", $element_holder_id);
            $result = $this->_mysql_connector->executeStatement($statement);
            while ($row = $result->fetch_assoc()) {
                return Page::constructFromRecord($row);
            }
        }
        return null;
    }

    public function getRootPage(): ?Page {
        $query = "SELECT " . self::$myAllColumns . " FROM pages p, element_holders e WHERE p.parent_id IS NULL
                      AND e.id = p.element_holder_id";
        $result = $this->_mysql_connector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return Page::constructFromRecord($row);
        }
        return null;
    }

    public function getSubPages(Page $page): array {
        $statement = $this->_mysql_connector->prepareStatement("SELECT " . self::$myAllColumns . " FROM pages p,
                                                                    element_holders e WHERE p.parent_id = ?
                                                                    AND p.element_holder_id = e.id ORDER BY p.follow_up");
        $id = $page->getId();
        $statement->bind_param("i", $id);
        $result = $this->_mysql_connector->executeStatement($statement);
        $pages = array();
        while ($row = $result->fetch_assoc()) {
            $pages[] = Page::constructFromRecord($row);
        }
        return $pages;
    }

    public function persist(Page $page): void {
        $this->_element_holder_dao->persist($page);
        $query = "INSERT INTO pages (navigation_title, parent_id, show_in_navigation, include_in_searchindex, element_holder_id,
                     follow_up, is_homepage, description) VALUES ('" . $page->getNavigationTitle() . "', " . $page->getParentId() . ","
            . $page->getShowInNavigation() . ", 1, " . $page->getId() . ", 1, 0, '')";
        $this->_mysql_connector->executeQuery($query);
    }

    public function updatePage(Page $page): void {
        $query = "UPDATE pages SET navigation_title = ?, keywords = ?, show_in_navigation = ?, include_in_searchindex = ?, follow_up = ?, `description` = ? WHERE element_holder_id = ?";

        $navigation_title = $page->getNavigationTitle();
        $keywords = $page->getKeywords();
        $show_in_navigation = $page->getShowInNavigation() ? 1 : 0;
        $include_in_search_engine = $page->getIncludeInSearchEngine() ? 1 : 0;
        $follow_Up = $page->getFollowUp();
        $description = $page->getDescription();
        $element_holder_id = $page->getId();

        $statement = $this->_mysql_connector->prepareStatement($query);
        $statement->bind_param('ssiiisi', $navigation_title, $keywords, $show_in_navigation, $include_in_search_engine, $follow_Up, $description, $element_holder_id);
        $this->_mysql_connector->executeStatement($statement);
        $this->_element_holder_dao->update($page);
    }

    public function deletePage(Page $page): void {
        $this->_element_holder_dao->delete($page);
    }

    public function isLast(Page $page): bool {
        $mysql_database = MysqlConnector::getInstance();

        $query = "SELECT element_holder_id FROM pages WHERE follow_up = (SELECT MAX(follow_up)"
            . " FROM pages WHERE parent_id = " . $page->getParentId() . ") AND"
            . " parent_id = " . $page->getParentId();
        $result = $mysql_database->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            $id = $row['element_holder_id'];
            break;
        }
        $last = false;
        if ($page->getId() == $id) {
            $last = true;
        }
        return $last;
    }

    public function isFirst(Page $page): bool {
        $mysql_database = MysqlConnector::getInstance();

        $query = "SELECT element_holder_id FROM pages WHERE follow_up = (SELECT MIN(follow_up)"
            . " FROM pages WHERE parent_id = " . $page->getParentId() . ") AND"
            . " parent_id = " . $page->getParentId();

        $result = $mysql_database->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            $id = $row['element_holder_id'];
            break;
        }
        $first = false;
        if ($page->getId() == $id) {
            $first = true;
        }
        return $first;
    }

    public function searchByTerm(string $term): array {
        $query = "SELECT " . self::$myAllColumns . " FROM pages p, element_holders e WHERE e.id = p.element_holder_id
                      AND title like '" . $term . "%'";
        $result = $this->_mysql_connector->executeQuery($query);
        $pages = array();
        while ($row = $result->fetch_assoc()) {
            $pages[] = Page::constructFromRecord($row);
        }
        return $pages;
    }

    public function moveUp(Page $page): void {
        $query1 = "UPDATE pages SET follow_up = (follow_up + 1) WHERE follow_up = " . ($page->getFollowUp() - 1);
        $query2 = "UPDATE pages SET follow_up = (follow_up - 1) WHERE element_holder_id = " . $page->getId();

        $this->_mysql_connector->executeQuery($query1);
        $this->_mysql_connector->executeQuery($query2);
    }

    public function moveDown(Page $page): void {
        $query1 = "UPDATE pages SET follow_up = (follow_up - 1) WHERE follow_up = " . ($page->getFollowUp() + 1);
        $query2 = "UPDATE pages SET follow_up = (follow_up + 1) WHERE element_holder_id = " . $page->getId();
        $this->_mysql_connector->executeQuery($query1);
        $this->_mysql_connector->executeQuery($query2);
    }

    public function getParent(Page $page): ?Page {
        $parent = null;
        if ($page->getParentId()) {
            $parent = $this->getPage($page->getParentId());
        }
        return $parent;
    }

    public function getParents(Page $page): array {
        $parents = array();
        array_unshift($parents, $page);
        $parent = $this->getParent($page);
        if (!is_null($parent)) {
            $parents = array_merge($this->getParents($parent), $parents);
        }
        return $parents;
    }
}

?>
