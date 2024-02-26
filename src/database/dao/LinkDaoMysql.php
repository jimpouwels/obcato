<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\core\model\Link;
use Obcato\Core\database\MysqlConnector;

class LinkDaoMysql implements LinkDao {

    private static ?LinkDaoMysql $instance = null;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): LinkDaoMysql {
        if (!self::$instance) {
            self::$instance = new LinkDaoMysql();
        }
        return self::$instance;
    }

    public function createLink(int $elementHolderId, $title): Link {
        $newLink = new Link();
        $newLink->setTitle($title);
        $newLink->setCode($newLink->getId());
        $newLink->setParentElementHolderId($elementHolderId);
        $newLink->setType(Link::INTERNAL);
        $this->persistLink($newLink);
        return $newLink;
    }

    public function persistLink(Link $newLink): void {
        $query = "INSERT INTO links (title, target_address, type, code, target, target_element_holder, parent_element_holder)
                      VALUES ('" . $newLink->getTitle() . "', NULL, '" . $newLink->getType() .
            "', '" . $newLink->getCode() . "', '_self', NULL, " . $newLink->getParentElementHolderId() . ")";
        $this->mysqlConnector->executeQuery($query);
        $newLink->setId($this->mysqlConnector->getInsertId());
    }

    public function getLinksForElementHolder(int $elementHolderId): array {
        $query = "SELECT * FROM links WHERE parent_element_holder = " . $elementHolderId;
        $result = $this->mysqlConnector->executeQuery($query);
        $links = array();
        while ($row = $result->fetch_assoc()) {
            $links[] = Link::constructFromRecord($row);
        }
        return $links;
    }

    public function deleteLink(Link $link): void {
        $statement = $this->mysqlConnector->prepareStatement('DELETE FROM links WHERE id = ?');
        $linkId = $link->getId();
        $statement->bind_param('i', $linkId);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function updateLink(Link $link): void {
        if (!is_null($link->getTargetElementHolder()) && $link->getTargetElementHolder() != '') {
            $link->setType(Link::INTERNAL);
        } else {
            $link->setType(Link::EXTERNAL);
        }

        $query = "UPDATE links SET title = '" . $link->getTitle() . "', target_address = ?,
                      code = '" . $link->getCode() . "', target = '" . $link->getTarget() . "', type = '" . $link->getType() . "'";

        if ($link->getTargetElementHolderId() != '' && !is_null($link->getTargetElementHolderId())) {
            $query = $query . ", target_element_holder = " . $link->getTargetElementHolderId();
        } else {
            $query = $query . ", target_element_holder = NULL";
        }
        $query = $query . " WHERE id = " . $link->getId();

        $statement = $this->mysqlConnector->prepareStatement($query);
        $targetAddress = $link->getTargetAddress();
        $statement->bind_param('s', $targetAddress);
        $this->mysqlConnector->executeStatement($statement);
    }

    public function getBrokenLinks(): array {
        $query = "SELECT * FROM links WHERE target_address IS NULL AND target_element_holder IS NULL";
        $result = $this->mysqlConnector->executeQuery($query);
        $links = array();
        while ($row = $result->fetch_assoc()) {
            $links[] = Link::constructFromRecord($row);
        }
        return $links;
    }

}