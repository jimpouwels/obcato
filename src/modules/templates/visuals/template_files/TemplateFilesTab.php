<?php

namespace Obcato\Core\modules\templates\visuals\template_files;

use Obcato\Core\modules\templates\model\TemplateFile;
use Obcato\Core\modules\templates\TemplateFilesRequestHandler;
use Obcato\Core\view\views\Visual;

class TemplateFilesTab extends Visual {

    private ?TemplateFile $currentTemplateFile;
    private TemplateFilesRequestHandler $requestHandler;

    public function __construct(TemplateFilesRequestHandler $requestHandler) {
        parent::__construct();
        $this->currentTemplateFile = $requestHandler->getCurrentTemplateFile();
        $this->requestHandler = $requestHandler;
    }

    public function getTemplateFilename(): string {
        return "modules/templates/template_files/template_files_tab.tpl";
    }

    public function load(): void {
        $this->assign("current_template_file_id", $this->getCurrentTemplateFileId());
        $templateFilesList = new TemplateFilesList();
        $this->assign("template_files_list", $templateFilesList->render());

        $editorHtml = "";
        if ($this->currentTemplateFile) {
            $templateFileEditor = new TemplateFileEditor($this->currentTemplateFile);
            $editorHtml = $templateFileEditor->render();
        }
        $this->assign("template_file_editor", $editorHtml);

        $varMigrationHtml = "";
        if (count($this->requestHandler->getParsedVarDefs()) > 0) {
            $templateVarMigration = new TemplateVarMigration($this->requestHandler);
            $varMigrationHtml = $templateVarMigration->render();
        }
        $this->assign("template_var_migration", $varMigrationHtml);

        $codeViewerHtml = "";
        if ($this->currentTemplateFile) {
            $codeViewerHtml = $this->renderTemplateCodeViewer();
        }
        $this->assign("template_code_viewer", $codeViewerHtml);
    }

    private function getCurrentTemplateFileId(): string {
        $id = "";
        if ($this->currentTemplateFile) {
            $id = $this->currentTemplateFile->getId();
        }
        return $id;
    }

    private function renderTemplateCodeViewer(): string {
        return (new TemplateFileCodeViewer($this->currentTemplateFile))->render();
    }

}