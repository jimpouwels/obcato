<?php

namespace Obcato\Core\admin\modules\logout;

use Obcato\Core\admin\authentication\Authenticator;
use Obcato\Core\admin\request_handlers\HttpRequestHandler;

class LogoutRequestHandler extends HttpRequestHandler {

    public function handleGet(): void {
        Authenticator::logOut();
    }

    public function handlePost(): void {}

}