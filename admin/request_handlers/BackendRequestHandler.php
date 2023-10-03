<?php

defined('_ACCESS') or die;

require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";
require_once CMS_ROOT . "/core/Blackboard.php";

class BackendRequestHandler extends HttpRequestHandler {

    private ModuleDao $_module_dao;
    private ?Module $_current_module = null;

    public function __construct() {
        $this->_module_dao = ModuleDaoMysql::getInstance();
    }

    public function handleGet(): void {
        $this->loadCurrentModule();
    }

    public function handlePost(): void {
        $this->loadCurrentModule();
    }

    public function getCurrentModule(): ?Module {
        return $this->_current_module;
    }

    public function loadCurrentModule(): void {
        $module_id = intval($this->getParam('module_id'));
        if ($module_id) {
            $this->_current_module = $this->_module_dao->getModule($module_id);
            BlackBoard::$MODULE_ID = $module_id;
            $module_tab_id = intval($this->getParam('module_tab_id'));
            BlackBoard::$MODULE_TAB_ID = $module_tab_id;
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

?>