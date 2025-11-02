<?php

namespace Obcato\Core\rest;

use Obcato\Core\rest\image\PhotoAlbumElementHandler;
use Obcato\Core\rest\image\ImageHandler;
use Obcato\Core\utilities\UrlHelper;


class Router {

    private array $handlers = array();

    public function __construct() {
        $this->handlers[] = new ImageHandler();
        $this->handlers[] = new PhotoAlbumElementHandler();
    }

    public function route(): void {
        foreach ($this->handlers as $handler) {
            $uriWithoutQueryString = UrlHelper::removeQueryStringFrom($_SERVER['REQUEST_URI']);
            if ($handler->handle(str_replace('/admin/api', '', $uriWithoutQueryString))) {
                return;
            }
        }
    }
}