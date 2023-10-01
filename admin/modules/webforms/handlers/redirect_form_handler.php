<?php
    defined('_ACCESS') or die;
  
    require_once CMS_ROOT . 'modules/webforms/handlers/form_handler.php';
    require_once CMS_ROOT . 'core/model/webform.php';
    require_once CMS_ROOT . 'database/dao/settings_dao.php';
    require_once CMS_ROOT . 'database/dao/page_dao.php';

    class RedirectFormHandler extends Formhandler {

        public static string $TYPE = 'redirect_form_handler';
        private PageDao $_page_dao;

        public function __construct() {
            parent::__construct();
            $this->_page_dao = PageDao::getInstance();
        }

        public function getRequiredProperties(): array {
            require_once CMS_ROOT . 'modules/webforms/visuals/redirect_form_handler_editor.php';
            return array(
                new HandlerProperty('page_id', 'textfield', new RedirectFormHandlerEditor()),
            );
        }

        public function getNameResourceIdentifier(): string {
            return 'webforms_redirect_form_handler_name';
        }

        public function getType(): string {
            return self::$TYPE;
        }

        public function handle(array $fields, Page $page, ?Article $article): void {
            $page_id = $this->getProperty('page_id');
            if ($page_id) {
                $page = $this->_page_dao->getPage($page_id);
                if ($page) {
                    $this->redirectTo($this->getPageUrl($page));
                }
            }
        }
    }
?>