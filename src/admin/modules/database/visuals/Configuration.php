<?php

namespace Obcato\Core\admin\modules\database\visuals;

use Obcato\Core\admin\database\MysqlConnector;
use Obcato\Core\admin\view\TemplateData;
use Obcato\Core\admin\view\views\Panel;

class Configuration extends Panel {

    private MysqlConnector $mysqlConnector;

    public function __construct() {
        parent::__construct('database_config_panel_title', 'configuration_panel');
        $this->mysqlConnector = MysqlConnector::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/database/configuration.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $data->assign("hostname", $this->mysqlConnector->getHostName());
        $data->assign("database_name", $this->mysqlConnector->getDatabaseName());
        $data->assign("database_type", $this->mysqlConnector->getDatabaseType());
        $data->assign("database_version", $this->mysqlConnector->getDatabaseVersion());
    }
}