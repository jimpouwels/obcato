<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/database/dao/TemplateDaoMysql.php";
require_once CMS_ROOT . "/request_handlers/http_request_handler.php";
require_once CMS_ROOT . "/modules/templates/template_file_form.php";
require_once CMS_ROOT . "/core/model/template_file.php";

class TemplateFilesRequestHandler extends HttpRequestHandler {

    private static string $TEMPLATE_FILE_ID_GET = "template_file";
    private static string $TEMPLATE_FILE_ID_POST = "template_file_id";

    private TemplateDao $_template_dao;
    private ?TemplateFile $_current_template_file = null;
    private array $_parsed_var_defs = array();

    public function __construct() {
        $this->_template_dao = TemplateDaoMysql::getInstance();
    }

    public function handleGet(): void {
        if ($this->isCurrentTemplateFileShown()) {
            $this->_current_template_file = $this->getTemplateFileFromGetRequest();
        }
    }

    public function handlePost(): void {
        $this->_current_template_file = $this->getTemplateFileFromPostRequest();
        if ($this->isAddAction()) {
            $this->addTemplateFile();
        } else if ($this->isUpdateAction()) {
            $this->updateTemplateFile();
        } else if ($this->isMigrateAction()) {
            $this->migrateTemplateFile();
        } else if ($this->isDeleteAction()) {
            $this->deleteTemplateFile();
        }
    }

    public function getCurrentTemplateFile(): ?TemplateFile {
        return $this->_current_template_file;
    }

    public function getParsedVarDefs(): array {
        return $this->_parsed_var_defs;
    }

    private function addTemplateFile(): void {
        $template_file = new TemplateFile();
        $template_file->setName($this->getTextResource('template_files_new_name'));
        $this->_template_dao->storeTemplateFile($template_file);
        $this->redirectTo($this->getBackendBaseUrl() . "&template_file=" . $template_file->getId());
    }

    private function updateTemplateFile(): void {
        $template_file_form = new TemplateFileForm($this->_current_template_file);
        $template_file_form->loadFields();
        $this->_template_dao->updateTemplateFile($this->_current_template_file);
        $this->sendSuccessMessage($this->getTextResource('message_template_file_successfully_saved'));
    }

    private function migrateTemplateFile(): void {
        $template_file_form = new TemplateFileForm($this->_current_template_file);
        $template_file_form->loadFields();
        $this->_parsed_var_defs = $template_file_form->getParsedVarDefs();
    }

    private function deleteTemplateFile(): void {
        $this->_template_dao->deleteTemplateFile($this->_current_template_file);
        $this->sendSuccessMessage($this->getTextResource('message_template_file_successfully_deleted'));
        $this->redirectTo($this->getBackendBaseUrl());
    }

    private function getTemplateFileFromPostRequest(): ?TemplateFile {
        $template_file = null;
        if (isset($_POST[self::$TEMPLATE_FILE_ID_POST])) {
            $id = intval($_POST[self::$TEMPLATE_FILE_ID_POST]);
            $template_file = $this->_template_dao->getTemplateFile($id);
        }
        return $template_file;
    }

    private function getTemplateFileFromGetRequest(): ?TemplateFile {
        $template_file = null;
        if (isset($_GET[self::$TEMPLATE_FILE_ID_GET])) {
            $template_file = $this->_template_dao->getTemplateFile($_GET[self::$TEMPLATE_FILE_ID_GET]);
        }
        return $template_file;
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

    private function isMigrateAction(): bool {
        return isset($_POST["action"]) && $_POST["action"] == "migrate_template_file";
    }

}

?>