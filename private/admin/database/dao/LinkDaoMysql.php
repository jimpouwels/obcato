<?php
require_once CMS_ROOT . "/database/dao/LinkDao.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/core/model/Link.php";

class LinkDaoMysql implements LinkDao {

    private static ?LinkDaoMysql $instance = null;
    private MysqlConnector $_mysql_connector;

    private function __construct() {
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public static function getInstance(): LinkDaoMysql {
        if (!self::$instance) {
            self::$instance = new LinkDaoMysql();
        }
        return self::$instance;
    }

    public function createLink(int $element_holder_id, $title): Link {
        $new_link = new Link();
        $new_link->setTitle($title);
        $new_link->setCode($new_link->getId());
        $new_link->setParentElementHolderId($element_holder_id);
        $new_link->setType(Link::INTERNAL);
        $this->persistLink($new_link);
        return $new_link;
    }

    public function persistLink(Link $new_link): void {
        $query = "INSERT INTO links (title, target_address, type, code, target, target_element_holder, parent_element_holder)
                      VALUES ('" . $new_link->getTitle() . "', NULL, '" . $new_link->getType() .
            "', '" . $new_link->getCode() . "', '_self', NULL, " . $new_link->getParentElementHolderId() . ")";
        $this->_mysql_connector->executeQuery($query);
        $new_link->setId($this->_mysql_connector->getInsertId());
    }

    public function getLinksForElementHolder(int $element_holder_id): array {
        $query = "SELECT * FROM links WHERE parent_element_holder = " . $element_holder_id;
        $result = $this->_mysql_connector->executeQuery($query);
        $links = array();
        while ($row = $result->fetch_assoc()) {
            $links[] = Link::constructFromRecord($row);
        }
        return $links;
    }

    public function deleteLink(Link $link) {
        $statement = $this->_mysql_connector->prepareStatement('DELETE FROM links WHERE id = ?');
        $link_id = $link->getId();
        $statement->bind_param('i', $link_id);
        $this->_mysql_connector->executeStatement($statement);
    }

    public function updateLink(Link $link) {
        if (!is_null($link->getTargetElementHolder()) && $link->getTargetElementHolder() != '')
            $link->setType(Link::INTERNAL);
        else
            $link->setType(Link::EXTERNAL);

        $query = "UPDATE links SET title = '" . $link->getTitle() . "', target_address = '" . $link->getTargetAddress() . "',
                      code = '" . $link->getCode() . "', target = '" . $link->getTarget() . "', type = '" . $link->getType() . "'";

        if ($link->getTargetElementHolderId() != '' && !is_null($link->getTargetElementHolderId()))
            $query = $query . ", target_element_holder = " . $link->getTargetElementHolderId();
        else
            $query = $query . ", target_element_holder = NULL";
        $query = $query . " WHERE id = " . $link->getId();
        $this->_mysql_connector->executeQuery($query);
    }

    public function getBrokenLinks(): array {
        $query = "SELECT * FROM links WHERE target_address IS NULL AND target_element_holder IS NULL";
        $result = $this->_mysql_connector->executeQuery($query);
        $links = array();
        while ($row = $result->fetch_assoc()) {
            $links[] = Link::constructFromRecord($row);
        }
        return $links;
    }

}

?>
