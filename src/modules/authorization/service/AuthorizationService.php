<?php

namespace Pageflow\Core\modules\authorization\service;

use Pageflow\Core\modules\authorization\model\User;

interface AuthorizationService {
    public function getUser(int $id): User;
}