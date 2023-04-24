<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/dao/template_dao.php";
    require_once CMS_ROOT . "view/views/information_message.php";
    require_once CMS_ROOT . "modules/templates/visuals/scope_selector.php";

    class TemplateList extends Panel {


        private TemplateDao $_template_dao;
        private Scope $_scope;

        public function __construct(Scope $scope) {
            parent::__construct($this->getTextResource($scope->getIdentifier() . '_scope_label') . ' templates', 'template_list_fieldset');
            $this->_scope = $scope;
            $this->_template_dao = TemplateDao::getInstance();
        }

        public function getPanelContentTemplate(): string {
            return "modules/templates/template_list.tpl";
        }

        public function loadPanelContent(Smarty_Internal_Data $data): void {
            $data->assign("scope", $this->_scope->getIdentifier());
            $data->assign("templates", $this->getTemplatesForScope($this->_scope));
            $data->assign("information_message", $this->renderInformationMessage());
        }

        private function getTemplatesForScope(Scope $scope): array {
            $templates_data = array();
            foreach ($this->_template_dao->getTemplatesByScope($scope) as $template) {
                $template_data = array();
                $template_data["id"] = $template->getId();
                $template_data["name"] = $template->getName();
                $template_data["filename"] = $template->getFileName();
                $template_data["exists"] = $template->exists();
                $template_data["delete_checkbox"] = $this->renderDeleteCheckBox($template);
                $templates_data[] = $template_data;
            }
            return $templates_data;
        }

        private function renderDeleteCheckBox(Template $template): string {
            $checkbox = new SingleCheckbox("template_" . $template->getId() . "_delete", "", "", false, "");
            return $checkbox->render();
        }

        private function renderInformationMessage(): string {
            $information_message = new InformationMessage("Geen templates gevonden. Klik op 'toevoegen' om een nieuw template te maken.");
            return $information_message->render();
        }

    }
