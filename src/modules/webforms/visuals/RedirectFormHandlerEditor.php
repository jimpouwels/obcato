<?php

namespace Obcato\Core\modules\webforms\visuals;

use Obcato\Core\database\dao\PageDao;
use Obcato\Core\database\dao\PageDaoMysql;
use Obcato\Core\modules\webforms\model\WebformHandlerProperty;
use Obcato\Core\view\views\PagePicker;
use Obcato\Core\view\views\Visual;

class RedirectFormHandlerEditor extends Visual {

    private ?WebformHandlerProperty $_property = null;
    private PageDao $_page_dao;

    public function __construct() {
        parent::__construct();
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
        $page_picker = new PagePicker("handler_property_{$id}_field", 'webforms_redirect_handler_page_picker', $this->_property->getValue(), 'update_webform');
        $this->assign('page_picker', $page_picker->render());
    }

    public function setCurrentValue(WebformHandlerProperty $property): void {
        $this->_property = $property;
    }

}