<?php

require_once CMS_ROOT . "/modules/authorization/model/User.php";

interface AuthorizationService {
    public function getUser(int $id): User;
}