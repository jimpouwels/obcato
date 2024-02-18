<?php
require_once CMS_ROOT . "/database/dao/TemplateDaoMysql.php";
require_once CMS_ROOT . "/database/dao/ScopeDaoMysql.php";
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/modules/templates/TemplateEditorForm.php";

class TemplateEditorRequestHandler extends HttpRequestHandler {

    private static string $TEMPLATE_ID_GET = "template";
    private static string $SCOPE_IDENTIFIER_GET = "scope";
    private static string $TEMPLATE_ID_POST = "template_id";

    private TemplateDao $templateDao;
    private ScopeDao $scopeDao;
    private ?Template $currentTemplate = null;
    private ?Scope $currentScope = null;

    public function __construct() {
        $this->templateDao = TemplateDaoMysql::getInstance();
        $this->scopeDao = ScopeDaoMysql::getInstance();
    }

    public function handleGet(): void {
        if ($this->isCurrentTemplateShown()) {
            $this->currentTemplate = $this->getTemplateFromGetRequest();
        }
        $this->currentScope = $this->getScopeFromGetRequest();
    }

    public function handlePost(): void {
        $this->currentTemplate = $this->getTemplateFromPostRequest();
        $this->currentScope = $this->getScopeFromGetRequest();
        if ($this->isUpdateAction()) {
            $this->updateTemplate();
        } else if ($this->isAddTemplateAction()) {
            $this->addTemplate();
        } else if ($this->isDeleteAction()) {
            $this->deleteTemplates();
        }
    }

    public function getCurrentTemplate(): ?Template {
        return $this->currentTemplate;
    }

    public function getCurrentScope(): ?Scope {
        return $this->currentScope;
    }

    private function addTemplate(): void {
        $new_template = $this->templateDao->createTemplate();
        $this->sendSuccessMessage("Template succesvol aangemaakt");
        $this->redirectTo($this->getBackendBaseUrl() . "&template=" . $new_template->getId());
    }

    private function deleteTemplates(): void {
        foreach ($this->templateDao->getTemplates() as $template) {
            if (isset($_POST["template_" . $template->getId() . "_delete"]))
                $this->templateDao->deleteTemplate($template);
        }
        $this->sendSuccessMessage("Template(s) succesvol verwijderd");
    }

    private function updateTemplate(): void {
        $template_form = new TemplateEditorForm($this->currentTemplate);
        try {
            $template_form->loadFields();
            $this->templateDao->updateTemplate($this->currentTemplate);
            $this->sendSuccessMessage("Template succesvol opgeslagen");
        } catch (FormException $e) {
            $this->sendErrorMessage("Template niet opgeslagen, verwerk de fouten");
        }
    }

    private function getTemplateFromPostRequest(): ?Template {
        $template = null;
        if (isset($_POST[self::$TEMPLATE_ID_POST])) {
            $template = $this->templateDao->getTemplate(intval($_POST[self::$TEMPLATE_ID_POST]));
        }
        return $template;
    }

    private function getTemplateFromGetRequest(): Template {
        return $this->templateDao->getTemplate($_GET[self::$TEMPLATE_ID_GET]);
    }

    private function getScopeFromGetRequest(): ?Scope {
        if (isset($_GET[self::$SCOPE_IDENTIFIER_GET])) {
            $scopeIdentifier = $_GET[self::$SCOPE_IDENTIFIER_GET];
            return $this->scopeDao->getScopeByIdentifier($scopeIdentifier);
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