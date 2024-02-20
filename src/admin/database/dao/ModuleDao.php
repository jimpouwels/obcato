<?php

namespace Obcato\Core\admin\database\dao;

use Obcato\Core\admin\core\model\Module;
use Obcato\Core\admin\core\model\ModuleGroup;

interface ModuleDao {
    public function getAllModules(): array;

    public function getModule(int $id): ?Module;

    public function removeModule(string $identifier): void;

    public function persistModule(Module $module): void;

    public function updateModule(Module $module): void;

    public function getModuleByIdentifier(string $identifier): ?Module;

    public function getModuleGroups(): array;

    public function getModuleGroupByIdentifier(string $identifier): ?ModuleGroup;

    public function getModuleGroup(string $id): ?ModuleGroup;
}