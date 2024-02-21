<?php

namespace Obcato\Core\admin\request_handlers;

use Obcato\ComponentApi\BlackBoard;
use Obcato\Core\admin\core\model\Module;
use Obcato\Core\admin\database\dao\ModuleDao;
use Obcato\Core\admin\database\dao\ModuleDaoMysql;

class BackendRequestHandler extends HttpRequestHandler {

    private ModuleDao $moduleDao;
    private ?Module $currentModule = null;

    public function __construct() {
        $this->moduleDao = ModuleDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->loadCurrentModule();
    }

    public function handlePost(): void {
        $this->loadCurrentModule();
    }

    public function getCurrentModule(): ?Module {
        return $this->currentModule;
    }

    public function loadCurrentModule(): void {
        $moduleId = intval($this->getParam('module_id'));
        if ($moduleId) {
            $this->currentModule = $this->moduleDao->getModule($moduleId);
            BlackBoard::$MODULE_ID = $moduleId;
            $moduleTabId = intval($this->getParam('module_tab_id'));
            BlackBoard::$MODULE_TAB_ID = $moduleTabId;
        }
    }

    private function getParam(string $name): ?string {
        $value = null;
        if (isset($_GET[$name])) {
            $value = $_GET[$name];
        } else if (isset($_POST[$name])) {
            $value = $_POST[$name];
        }
        return $value;
    }

}