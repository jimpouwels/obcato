<?php

namespace Obcato\Core\database\dao;

use Obcato\Core\modules\authorization\model\User;

interface AuthorizationDao {
    public function getUser(string $username): ?User;

    public function getUserById(int $id): ?User;

    public function getAllUsers(): array;

    public function updateUser(User $user): void;

    public function deleteUser(int $userId): void;

    public function createUser(): User;
}