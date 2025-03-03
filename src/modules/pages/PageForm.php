<?php

namespace Obcato\Core\modules\pages;

use Obcato\Core\core\form\Form;
use Obcato\Core\core\form\FormException;
use Obcato\Core\modules\pages\model\Page;

class PageForm extends Form {

    private Page $page;
    private array $selectedBlocks;

    public function __construct(Page $page) {
        $this->page = $page;
    }

    public function loadFields(): void {
        $this->page->setTitle($this->getMandatoryFieldValue("page_title"));
        $this->page->setName($this->getMandatoryFieldValue("name"));
        $this->page->setUrlTitle($this->getFieldValue("url_title"));
        $this->page->setIncludeParentInUrl($this->getCheckboxValue("include_parent_in_url"));
        $this->page->setPublished($this->getCheckboxValue("published"));
        $this->page->setIncludeInSearchEngine($this->getCheckboxValue("include_in_search_engine"));
        $this->page->setNavigationTitle($this->getMandatoryFieldValue("navigation_title"));
        $this->page->setKeywords($this->getFieldValue("keywords"));
        $this->page->setDescription($this->getFieldValue("description"));
        $this->page->setShowInNavigation($this->getCheckboxValue("show_in_navigation"));
        $this->page->setTemplateId($this->getNumber("page_template"));
        $this->selectedBlocks = $this->getFieldValues("select_blocks_" . $this->page->getId());
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getSelectedBlocks(): array {
        return $this->selectedBlocks;
    }

}
    