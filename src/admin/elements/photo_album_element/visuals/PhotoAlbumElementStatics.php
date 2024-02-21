<?php

namespace Obcato\Core\admin\elements\photo_album_element\visuals;

use Obcato\Core\admin\view\views\Visual;

class PhotoAlbumElementStatics extends Visual {

    public function getTemplateFilename(): string {
        return "elements/photo_album_element/photo_album_element_statics.tpl";
    }

    public function load(): void {}

}