<?php

namespace Obcato\Core\frontend;

use Obcato\Core\database\dao\LinkDao;
use Obcato\Core\database\dao\LinkDaoMysql;
use Obcato\Core\elements\image_element\ImageElement;
use Obcato\Core\modules\articles\model\Article;
use Obcato\Core\modules\blocks\model\Block;
use Obcato\Core\modules\pages\model\Page;

class ImageElementFrontendVisual extends ElementFrontendVisual {

    private LinkDao $linkDao;

    public function __construct(Page $page, ?Article $article, ?Block $block, ImageElement $imageElement) {
        parent::__construct($page, $article, $block, $imageElement);
        $this->linkDao = LinkDaoMysql::getInstance();
    }

    public function loadElement(array &$data): void {
        $data["title"] = $this->getElement()->getTitle();
        $data["img_title"] = $this->getElement()->getImage()?->getTitle();
        $data["img_alt_text"] = $this->getElement()->getImage()?->getAltText();
        $data["img_location"] = $this->getElement()->getImage()?->getLocation();
        $data["align"] = $this->getElement()->getAlign();
        $data["width"] = $this->getElement()->getWidth();
        $data["height"] = $this->getElement()->getHeight();
        $data["image_url"] = $this->createImageUrl();
        $data["extension"] = $this->getExtension();

        $linkData = array();
        $openTag = "";
        $closeTag = "";
        if ($this->getElement()->getLinkId()) {
            $link = $this->linkDao->getLink($this->getElement()->getLinkId());
            $url = $this->createUrlFromLink($link);
            $linkData['title'] = $link->getTitle();
            $linkData['url'] = $url;
            $linkData['target'] = $link->getTarget();
            $openTag = "<a href=\"{$url}\" target=\"{$link->getTarget()}\" title=\"{$link->getTitle()}\">";
            $closeTag = "</a>";
        }
        $linkData['open_tag'] = $openTag;
        $linkData['close_tag'] = $closeTag;
        $data['link'] = $linkData;
    }

    private function createImageUrl(): string {
        return $this->getImageUrl($this->getElement()->getImage());
    }

    private function getExtension(): string {
        $image = $this->getElement()->getImage();
        return $image ? $image->getExtension() : "";
    }
}