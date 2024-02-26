<?php

namespace Obcato\Core\modules\templates;

use Obcato\Core\database\dao\TemplateDao;
use Obcato\Core\database\dao\TemplateDaoMysql;
use Obcato\Core\modules\templates\model\TemplateFile;
use Obcato\Core\request_handlers\HttpRequestHandler;

class TemplateFilesRequestHandler extends HttpRequestHandler {

    private static string $TEMPLATE_FILE_ID_GET = "template_file";
    private static string $TEMPLATE_FILE_ID_POST = "template_file_id";

    private TemplateDao $templateDao;
    private ?TemplateFile $currentTemplateFile = null;
    private array $parsedVarDefs = array();

    public function __construct() {
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public function handleGet(): void {
        if ($this->isCurrentTemplateFileShown()) {
            $this->currentTemplateFile = $this->getTemplateFileFromGetRequest();
        }
    }

    public function handlePost(): void {
        $this->currentTemplateFile = $this->getTemplateFileFromPostRequest();
        if ($this->isAddAction()) {
            $this->addTemplateFile();
        } else if ($this->isUpdateAction()) {
            $this->updateTemplateFile();
        } else if ($this->isReloadAction()) {
            $this->reloadTemplateFile();
        } else if ($this->isDeleteAction()) {
            $this->deleteTemplateFile();
        }
    }

    public function getCurrentTemplateFile(): ?TemplateFile {
        return $this->currentTemplateFile;
    }

    public function getParsedVarDefs(): array {
        return $this->parsedVarDefs;
    }

    private function addTemplateFile(): void {
        $templateFile = new TemplateFile();
        $templateFile->setName($this->getTextResource('template_files_new_name'));
        $this->templateDao->storeTemplateFile($templateFile);
        $this->redirectTo($this->getBackendBaseUrl() . "&template_file=" . $templateFile->getId());
    }

    private function updateTemplateFile(): void {
        $templateFileForm = new TemplateFileForm($this->currentTemplateFile);
        $templateFileForm->loadFields();
        $this->templateDao->updateTemplateFile($this->currentTemplateFile);
        $this->sendSuccessMessage($this->getTextResource('message_template_file_successfully_saved'));
    }

    private function reloadTemplateFile(): void {
        $templateFileForm = new TemplateFileForm($this->currentTemplateFile);
        $templateFileForm->setReloading();
        $templateFileForm->loadFields();
        $this->parsedVarDefs = $templateFileForm->getParseVarDefs();
    }

    private function deleteTemplateFile(): void {
        $this->templateDao->deleteTemplateFile($this->currentTemplateFile);
        $this->sendSuccessMessage($this->getTextResource('message_template_file_successfully_deleted'));
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function getTemplateFileFromPostRequest(): ?TemplateFile {
        $templateFile = null;
        if (isset($_POST[self::$TEMPLATE_FILE_ID_POST])) {
            $id = intval($_POST[self::$TEMPLATE_FILE_ID_POST]);
            $templateFile = $this->templateDao->getTemplateFile($id);
        }
        return $templateFile;
    }

    private function getTemplateFileFromGetRequest(): ?TemplateFile {
        $templateFile = null;
        if (isset($_GET[self::$TEMPLATE_FILE_ID_GET])) {
            $templateFile = $this->templateDao->getTemplateFile($_GET[self::$TEMPLATE_FILE_ID_GET]);
        }
        return $templateFile;
    }

    private function isCurrentTemplateFileShown(): bool {
        return isset($_GET[self::$TEMPLATE_FILE_ID_GET]);
    }

    private function isUpdateAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "update_template_file";
    }

    private function isAddAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "add_template_file";
    }

    private function isDeleteAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "delete_template_file";
    }

    private function isReloadAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "reload_template_file";
    }

}