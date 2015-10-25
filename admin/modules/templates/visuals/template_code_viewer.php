<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/scope_dao.php";

    class TemplateCodeViewer extends Panel {

        private static $TEMPLATE_EDITOR_TEMPLATE = "templates/template_code_viewer.tpl";

        private $_template;
        private $_template_engine;

        public function __construct($template) {
            parent::__construct('Markup', 'template_content_fieldset');
            $this->_template = $template;
            $this->_template_engine = TemplateEngine::getInstance();
        }

        public function render() {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign('file_content', $this->getTemplateCode());
            return $this->_template_engine->fetch("modules/" . self::$TEMPLATE_EDITOR_TEMPLATE);
        }

        private function getTemplateCode() {
            $file_path = FRONTEND_TEMPLATE_DIR . '/' . $this->_template->getFilename();
            if (is_file($file_path) && file_exists($file_path)) {
                return htmlspecialchars(file_get_contents($file_path));
            }
        }

    }
