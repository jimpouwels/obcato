<?php

namespace Obcato\Core\view;

use Smarty;
use const Obcato\Core\BACKEND_TEMPLATE_DIR;


class TemplateEngine {
    private static ?TemplateEngine $_instance = null;
    private Smarty $smarty;

    private function __construct(Smarty $smarty) {
        $this->smarty = $smarty;
    }

    public static function getInstance(): TemplateEngine {
        if (!self::$_instance) {
            $smarty = new Smarty();
            $smarty->registerPlugin("modifier", "strstr", "strstr");
            $smarty->setTemplateDir(BACKEND_TEMPLATE_DIR);
            $smarty->setCompileDir(BACKEND_TEMPLATE_DIR . "/compiled_templates");
            $smarty->setCacheDir(BACKEND_TEMPLATE_DIR . "/cache");
            self::$_instance = new TemplateEngine($smarty);
        }
        return self::$_instance;
    }

    public function assign(string $key, mixed $value): void {
        $this->smarty->assign($key, $value);
    }

    public function getGlobal(string $key): mixed {
        return $this->smarty->getTemplateVars($key);
    }

    public function fetch(string $template, ?TemplateData $data = null): string {
        if ($data) {
            $tpl = $this->smarty->createTemplate($template, $data->getData());
            return $tpl->fetch();
        } else {
            return $this->smarty->fetch($template);
        }
    }

    public function createChildData(): TemplateData {
        return new TemplateData($this->smarty->createData($this->smarty));
    }

    public function display(string $template): void {
        $this->smarty->display($template);
    }
}
