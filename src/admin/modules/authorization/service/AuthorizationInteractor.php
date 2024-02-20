<?php

namespace Obcato\Core;

class AuthorizationInteractor implements AuthorizationService {

    private static ?AuthorizationInteractor $instance = null;

    private AuthorizationDao $authorizationDao;

    private function __construct() {
        $this->authorizationDao = AuthorizationDaoMysql::getInstance();
    }

    public static function getInstance(): AuthorizationInteractor {
        if (!self::$instance) {
            self::$instance = new AuthorizationInteractor();
        }
        return self::$instance;
    }

    public function getUser(int $id): User {
        return $this->authorizationDao->getUserById($id);
    }
}