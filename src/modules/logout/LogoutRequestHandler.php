<?php

namespace Pageflow\Core\modules\logout;

use Pageflow\Core\authentication\Authenticator;
use Pageflow\Core\request_handlers\HttpRequestHandler;

class LogoutRequestHandler extends HttpRequestHandler {

    public function handleGet(): void {
        Authenticator::logOut();
    }

    public function handlePost(): void {}

}