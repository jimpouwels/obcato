<?php

namespace Obcato\Core;

interface AuthorizationService {
    public function getUser(int $id): User;
}