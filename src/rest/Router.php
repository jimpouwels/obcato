<?php

namespace Pageflow\Core\rest;

use Pageflow\Core\rest\article\ArticleHandler;
use Pageflow\Core\rest\element_holder\ElementHolderHandler;
use Pageflow\Core\rest\image\ImageElementHandler;
use Pageflow\Core\rest\image\PhotoAlbumElementHandler;
use Pageflow\Core\rest\image\ImageHandler;
use Pageflow\Core\rest\page\PageSearchHandler;
use Pageflow\Core\utilities\UrlHelper;


class Router {

    private array $handlers = array();

    public function __construct() {
        $this->handlers[] = new ImageHandler();
        $this->handlers[] = new PhotoAlbumElementHandler();
        $this->handlers[] = new ImageElementHandler();
        $this->handlers[] = new ArticleHandler();
        $this->handlers[] = new PageSearchHandler();
        $this->handlers[] = new ElementHolderHandler();
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