<?php
require_once CMS_ROOT . "/modules/templates/visuals/template_files/TemplateFilesList.php";
require_once CMS_ROOT . "/modules/templates/visuals/template_files/TemplateFileEditor.php";
require_once CMS_ROOT . "/modules/templates/visuals/template_files/TemplateFileCodeViewer.php";
require_once CMS_ROOT . "/modules/templates/visuals/template_files/TemplateVarMigration.php";

class TemplateFilesTab extends Visual {

    private ?TemplateFile $_current_template_file;
    private TemplateFilesRequestHandler $_request_handler;

    public function __construct(TemplateFilesRequestHandler $requestHandler) {
        parent::__construct();
        $this->_current_template_file = $requestHandler->getCurrentTemplateFile();
        $this->_request_handler = $requestHandler;
    }

    public function getTemplateFilename(): string {
        return "modules/templates/template_files/template_files_tab.tpl";
    }

    public function load(): void {
        $this->assign("current_template_file_id", $this->getCurrentTemplateFileId());
        $template_files_list = new TemplateFilesList();
        $this->assign("template_files_list", $template_files_list->render());

        $editor_html = "";
        if ($this->_current_template_file) {
            $template_file_editor = new TemplateFileEditor($this->_current_template_file);
            $editor_html = $template_file_editor->render();
        }
        $this->assign("template_file_editor", $editor_html);

        $var_migration_html = "";
        if (count($this->_request_handler->getParsedVarDefs()) > 0) {
            $template_var_migration = new TemplateVarMigration($this->_request_handler);
            $var_migration_html = $template_var_migration->render();
        }
        $this->assign("template_var_migration", $var_migration_html);

        $code_viewer_html = "";
        if ($this->_current_template_file) {
            $code_viewer_html = $this->renderTemplateCodeViewer();
        }
        $this->assign("template_code_viewer", $code_viewer_html);
    }

    private function getCurrentTemplateFileId(): string {
        $id = "";
        if ($this->_current_template_file) {
            $id = $this->_current_template_file->getId();
        }
        return $id;
    }

    private function renderTemplateCodeViewer(): string {
        $template_code_viewer = new TemplateFileCodeViewer($this->_current_template_file);
        return $template_code_viewer->render();
    }

}

?>