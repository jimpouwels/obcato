<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "/request_handlers/http_request_handler.php";

class RestRequestHandler extends HttpRequestHandler {

    public function handleGet(): void {}

    public function handlePost(): void {}
}

?>