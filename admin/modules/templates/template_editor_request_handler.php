<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/database/dao/TemplateDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ScopeDaoMysql.php";
require_once CMS_ROOT . "/request_handlers/http_request_handler.php";
require_once CMS_ROOT . "/modules/templates/template_editor_form.php";

class TemplateEditorRequestHandler extends HttpRequestHandler {

    private static string $TEMPLATE_ID_GET = "template";
    private static string $SCOPE_IDENTIFIER_GET = "scope";
    private static string $TEMPLATE_ID_POST = "template_id";

    private TemplateDao $_template_dao;
    private ScopeDao $_scope_dao;
    private ?Template $_current_template = null;
    private ?Scope $_current_scope = null;

    public function __construct() {
        $this->_template_dao = TemplateDaoMysql::getInstance();
        $this->_scope_dao = ScopeDaoMysql::getInstance();
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
        $template_form = new TemplateEditorForm($this->_current_template);
        try {
            $template_form->loadFields();
            $this->_template_dao->updateTemplate($this->_current_template);
            $this->sendSuccessMessage("Template succesvol opgeslagen");
        } catch (FormException $e) {
            $this->sendErrorMessage("Template niet opgeslagen, verwerk de fouten");
        }
    }

    private function getTemplateFromPostRequest(): ?Template {
        $template = null;
        if (isset($_POST[self::$TEMPLATE_ID_POST])) {
            $template = $this->_template_dao->getTemplate(intval($_POST[self::$TEMPLATE_ID_POST]));
        }
        return $template;
    }

    private function getTemplateFromGetRequest(): Template {
        return $this->_template_dao->getTemplate($_GET[self::$TEMPLATE_ID_GET]);
    }

    private function getScopeFromGetRequest(): ?Scope {
        if (isset($_GET[self::$SCOPE_IDENTIFIER_GET])) {
            $scope_identifier = $_GET[self::$SCOPE_IDENTIFIER_GET];
            return $this->_scope_dao->getScopeByIdentifier($scope_identifier);
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