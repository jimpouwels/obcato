<?php

namespace Obcato\Core\admin\modules\webforms\visuals;

use Obcato\ComponentApi\TemplateEngine;
use Obcato\ComponentApi\Visual;
use Obcato\Core\admin\database\dao\PageDao;
use Obcato\Core\admin\database\dao\PageDaoMysql;
use Obcato\Core\admin\modules\webforms\model\WebFormHandlerProperty;
use Obcato\Core\admin\view\views\PagePicker;

class RedirectFormHandlerEditor extends Visual {

    private ?WebFormHandlerProperty $_property = null;
    private PageDao $_page_dao;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine);
        $this->_page_dao = PageDaoMysql::getInstance();
    }

    public function getTemplateFilename(): string {
        return 'modules/webforms/webforms/redirect_form_handler_editor.tpl';
    }

    public function load(): void {
        $id = $this->_property->getId();
        if ($this->_property->getValue()) {
            $page = $this->_page_dao->getPage(intval($this->_property->getValue()));
            if ($page) {
                $this->assign('selected_page', $page->getTitle());
            }
        }
        $page_picker = new PagePicker($this->getTemplateEngine(), "handler_property_{$id}_field", 'webforms_redirect_handler_page_picker', $this->_property->getValue(), 'update_webform');
        $this->assign('page_picker', $page_picker->render());
    }

    public function setCurrentValue(WebFormHandlerProperty $property): void {
        $this->_property = $property;
    }

}