<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/scope_dao.php";

    class TemplateCodeViewer extends Panel {

        private static $TEMPLATE_EDITOR_TEMPLATE = "templates/template_code_viewer.tpl";

        private $_template;

        public function __construct($template) {
            parent::__construct('Markup', 'template_content_fieldset');
            $this->_template = $template;
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign('file_content', $this->getTemplateCode());
            return $this->getTemplateEngine()->fetch("modules/" . self::$TEMPLATE_EDITOR_TEMPLATE);
        }

        private function getTemplateCode() {
            $file_path = FRONTEND_TEMPLATE_DIR . '/' . $this->_template->getFilename();
            if (is_file($file_path) && file_exists($file_path)) {
                return htmlspecialchars(file_get_contents($file_path));
            }
        }

    }
