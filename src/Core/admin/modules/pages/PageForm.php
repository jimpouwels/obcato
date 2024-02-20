<?php

namespace Obcato\Core;

use Obcato\Core\admin\core\form\Form;
use Obcato\Core\admin\core\form\FormException;

class PageForm extends Form {

    private Page $page;
    private array $selectedBlocks;

    public function __construct(Page $page) {
        $this->page = $page;
    }

    public function loadFields(): void {
        $this->page->setTitle($this->getMandatoryFieldValue("page_title"));
        $this->page->setUrlTitle($this->getFieldValue("url_title"));
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
    