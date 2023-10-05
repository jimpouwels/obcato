<?php
require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once CMS_ROOT . "/request_handlers/HttpRequestHandler.php";

class LogoutRequestHandler extends HttpRequestHandler {

    public function handleGet(): void {
        Authenticator::logOut();
    }

    public function handlePost(): void {}

}

?>