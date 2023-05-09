<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'view/views/visual.php';
    require_once CMS_ROOT . 'view/views/page_picker.php';
    require_once CMS_ROOT . 'database/dao/page_dao.php';
  
    class RedirectFormHandlerEditor extends Visual {

        private ?array $_property = null;
        private PageDao $_page_dao;

        public function __construct() {
            parent::__construct();
            $this->_page_dao = PageDao::getInstance();
        }

        public function getTemplateFilename(): string {
            return 'modules/webforms/webforms/redirect_form_handler_editor.tpl';
        }

        public function load(): void {
            $id = $this->_property['id'];
            $name = $this->_property['name'];
            $backing_field_name = "handler_property_{$id}_{$name}_field";
            $page_picker = new PagePicker('webforms_redirect_handler_page_picker', $this->_property['value'], $backing_field_name, 'submit_webform_editor_form', 'pages');
            if ($this->_property['value']) {
                $page = $this->_page_dao->getPage(intval($this->_property['value']));
                if ($page) {
                  $this->assign('selected_page', $page->getTitle());
                }
            }
            $this->assign('page_picker', $page_picker->render());
        }

        public function setCurrentValue(array $property): void {
            $this->_property = $property;
        }

    }
?>