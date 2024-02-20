<?php

namespace Obcato\Core;

class Page extends ElementHolder {

    const ElementHolderType = "ELEMENT_HOLDER_PAGE";
    private static int $SCOPE = 5;
    private ?string $description = null;
    private string $navigationTitle;
    private bool $includeInSearchEngine;
    private ?string $urlTitle = null;
    private ?int $parentId;
    private ?string $keywords = null;
    private bool $showInNavigation;
    private int $followUp;
    private bool $isHomepage;

    public function __construct() {
        parent::__construct(self::$SCOPE);
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
        $this->setUrlTitle($row['url_title']);
        $this->setNavigationTitle($row['navigation_title']);
        $this->setShowInNavigation($row['show_in_navigation'] == 1);
        $this->setIncludeInSearchEngine($row['include_in_searchindex'] == 1);
        $this->setFollowUp($row['follow_up']);
        $this->setIsHomepage($row['is_homepage'] == 1);
        parent::initFromDb($row);
    }

    public function setIsHomepage(bool $isHomepage): void {
        $this->isHomepage = $isHomepage;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function setUrlTitle(?string $urlTitle): void {
        $this->urlTitle = $urlTitle;
    }

    public function getUrlTitle(): ?string {
        return $this->urlTitle;
    }

    public function getKeywords(): ?string {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): void {
        $this->keywords = $keywords;
    }

    public function getNavigationTitle(): string {
        return $this->navigationTitle;
    }

    public function setNavigationTitle(string $navigationTitle): void {
        $this->navigationTitle = $navigationTitle;
    }

    public function getParentId(): ?int {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): void {
        $this->parentId = $parentId;
    }

    public function setParent(Page $parent): void {
        $this->parentId = $parent->getId();
    }

    public function getIncludeInSearchEngine(): bool {
        return $this->includeInSearchEngine;
    }

    public function setIncludeInSearchEngine(bool $includeInSearchEngine): void {
        $this->includeInSearchEngine = $includeInSearchEngine;
    }

    public function getShowInNavigation(): bool {
        return $this->showInNavigation;
    }

    public function setShowInNavigation(bool $showInNavigation): void {
        $this->showInNavigation = $showInNavigation;
    }

    public function getFollowUp(): int {
        return $this->followUp;
    }

    public function setFollowUp(int $followUp): void {
        $this->followUp = $followUp;
    }

    public function isHomepage(): bool {
        return $this->isHomepage;
    }

}