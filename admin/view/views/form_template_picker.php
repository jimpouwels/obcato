<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/view/views/form_pulldown.php";
require_once CMS_ROOT . "/database/dao/TemplateDaoMysql.php";

class TemplatePicker extends Pulldown {

    public function __construct(string $name, string $label, bool $mandatory, ?string $class_name, ?Template $current_template, Scope $scope) {
        $options = $this->getOptions($scope);
        $current_template_id = null;
        if (!is_null($current_template)) {
            $current_template_id = $current_template->getId();
        }
        parent::__construct($name, $label, $current_template_id, $options, $mandatory, $class_name, true);
    }

    private function getOptions(Scope $scope): array {
        $template_dao = TemplateDaoMysql::getInstance();
        $options = array();
        foreach ($template_dao->getTemplatesByScope($scope) as $template) {
            $options[] = array('name' => $template->getName(), 'value' => $template->getId());
        }
        return $options;
    }

}