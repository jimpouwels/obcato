<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "core/model/element_holder.php";
require_once CMS_ROOT . "database/dao/block_dao.php";
require_once CMS_ROOT . "database/dao/page_dao.php";

class Page extends ElementHolder {

    const ElementHolderType = "ELEMENT_HOLDER_PAGE";
    private static int $SCOPE = 5;
    private PageDao $_page_dao;
    private ?string $_description = null;
    private string $_navigation_title;
    private bool $_include_in_search_engine;
    private ?int $_parent_id;
    private ?string $_keywords = null;
    private bool $_show_in_navigation;
    private int $_followup;
    private bool $_is_homepage;

    public function __construct() {
        parent::__construct(self::$SCOPE);
        $this->_page_dao = PageDao::getInstance();
    }

    public static function constructFromRecord(array $row): Page {
        $page = new Page();
        $page->initFromDb($row);
        return $page;
    }

    protected function initFromDb(array $row): void {
        $this->setParentId($row['parent_id']);
        $this->setDescription($row['description']);
        $this->setKeywords($row['keywords']);
        $this->setNavigationTitle($row['navigation_title']);
        $this->setShowInNavigation($row['show_in_navigation'] == 1);
        $this->setIncludeInSearchEngine($row['include_in_searchindex'] == 1);
        $this->setFollowUp($row['follow_up']);
        $this->setIsHomepage($row['is_homepage'] == 1);
        parent::initFromDb($row);
    }

    public function setIsHomepage(bool $is_homepage): void {
        $this->_is_homepage = $is_homepage;
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

    public function getNavigationTitle(): string {
        return $this->_navigation_title;
    }

    public function setNavigationTitle(string $navigation_title): void {
        $this->_navigation_title = $navigation_title;
    }

    public function getParentId(): int {
        return $this->_parent_id;
    }

    public function setParentId(?int $parent_id): void {
        $this->_parent_id = $parent_id;
    }

    public function setParent(Page $parent): void {
        $this->_parent_id = $parent->getId();
    }

    public function getIncludeInSearchEngine(): bool {
        return $this->_include_in_search_engine;
    }

    public function setIncludeInSearchEngine(bool $include_in_search_engine): void {
        $this->_include_in_search_engine = $include_in_search_engine;
    }

    public function getShowInNavigation(): bool {
        return $this->_show_in_navigation;
    }

    public function setShowInNavigation(bool $show_in_navigation): void {
        $this->_show_in_navigation = $show_in_navigation;
    }

    public function getFollowup(): int {
        return $this->_followup;
    }

    public function setFollowUp(int $follow_up): void {
        $this->_followup = $follow_up;
    }

    public function isHomepage(): bool {
        return $this->_is_homepage;
    }

    public function isLast(): bool {
        return $this->_page_dao->isLast($this);
    }

    public function isFirst(): bool {
        return $this->_page_dao->isFirst($this);
    }

    public function getParents(): array {
        $parents = array();
        array_unshift($parents, $this);
        $parent = $this->getParent();
        if (!is_null($parent)) {
            $parents = array_merge($parent->getParents(), $parents);
        }
        return $parents;
    }

    public function getParent(): ?Page {
        $parent = null;
        if (!is_null($this->_parent_id)) {
            $parent = $this->_page_dao->getPage($this->_parent_id);
        }
        return $parent;
    }

    public function getSubPages(): array {
        return $this->_page_dao->getSubPages($this);
    }

    public function getBlocks(): array {
        $block_dao = BlockDao::getInstance();
        return $block_dao->getBlocksByPage($this);
    }

    public function getBlocksByPosition($position): array {
        $block_dao = BlockDao::getInstance();
        return $block_dao->getBlocksByPageAndPosition($this, $position);
    }

    public function addBlock(Block $block): void {
        $block_dao = BlockDao::getInstance();
        $block_dao->addBlockToPage($block->getId(), $this);
    }

    public function deleteBlock(Block $block): void {
        $block_dao = BlockDao::getInstance();
        $block_dao->deleteBlockFromPage($block->getId(), $this);
    }

    public function moveUp(): void {
        $this->_page_dao->moveUp($this);
    }

    public function moveDown(): void {
        $this->_page_dao->moveDown($this);
    }

}