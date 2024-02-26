<?php

namespace Obcato\Core\modules\logout;

use Obcato\Core\authentication\Authenticator;
use Obcato\Core\request_handlers\HttpRequestHandler;

class LogoutRequestHandler extends HttpRequestHandler {

    public function handleGet(): void {
        Authenticator::logOut();
    }

    public function handlePost(): void {}

}