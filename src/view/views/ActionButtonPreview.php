<?php

namespace Pageflow\Core\view\views;

class ActionButtonPreview extends ActionButton {

    public function __construct(string $id) {
        parent::__construct('Preview', $id, 'icon_preview');
    }

}
