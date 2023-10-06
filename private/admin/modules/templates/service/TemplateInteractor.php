<?php
require_once CMS_ROOT . "/modules/templates/service/TemplateService.php";
require_once CMS_ROOT . "/database/dao/TemplateDaoMysql.php";

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

    public function getTemplateVarDefByTemplateVar(TemplateVar $templateVar): TemplateVarDef {
        return $this->templateDao->getTemplateFile(
            $this->templateDao->getTemplate($templateVar->getTemplateId())->getTemplateFileId())
            ->getTemplateVarDef($templateVar->getName());
    }
}