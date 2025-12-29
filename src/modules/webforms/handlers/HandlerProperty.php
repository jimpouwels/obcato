<?php

namespace Obcato\Core\modules\webforms\handlers;

use Obcato\Core\view\views\Visual;

class HandlerProperty {

    private string $name;
    private string $type;
    private ?Visual $editor;

    public function __construct(string $name, string $type, Visual $editor = null) {
        $this->name = $name;
        $this->type = $type;
        $this->editor = $editor;
    }
    
    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setType(string $type): void {
        $this->type = $type;
    }

    public function getType(): string {
        return $this->type;
    }

    public function setEditor(?Visual $editor): void {
        $this->editor = $editor;
    }

    public function getEditor(): ?Visual {
        return $this->editor;
    }

}