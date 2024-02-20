<?php

namespace Obcato\Core;

class LogoutRequestHandler extends HttpRequestHandler {

    public function handleGet(): void {
        Authenticator::logOut();
    }

    public function handlePost(): void {}

}