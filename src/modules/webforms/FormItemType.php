<?php

namespace Obcato\Core\modules\webforms;

class FormItemType {
    private string $typeName;
    private string $backendVisualClassname;
    private string $backendFormClassname;
    private string $frontendVisualClassname;

    public function __construct(string $typeName, string $backendVisualClassname, string $backendFormClassname, string $frontendVisualClassname) {
        $this->typeName = $typeName;
        $this->backendVisualClassname = $backendVisualClassname;
        $this->backendFormClassname = $backendFormClassname;
        $this->frontendVisualClassname = $frontendVisualClassname;
    }

    public function getTypeName(): string {
        return $this->typeName;
    }

    public function getBackendVisualClassname(): string {
        return $this->backendVisualClassname;
    }

    public function getBackendFormClassname(): string {
        return $this->backendFormClassname;
    }

    public function getFrontendVisualClassname(): string {
        return $this->frontendVisualClassname;
    }
}