<?php
require_once CMS_ROOT . "/database/MysqlConnector.php";

class Configuration extends Panel {

    private MysqlConnector $_mysql_connector;

    public function __construct() {
        parent::__construct('database_config_panel_title', 'configuration_panel');
        $this->_mysql_connector = MysqlConnector::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/database/configuration.tpl";
    }

    public function loadPanelContent(Smarty_Internal_Data $data): void {
        $data->assign("hostname", $this->_mysql_connector->getHostName());
        $data->assign("database_name", $this->_mysql_connector->getDatabaseName());
        $data->assign("database_type", $this->_mysql_connector->getDatabaseType());
        $data->assign("database_version", $this->_mysql_connector->getDatabaseVersion());
    }
}
