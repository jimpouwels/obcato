<?php

namespace Pageflow\Core\modules\templates\visuals\template_editor;

use Pageflow\Core\database\dao\TemplateDao;
use Pageflow\Core\database\dao\TemplateDaoMysql;
use Pageflow\Core\modules\templates\model\Scope;
use Pageflow\Core\modules\templates\model\Template;
use Pageflow\Core\view\TemplateData;
use Pageflow\Core\view\views\InformationMessage;
use Pageflow\Core\view\views\Panel;
use Pageflow\Core\view\views\SingleCheckbox;

class TemplateList extends Panel {

    private TemplateDao $templateDao;
    private Scope $scope;

    public function __construct(Scope $scope) {
        parent::__construct($this->getTextResource($scope->getIdentifier() . '_scope_label') . ' templates', 'template_list_panel');
        $this->scope = $scope;
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "templates/templates/template_list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("scope", $this->scope->getIdentifier());
        $data->assign("templates", $this->getTemplatesForScope($this->scope));
        $data->assign("information_message", $this->renderInformationMessage());
    }

    private function getTemplatesForScope(Scope $scope): array {
        $templatesData = array();
        foreach ($this->templateDao->getTemplatesByScope($scope) as $template) {
            $templateData = array();
            $templateData["id"] = $template->getId();
            $templateData["name"] = $template->getName();
            $templateData["delete_checkbox"] = $this->renderDeleteCheckBox($template);
            $templatesData[] = $templateData;
        }
        return $templatesData;
    }

    private function renderDeleteCheckBox(Template $template): string {
        $checkbox = new SingleCheckbox("template_" . $template->getId() . "_delete", "", "", false, "");
        return $checkbox->render();
    }

    private function renderInformationMessage(): string {
        $informationMessage = new InformationMessage("Geen templates gevonden. Klik op 'toevoegen' om een nieuw template te maken.");
        return $informationMessage->render();
    }

}
