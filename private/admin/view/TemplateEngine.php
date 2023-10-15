<?php
require_once CMS_ROOT . "/view/smarty/Smarty.class.php";

class TemplateEngine {
    private static ?TemplateEngine $_instance = null;
    private Smarty $smarty;

    private function __construct(Smarty $smarty) {
        $this->smarty = $smarty;
    }

    public static function getInstance(): TemplateEngine {
        if (!self::$_instance) {
            $smarty = new Smarty();
            $smarty->template_dir = BACKEND_TEMPLATE_DIR;
            $smarty->compile_dir = BACKEND_TEMPLATE_DIR . "/compiled_templates";
            $smarty->cache_dir = BACKEND_TEMPLATE_DIR . "/cache";
            self::$_instance = new TemplateEngine($smarty);
        }
        return self::$_instance;
    }

    public function assign(string $key, mixed $value): void {
        $this->smarty->assign($key, $value);
    }

    public function fetch(string $template, ?Smarty_Internal_Data $data = null): string {
        if ($data) {
            $tpl = $this->smarty->createTemplate($template, $data);
            return $tpl->fetch();
        } else {
            return $this->smarty->fetch($template);
        }
    }

    public function createChildData(): Smarty_Internal_Data {
        return $this->smarty->createData($this->smarty);
    }

    public function display(string $template): void {
        $this->smarty->display($template);
    }
}
