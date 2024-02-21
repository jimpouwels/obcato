<?php

namespace Obcato\Core\admin\core;

use Obcato\ComponentApi\Module;

class Blackboard implements \Obcato\ComponentApi\Blackboard {
    private static Blackboard $instance;

    private ?Module $currentModule = null;
    private int $moduleTabId = 0;

    private function __construct() {}

    public static function getInstance(): Blackboard {
        if (!self::$instance) {
            self::$instance = new Blackboard();
        }
        return self::$instance;
    }

    public function getBackendBaseUrl(): string {
        $baseUrl = sprintf("/admin/index.php?module_id=%s", $this->currentModule->getId());
        $baseUrl .= sprintf("&module_tab_id=%s", $this->moduleTabId);
        return $baseUrl;
    }

    public function getBackendBaseUrlWithoutTab(): string {
        return sprintf("/admin/index.php?module_id=%s", $this->moduleTabId);
    }

    public function getBackendBaseUrlRaw(): string {
        return "/admin/index.php";
    }

    public function setCurrentModule(Module $module): void {
        $this->currentModule = $module;
    }

    public function getCurrentModule(): Module {
        return $this->currentModule;
    }

    public function setCurrentTabId(int $tabId): void {
        $this->moduleTabId = $tabId;
    }

    public function getCurrentTabId(): int {
        return $this->moduleTabId;
    }
}