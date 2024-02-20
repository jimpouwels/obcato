<?php

namespace Obcato\Core\admin;

use Obcato\Core\admin\core\model\ElementType;
use Obcato\Core\admin\core\model\Module;
use Obcato\Core\admin\database\dao\ElementDao;
use Obcato\Core\admin\database\dao\ElementDaoMysql;
use Obcato\Core\admin\database\dao\ModuleDao;
use Obcato\Core\admin\database\dao\ModuleDaoMysql;

class TextResourceLoader {

    private string $language;
    private ModuleDao $_module_dao;
    private ElementDao $_element_dao;

    public function __construct(string $language) {
        $this->language = $language;
        $this->_module_dao = ModuleDaoMysql::getInstance();
        $this->_element_dao = ElementDaoMysql::getInstance();
    }

    public function loadTextResources(): array {
        $text_resources = $this->getGlobalTextResources();
        foreach ($this->_module_dao->getAllModules() as $module) {
            $text_resources = array_merge($text_resources, $this->getModuleTextResources($module));
        }
        foreach ($this->_element_dao->getElementTypes() as $element_type) {
            $text_resources = array_merge($text_resources, $this->getElementTextResources($element_type));
        }
        return $text_resources;
    }

    private function getGlobalTextResources(): array {
        return $this->getTextResourcesFromFile(STATIC_DIR . '/text_resources/common-' . $this->language . '.txt');
    }

    private function getModuleTextResources(Module $module): array {
        return $this->getTextResourcesFromFile($this->getResourceFilePath($module->getIdentifier()));
    }

    private function getElementTextResources(ElementType $element_type): array {
        return $this->getTextResourcesFromFile($this->getResourceFilePath($element_type->getIdentifier()));
    }

    private function getTextResourcesFromFile(string $file_path): array {
        $resources = array();
        if (file_exists($file_path)) {
            $file = fopen($file_path, 'rb');
            while (!feof($file)) {
                $line = fgets($file);
                if (!$this->isComment($line) && !$this->isEmptyLine($line)) {
                    $parts = explode(':', $line, 2);
                    if (count($parts) > 1) {
                        $resources[trim($parts[0])] = trim($parts[1]);
                    }
                }
            }
        }
        return $resources;
    }

    private function getResourceFilePath(string $identifier): string {
        $path = STATIC_DIR . '/text_resources/' . $identifier . '-' . $this->language . '.txt';
        if (!file_exists($path)) {
            $path = STATIC_DIR . '/text_resources/' . $identifier . '-nl.txt';
        }
        return $path;
    }

    private function isComment($line): bool {
        return str_starts_with(trim($line), '#');
    }

    private function isEmptyLine($line): bool {
        return trim($line) == '';
    }

}