<?php
require_once CMS_ROOT . "/database/dao/BlockDao.php";
require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/database/dao/ElementDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ElementHolderDaoMysql.php";
require_once CMS_ROOT . "/modules/blocks/model/Block.php";
require_once CMS_ROOT . "/modules/blocks/model/BlockPosition.php";
require_once CMS_ROOT . "/database/dao/AuthorizationDaoMysql.php";

class BlockDaoMysql implements BlockDao {

    public static string $myAllColumns = "e.id, e.template_id, e.last_modified, e.title, e.published, e.scope_id, 
                      e.created_at, e.created_by, e.type, b.position_id";
    private static ?BlockDaoMysql $instance = null;
    private ElementHolderDao $elementHolderDao;
    private MysqlConnector $mysqlConnector;

    private function __construct() {
        $this->elementHolderDao = ElementHolderDaoMysql::getInstance();
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public static function getInstance(): BlockDaoMysql {
        if (!self::$instance) {
            self::$instance = new BlockDaoMysql();
        }
        return self::$instance;
    }

    public function getAllBlocks(): array {
        $query = "SELECT " . self::$myAllColumns . " FROM element_holders e, blocks b WHERE e.id = b.element_holder_id";
        $result = $this->mysqlConnector->executeQuery($query);
        $blocks = array();
        while ($row = $result->fetch_assoc()) {
            $blocks[] = Block::constructFromRecord($row);
        }
        return $blocks;
    }

    public function getBlocksByPosition(BlockPosition $position): array {
        $statement = $this->mysqlConnector->prepareStatement("SELECT " . self::$myAllColumns . " FROM
                                                                    element_holders e, blocks b WHERE b.position_id = ?
                                                                    AND e.id = b.element_holder_id");
        $position_id = $position->getId();
        $statement->bind_param("i", $position_id);
        $result = $this->mysqlConnector->executeStatement($statement);
        $blocks = array();
        while ($row = $result->fetch_assoc()) {
            $blocks[] = Block::constructFromRecord($row);
        }
        return $blocks;
    }

    public function getBlocksWithoutPosition(): array {
        $query = "SELECT " . self::$myAllColumns . " FROM element_holders e, blocks b WHERE b.position_id IS  
                     NULL AND e.id = b.element_holder_id";
        $result = $this->mysqlConnector->executeQuery($query);
        $blocks = array();
        while ($row = $result->fetch_assoc()) {
            $blocks[] = Block::constructFromRecord($row);
        }
        return $blocks;
    }

    public function getBlocksByPageAndPosition(Page $page, string $position_name): array {
        $query = "SELECT " . self::$myAllColumns .
            " FROM element_holders e, blocks b, blocks_pages bp, block_positions bps" .
            " WHERE e.id = b.element_holder_id" .
            " AND bp.block_id = e.id" .
            " AND bp.page_id = " . $page->getId() .
            " AND bps.name = '" . $position_name . "'" .
            " AND b.position_id = bps.id";
        $result = $this->mysqlConnector->executeQuery($query);
        $blocks = array();
        while ($row = $result->fetch_assoc()) {
            $blocks[] = Block::constructFromRecord($row);
        }
        return $blocks;
    }

    public function getBlocksByPage(Page $page): array {
        $query = "SELECT " . self::$myAllColumns . " FROM element_holders e, blocks b, blocks_pages bps WHERE e.id = b.element_holder_id
                      AND bps.page_id = " . $page->getId() . " AND bps.block_id = e.id";
        $result = $this->mysqlConnector->executeQuery($query);
        $blocks = array();

        while ($row = $result->fetch_assoc()) {
            $blocks[] = Block::constructFromRecord($row);
        }
        return $blocks;
    }

    public function getBlockPositions(): array {
        $query = "SELECT * FROM block_positions ORDER BY name";
        $result = $this->mysqlConnector->executeQuery($query);
        $positions = array();
        while ($row = $result->fetch_assoc()) {
            $positions[] = BlockPosition::constructFromRecord($row);
        }
        return $positions;
    }

    public function getBlockPosition($position_id): ?BlockPosition {
        if (!is_null($position_id) && $position_id != '') {
            $query = "SELECT * FROM block_positions WHERE id = " . $position_id;
            $result = $this->mysqlConnector->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                return BlockPosition::constructFromRecord($row);
            }
        }
        return null;
    }

    public function getBlock($id): ?Block {
        $query = "SELECT " . self::$myAllColumns . " FROM element_holders e, blocks b WHERE e.id = " . $id
            . " AND e.id = b.element_holder_id";
        $result = $this->mysqlConnector->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            return Block::constructFromRecord($row);
        }
        return null;
    }

    public function createBlock(): Block {
        $new_block = new Block();
        $new_block->setPublished(false);
        $new_block->setTitle('Nieuw block');
        $new_block->setCreatedById(Authenticator::getCurrentUser()->getId());
        $new_block->setType(ELEMENT_HOLDER_BLOCK);
        $this->persistBlock($new_block);
        return $new_block;
    }

    private function persistBlock($block): void {
        $this->elementHolderDao->persist($block);
        $query = "INSERT INTO blocks (position_id, element_holder_id) VALUES (NULL, " . $block->getId() . ")";
        $this->mysqlConnector->executeQuery($query);
    }

    public function updateBlock($block): void {
        $query = "UPDATE blocks SET";
        if ($block->getPositionId() != '' && !is_null($block->getPositionId()))
            $query = $query . " position_id = " . $block->getPositionId();
        else
            $query = $query . " position_id = NULL";
        $query .= " WHERE element_holder_id = " . $block->getId();
        $this->mysqlConnector->executeQuery($query);
        $this->elementHolderDao->update($block);
    }

    public function deleteBlock($block): void {
        $this->elementHolderDao->delete($block);
    }

    public function createBlockPosition(): BlockPosition {
        $new_position = new BlockPosition();
        $postfix = 1;
        while (!is_null($this->getBlockPositionByName($new_position->getName()))) {
            $new_position->setName("nieuwe_positie_" . $postfix);
            $postfix++;
        }
        $this->persistBlockPosition($new_position);
        return $new_position;
    }

    public function getBlockPositionByName($position_name): ?BlockPosition {
        if (!is_null($position_name) && $position_name != '') {
            $query = "SELECT * FROM block_positions WHERE name = '" . $position_name . "'";
            $result = $this->mysqlConnector->executeQuery($query);
            while ($row = $result->fetch_assoc()) {
                return BlockPosition::constructFromRecord($row);
            }
        }
        return null;
    }

    private function persistBlockPosition($position): void {
        $query = "INSERT INTO block_positions (name, explanation) VALUES  ('" . $position->getName() . "', NULL)";
        $this->mysqlConnector->executeQuery($query);
        $position->setId($this->mysqlConnector->getInsertId());
    }

    public function updateBlockPosition($position): void {
        $query = "UPDATE block_positions SET name = '" . $position->getName() . "'
                     , explanation = '" . $position->getExplanation() . "' WHERE id = " . $position->getId();
        $this->mysqlConnector->executeQuery($query);
    }

    public function deleteBlockPosition($position): void {
        $query = "DELETE FROM block_positions WHERE id = " . $position->getId();
        $this->mysqlConnector->executeQuery($query);
    }

    public function addBlockToPage($block_id, $page): void {
        $query = "INSERT INTO blocks_pages (page_id, block_id) VALUES (" . $page->getId() . ", " . $block_id . ")";
        $this->mysqlConnector->executeQuery($query);
    }

    public function deleteBlockFromPage($block_id, $page): void {
        $query = "DELETE FROM blocks_pages WHERE page_id = " . $page->getId() . "
                      AND block_id = " . $block_id;
        $this->mysqlConnector->executeQuery($query);
    }
}

?>