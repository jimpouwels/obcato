<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "core/model/element_holder.php";
    require_once CMS_ROOT . "database/dao/block_dao.php";
    require_once CMS_ROOT . "database/dao/page_dao.php";

    class Page extends ElementHolder {

        const ElementHolderType = "ELEMENT_HOLDER_PAGE";

        private static string $TABLE_NAME = "pages";
        private static int $SCOPE = 5;
        
        private PageDao $_page_dao;
        private string $_description;
        private string $_navigation_title;
        private ?int $_parent_id;
        private bool $_show_in_navigation;
        private int $_followup;
        private bool $_is_homepage;
        
        public function __construct() {
            parent::__construct(self::$SCOPE);
            $this->_page_dao = PageDao::getInstance();
        }
        
        public function getDescription(): string {
            return $this->_description;
        }
        
        public function setDescription(string $description): void {
            $this->_description = $description;
        }
        
        public function getNavigationTitle(): string {
            return $this->_navigation_title;
        }
        
        public function setNavigationTitle(string $navigation_title): void {
            $this->_navigation_title = $navigation_title;
        }
        
        public function getParent(): ?Page {
            $parent = null;
            if (!is_null($this->_parent_id)) {
                $parent = $this->_page_dao->getPage($this->_parent_id);
            }
            return $parent;
        }
        
        public function setParentId(?int $parent_id): void {
            $this->_parent_id = $parent_id;    
        }
        
        public function getParentId(): int {
            return $this->_parent_id;
        }
        
        public function setParent(Page $parent): void {
            if (!is_null($parent)) {
                $this->_parent_id = $parent->getId();
            }
        }
        
        public function getShowInNavigation(): bool {
            return $this->_show_in_navigation;
        }
        
        public function setShowInNavigation(bool $show_in_navigation): void {
            $this->_show_in_navigation = $show_in_navigation;
        }
        
        public function setFollowUp(int $follow_up): void {
            $this->_followup = $follow_up;
        }
        
        public function getFollowup(): int {
            return $this->_followup;
        }
        
        public function setIsHomepage(bool $is_homepage): void {
            $this->_is_homepage = $is_homepage;
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
        
        public static function constructFromRecord(array $record): Page {
            $page = new Page();
            $page->setId($record['id']);
            $page->setParentId($record['parent_id']);
            $page->setPublished($record['published'] == 1 ? true : false);
            $page->setTitle($record['title']);
            $page->setDescription($record['description']);
            $page->setNavigationTitle($record['navigation_title']);
            $page->setShowInNavigation($record['show_in_navigation'] == 1 ? true : false);
            $page->setTemplateId($record['template_id']);
            $page->setIncludeInSearchEngine($record['include_in_searchindex'] == 1 ? true : false);
            $page->setScopeId($record['scope_id']);
            $page->setCreatedAt($record['created_at']);
            $page->setCreatedById($record['created_by']);
            $page->setType($record['type']);
            $page->setFollowUp($record['follow_up']);
            $page->setIsHomepage($record['is_homepage'] == 1 ? true : false);
            return $page;
        }
    
    }
    
?>