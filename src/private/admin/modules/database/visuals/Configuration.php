<?php
require_once CMS_ROOT . "/database/MysqlConnector.php";

class Configuration extends Panel {

    private MysqlConnector $mysqlConnector;

    public function __construct(TemplateEngine $templateEngine) {
        parent::__construct($templateEngine, 'database_config_panel_title', 'configuration_panel');
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/database/configuration.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("hostname", $this->mysqlConnector->getHostName());
        $data->assign("database_name", $this->mysqlConnector->getDatabaseName());
        $data->assign("database_type", $this->mysqlConnector->getDatabaseType());
        $data->assign("database_version", $this->mysqlConnector->getDatabaseVersion());
    }
}
