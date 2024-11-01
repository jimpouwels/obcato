<?php

namespace Obcato\Core\request_handlers;

use const Obcato\core\STATIC_DIR;

class StaticsRequestHandler extends HttpRequestHandler {

    public function __construct() {}

    public function handleGet(): void {
        $relativePath = $this->getRelativePathFromGetRequest();
        if (!empty($relativePath)) {
            $absolutePath = $this->getAbsolutePathFor($relativePath);
            $this->setResponseContentType($absolutePath);
            readfile(explode('?', $absolutePath)[0]);
        }
    }

    public function handlePost(): void {}

    private function setResponseContentType(string $absolute_path): void {
        $path_parts = explode(".", $absolute_path);
        $extension = $path_parts[count($path_parts) - 1];
        if ($extension == "jpg") {
            header("Content-Type: image/jpeg");
        } else if ($extension == "gif") {
            header("Content-Type: image/gif");
        } else if ($extension == "png") {
            header("Content-Type: img/png");
        } else if ($extension == "css") {
            header("Content-Type: text/css");
        } else if ($extension == "js") {
            header("Content-Type: text/javascript");
        } else if ($extension == "ttf") {
            header("Content-Type: application/x-font-ttf");
        }
    }

    private function getAbsolutePathFor(string $relative_path): string {
        return STATIC_DIR . $relative_path;
    }

    private function getRelativePathFromGetRequest(): ?string {
        if (isset($_GET["file"]) && $_GET["file"] != "") {
            return $_GET["file"];
        }
        return null;
    }
}