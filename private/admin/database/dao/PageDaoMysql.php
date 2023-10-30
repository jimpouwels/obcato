<?php
require_once CMS_ROOT . '/database/dao/PageDao.php';
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ElementHolderDaoMysql.php";
require_once CMS_ROOT . "/database/dao/BlockDaoMysql.php";
require_once CMS_ROOT . "/database/dao/TemplateDaoMysql.php";
require_once CMS_ROOT . "/modules/pages/model/Page.php";
require_once CMS_ROOT . "/database/dao/AuthorizationDaoMysql.php";

class PageDaoMysql implements PageDao {

    private static string $myAllColumns = "e.id, e.template_id, e.last_modified, e.title, e.published, e.scope_id,
                      e.created_at, e.created_by, e.type, p.navigation_title, p.keywords, p.description, p.parent_id, p.show_in_navigation,
                      p.include_in_searchindex, p.follow_up, p.is_homepage";
    private static ?PageDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;
    private ElementHolderDao $elementHolderDao;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
        $this->elementHolderDao = ElementHolderDaoMysql::getInstance();
    }

    public static function getInstance(): PageDaoMysql {
        if (!self::$instance) {
            self::$instance = new PageDaoMysql();
        }
        return self::$instance;
    }

    public function getHomepage(): ?Page {
        $statement = $this->mysqlConnector->prepareStatement("SELECT " . self::$myAllColumns . " FROM pages p, element_holders e WHERE p.is_homepage = 1");
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return Page::constructFromRecord($row);
        }
        return null;
    }

    public function getAllPages(): array {
        $pages = array();
        $statement = $this->mysqlConnector->prepareStatement("SELECT " . self::$myAllColumns . " FROM pages p,
                                                                     element_holders e WHERE e.id = p.element_holder_id");
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            $pages[] = Page::constructFromRecord($row);
        }
        return $pages;
    }

    public function getPage(?int $id): ?Page {
        if (!$id) {
            return null;
        }
        $statement = $this->mysqlConnector->prepareStatement("SELECT " . self::$myAllColumns . " FROM pages p,
            element_holders e WHERE e.id = ? AND e.id = p.element_holder_id");
        $statement->bind_param("i", $id);
        $result = $this->mysqlConnector->executeStatement($statement);
        while ($row = $result->fetch_assoc()) {
            return Page::constructFromRecord($row);
        }
        return null;
    }

    public function getPageByElementHolderId(?int $elementHolderId): ?Page {
        if ($elementHolderId) {
            $statement = $this->mysqlConnector->prepareStatement("SELECT " . self::$myAllColumns . " FROM pages p,
                                                                        element_holders e WHERE p.element_holder_id = ? AND e.id = p.element_holder_id");
            $statement->bind_param("i", $elementHolderId);
            $result = $this->mysqlConnector->executeStatement($statement);
            while ($row = $result->fetch_assoc()) {
                return Page::constructFromRecord($row);
            }
        }
        return null;
    }

    public function getRootPage(): ?Page {
        $query = "SELECT " . self::$myAllColumns . " FROM pages p, element_holders e WHERE p.parent_id IS NULL
                      AND e.id = p.element_holder_id";
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return Page::constructFromRecord($row);
        }
        return null;
    }

    public function getSubPages(Page $page): array {
        $statement = $this->mysqlConnector->prepareStatement("SELECT " . self::$myAllColumns . " FROM pages p,
                                                                    element_holders e WHERE p.parent_id = ?
                                                                    AND p.element_holder_id = e.id ORDER BY p.follow_up");
        $id = $page->getId();
        $statement->bind_param("i", $id);
        $result = $this->mysqlConnector->executeStatement($statement);
        $pages = array();
        while ($row = $result->fetch_assoc()) {
            $pages[] = Page::constructFromRecord($row);
        }
        return $pages;
    }

    public function persist(Page $page): void {
        $this->elementHolderDao->persist($page);
        $statement = $this->mysqlConnector->prepareStatement("INSERT INTO pages (navigation_title, parent_id, show_in_navigation, include_in_searchindex, element_holder_id,
                     follow_up, is_homepage, description) VALUES (?, ?, ?, 1, ?, 1, 0, '')");
        $navigationTitle = $page->getNavigationTitle();
        $parentId = $page->getParentId();
        $showInNavigation = $page->getShowInNavigation() ? 1 : 0;
        $id = $page->getId();
        $statement->bind_param("siii", $navigationTitle, $parentId, $showInNavigation, $id);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function updatePage(Page $page): void {
        $query = "UPDATE pages SET navigation_title = ?, keywords = ?, show_in_navigation = ?, include_in_searchindex = ?, follow_up = ?, `description` = ? WHERE element_holder_id = ?";

        $navigationTitle = $page->getNavigationTitle();
        $keywords = $page->getKeywords();
        $showInNavigation = $page->getShowInNavigation() ? 1 : 0;
        $includeInSearchEngine = $page->getIncludeInSearchEngine() ? 1 : 0;
        $followUp = $page->getFollowUp();
        $description = $page->getDescription();
        $elementHolderId = $page->getId();

        $statement = $this->mysqlConnector->prepareStatement($query);
        $statement->bind_param('ssiiisi', $navigationTitle, $keywords, $showInNavigation, $includeInSearchEngine, $followUp, $description, $elementHolderId);
        $this->mysqlConnector->executeStatement($statement);
        $this->elementHolderDao->update($page);
    }

    public function deletePage(Page $page): void {
        $this->elementHolderDao->delete($page);
    }

    public function isLast(Page $page): bool {
        $query = "SELECT element_holder_id FROM pages WHERE follow_up = (SELECT MAX(follow_up)"
            . " FROM pages WHERE parent_id = " . $page->getParentId() . ") AND"
            . " parent_id = " . $page->getParentId();
        $result = $this->mysqlConnector->executeQuery($query);
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
        $query = "SELECT element_holder_id FROM pages WHERE follow_up = (SELECT MIN(follow_up)"
            . " FROM pages WHERE parent_id = " . $page->getParentId() . ") AND"
            . " parent_id = " . $page->getParentId();

        $result = $this->mysqlConnector->executeQuery($query);
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
        $result = $this->mysqlConnector->executeQuery($query);
        $pages = array();
        while ($row = $result->fetch_assoc()) {
            $pages[] = Page::constructFromRecord($row);
        }
        return $pages;
    }

    public function moveUp(Page $page): void {
        $query1 = "UPDATE pages SET follow_up = (follow_up + 1) WHERE follow_up = " . ($page->getFollowUp() - 1);
        $query2 = "UPDATE pages SET follow_up = (follow_up - 1) WHERE element_holder_id = " . $page->getId();

        $this->mysqlConnector->executeQuery($query1);
        $this->mysqlConnector->executeQuery($query2);
    }

    public function moveDown(Page $page): void {
        $query1 = "UPDATE pages SET follow_up = (follow_up - 1) WHERE follow_up = " . ($page->getFollowUp() + 1);
        $query2 = "UPDATE pages SET follow_up = (follow_up + 1) WHERE element_holder_id = " . $page->getId();
        $this->mysqlConnector->executeQuery($query1);
        $this->mysqlConnector->executeQuery($query2);
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
