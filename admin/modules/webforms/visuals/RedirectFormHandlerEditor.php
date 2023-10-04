<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . '/view/views/PagePicker.php';
require_once CMS_ROOT . '/database/dao/PageDaoMysql.php';
require_once CMS_ROOT . '/modules/webforms/model/WebformHandlerProperty.php';

class RedirectFormHandlerEditor extends Visual {

    private ?WebFormHandlerProperty $_property = null;
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

    public function setCurrentValue(WebFormHandlerProperty $property): void {
        $this->_property = $property;
    }

}

?>