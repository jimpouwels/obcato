<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/scope_dao.php";

    class TemplateCodeViewer extends Panel {


        private Template $_template;

        public function __construct(Template $template) {
            parent::__construct('Markup', 'template_content_fieldset');
            $this->_template = $template;
        }

        public function getPanelContentTemplate(): string {
            return "modules/templates/template_code_viewer.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign('file_content', $this->getTemplateCode());
        }

        private function getTemplateCode(): ?string {
            $file_path = FRONTEND_TEMPLATE_DIR . '/' . $this->_template->getFilename();
            if (is_file($file_path) && file_exists($file_path)) {
                return htmlspecialchars(file_get_contents($file_path));
            }
            return null;
        }

    }
