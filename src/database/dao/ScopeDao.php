<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\modules\templates\model\Scope;

interface ScopeDao {
    public function getScopes(): array;

    public function getScope(int $id): ?Scope;

    public function getScopeByIdentifier(string $identifier): ?Scope;

    public function persistScope(Scope $scope): void;

    public function deleteScope(Scope $scope): void;
}