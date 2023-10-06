<?php
require_once CMS_ROOT . "/core/form/Form.php";

class PageForm extends Form {

    private Page $_page;
    private array $_selected_blocks;

    public function __construct(Page $page) {
        $this->_page = $page;
    }

    public function loadFields(): void {
        $this->_page->setTitle($this->getMandatoryFieldValue("page_title"));
        $this->_page->setPublished($this->getCheckboxValue("published"));
        $this->_page->setIncludeInSearchEngine($this->getCheckboxValue("include_in_search_engine"));
        $this->_page->setNavigationTitle($this->getMandatoryFieldValue("navigation_title"));
        $this->_page->setKeywords($this->getFieldValue("keywords"));
        $this->_page->setDescription($this->getFieldValue("description"));
        $this->_page->setShowInNavigation($this->getCheckboxValue("show_in_navigation"));
        $this->_page->setTemplateId($this->getNumber("page_template"));
        $this->_selected_blocks = $this->getFieldValues("select_blocks_" . $this->_page->getId());
        if ($this->hasErrors()) {
            throw new FormException();
        }
    }

    public function getSelectedBlocks(): array {
        return $this->_selected_blocks;
    }

}
    