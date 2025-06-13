<?php

namespace Obcato\Core\view\views;

use Obcato\Core\database\dao\TemplateDaoMysql;
use Obcato\Core\modules\templates\model\Scope;
use Obcato\Core\modules\templates\model\Template;

class TemplatePicker extends Pulldown {

    public function __construct(string $name, string $label, bool $mandatory, ?string $className, ?Template $current_template, Scope $scope) {
        $options = $this->getOptions($scope);
        $current_template_id = null;
        if (!is_null($current_template)) {
            $current_template_id = $current_template->getId();
        }
        parent::__construct($name, $label, $current_template_id, $options, $mandatory, $className, true);
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