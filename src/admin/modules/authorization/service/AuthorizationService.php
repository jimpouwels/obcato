<?php

namespace Obcato\Core\admin\modules\authorization\service;

use Obcato\Core\admin\modules\authorization\model\User;

interface AuthorizationService {
    public function getUser(int $id): User;
}