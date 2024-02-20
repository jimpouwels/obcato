<?php

namespace Obcato\Core\admin\modules\templates\visuals\template_editor;

use Obcato\ComponentApi\TemplateData;
use Obcato\ComponentApi\TemplateEngine;
use Obcato\Core\admin\database\dao\TemplateDao;
use Obcato\Core\admin\database\dao\TemplateDaoMysql;
use Obcato\Core\admin\modules\templates\model\Scope;
use Obcato\Core\admin\modules\templates\model\Template;
use Obcato\Core\admin\view\views\InformationMessage;
use Obcato\Core\admin\view\views\Panel;
use Obcato\Core\admin\view\views\SingleCheckbox;

class TemplateList extends Panel {

    private TemplateDao $templateDao;
    private Scope $scope;

    public function __construct(TemplateEngine $templateEngine, Scope $scope) {
        parent::__construct($templateEngine, $this->getTextResource($scope->getIdentifier() . '_scope_label') . ' templates', 'template_list_panel');
        $this->scope = $scope;
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/templates/template_list.tpl";
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
        $checkbox = new SingleCheckbox($this->getTemplateEngine(), "template_" . $template->getId() . "_delete", "", "", false, "");
        return $checkbox->render();
    }

    private function renderInformationMessage(): string {
        $informationMessage = new InformationMessage($this->getTemplateEngine(), "Geen templates gevonden. Klik op 'toevoegen' om een nieuw template te maken.");
        return $informationMessage->render();
    }

}
