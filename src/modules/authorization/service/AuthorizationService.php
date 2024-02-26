<?php

namespace Obcato\Core\modules\authorization\service;

use Obcato\Core\modules\authorization\model\User;

interface AuthorizationService {
    public function getUser(int $id): User;
}