<?php
    
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/template_dao.php";
    require_once CMS_ROOT . "database/dao/scope_dao.php";
    require_once CMS_ROOT . "request_handlers/http_request_handler.php";
    require_once CMS_ROOT . "modules/templates/template_form.php";
    
    class TemplateRequestHandler extends HttpRequestHandler {
    
        private static string $TEMPLATE_ID_GET = "template";
        private static string $SCOPE_NAME_GET = "scope";
        private static string $TEMPLATE_ID_POST = "template_id";

        private TemplateDao $_template_dao;
        private ScopeDao $_scope_dao;
        private ?Template $_current_template = null;
        private ?Scope $_current_scope = null;
        
        public function __construct() {
            $this->_template_dao = TemplateDao::getInstance();
            $this->_scope_dao = ScopeDao::getInstance();
        }
    
        public function handleGet(): void {
            if ($this->isCurrentTemplateShown()) {
                $this->_current_template = $this->getTemplateFromGetRequest();
            }
            $this->_current_scope = $this->getScopeFromGetRequest();
        }
        
        public function handlePost(): void {
            $this->_current_template = $this->getTemplateFromPostRequest();
            $this->_current_scope = $this->getScopeFromGetRequest();
            if ($this->isUpdateAction()) {
                $this->updateTemplate();
            } else if ($this->isAddTemplateAction()) {
                $this->addTemplate();
            } else if ($this->isDeleteAction()) {
                $this->deleteTemplates();
            }
        }
        
        public function getCurrentTemplate(): ?Template {
            return $this->_current_template;
        }
        
        public function getCurrentScope(): ?Scope {
            return $this->_current_scope;
        }
        
        private function addTemplate(): void {
            $new_template = $this->_template_dao->createTemplate();
            $this->sendSuccessMessage("Template succesvol aangemaakt");
            $this->redirectTo($this->getBackendBaseUrl() . "&template=" . $new_template->getId());
        }
        
        private function deleteTemplates(): void {
            foreach ($this->_template_dao->getTemplates() as $template) {
                if (isset($_POST["template_" . $template->getId() . "_delete"]))
                    $this->_template_dao->deleteTemplate($template);
            }
            $this->sendSuccessMessage("Template(s) succesvol verwijderd");
        }
        
        private function updateTemplate(): void {
            $template_form = new TemplateForm($this->_current_template);
            $old_file_path = FRONTEND_TEMPLATE_DIR . "\\" . $this->_current_template->getFileName();
            $old_file_name = $this->_current_template->getFileName();
            try {
                $template_form->loadFields();
                if ($template_form->isFileUploaded()) {
                    $this->removeOldFile($old_file_path);
                    $this->copyUploadToTemplateDir($template_form->getPathToUploadedFile());
                } else if ($this->isTemplateRenamed($old_file_name) && file_exists($old_file_path) ) {
                    $this->renameTemplateFile($old_file_name);
                }
                $this->_template_dao->updateTemplate($this->_current_template);
                $this->sendSuccessMessage("Template succesvol opgeslagen");
            } catch (FormException $e) {
                $this->sendErrorMessage("Template niet opgeslagen, verwerk de fouten");
            }
        }
        
        private function renameTemplateFile(string $old_file_name): void {
            rename(FRONTEND_TEMPLATE_DIR . "/" . $old_file_name, FRONTEND_TEMPLATE_DIR . "/" . $this->_current_template->getFileName());
        }
        
        private function isTemplateRenamed(string $old_file_name): bool {
            return ($old_file_name != "" && $old_file_name != $this->_current_template->getFileName());
        }
        
        private function removeOldFile(string $old_file_path): void {
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
        }
        
        private function copyUploadToTemplateDir(string $path_to_uploaded_file): void {
            move_uploaded_file($path_to_uploaded_file, FRONTEND_TEMPLATE_DIR . "/" . $this->_current_template->getFileName());
        }

        private function getTemplateFromPostRequest(): ?Template {
            $template = null;
            if (isset($_POST[self::$TEMPLATE_ID_POST])) {
                $template = $this->_template_dao->getTemplate($_POST[self::$TEMPLATE_ID_POST]);
            }
            return $template;
        }
        
        private function getTemplateFromGetRequest(): Template {
            return $this->_template_dao->getTemplate($_GET[self::$TEMPLATE_ID_GET]);
        }
        
        private function getScopeFromGetRequest(): ?Scope {
            if (isset($_GET[self::$SCOPE_NAME_GET])) {
                $scope_name = $_GET[self::$SCOPE_NAME_GET];
                return $this->_scope_dao->getScopeByName($scope_name);
            }
            return null;
        }
        
        private function isCurrentTemplateShown(): bool {
            return isset($_GET[self::$TEMPLATE_ID_GET]);
        }
        
        private function isUpdateAction(): bool {
            return isset($_POST["action"]) && $_POST["action"] == "update_template";
        }
        
        private function isAddTemplateAction(): bool {
            return isset($_POST["action"]) && $_POST["action"] == "add_template";
        }
        
        private function isDeleteAction(): bool {
            return isset($_POST["action"]) && $_POST["action"] == "delete_templates";
        }

    }
?>