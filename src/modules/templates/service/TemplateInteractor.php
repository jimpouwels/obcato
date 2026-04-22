<?php

namespace Pageflow\Core\modules\templates\service;


use Pageflow\Core\database\dao\TemplateDao;
use Pageflow\Core\database\dao\TemplateDaoMysql;
use Pageflow\Core\modules\templates\model\Template;
use Pageflow\Core\modules\templates\model\TemplateFile;
use Pageflow\Core\modules\templates\model\TemplateVar;
use Pageflow\Core\modules\templates\model\TemplateVarDef;
use Pageflow\Core\utilities\Arrays;

class TemplateInteractor implements TemplateService {

    private static ?TemplateInteractor $instance = null;

    private TemplateDao $templateDao;

    private function __construct() {
        $this->templateDao = TemplateDaoMysql::getInstance();
    }

    public static function getInstance(): TemplateInteractor {
        if (!self::$instance) {
            self::$instance = new TemplateInteractor();
        }
        return self::$instance;
    }

    public function getTemplateVarDefByTemplateVar(Template $template, TemplateVar $templateVar): TemplateVarDef {
        return Arrays::firstMatch($this->getTemplateVarDefsByTemplate($template), function ($templateVarDef) use ($templateVar) {
            return $templateVar->getName() == $templateVarDef->getName();
        });
    }

    public function getTemplateVarDefsByTemplate(Template $template): array {
        return $this->templateDao->getTemplateVarDefs($this->getTemplateFileForTemplate($template));
    }

    public function getTemplateFileForTemplate(Template $template): TemplateFile {
        return $this->templateDao->getTemplateFile($template->getTemplateFileId());
    }
}