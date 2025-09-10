<?php

namespace Obcato\Core\modules\database\visuals;

use Obcato\Core\database\MysqlConnector;
use Obcato\Core\view\TemplateData;
use Obcato\Core\view\views\Panel;

class Configuration extends Panel {

    private MysqlConnector $mysqlConnector;

    public function __construct() {
        parent::__construct('database_config_panel_title', 'configuration_panel');
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "database/templates/configuration.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("hostname", $this->mysqlConnector->getHostName());
        $data->assign("database_name", $this->mysqlConnector->getDatabaseName());
        $data->assign("database_type", $this->mysqlConnector->getDatabaseType());
        $data->assign("database_version", $this->mysqlConnector->getDatabaseVersion());
    }
}