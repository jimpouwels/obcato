<?php
defined('_ACCESS') or die;

require_once CMS_ROOT . "view/smarty/Smarty.class.php";

class TemplateEngine {
    private static ?TemplateEngine $_instance = null;
    private Smarty $_smarty;

    private function __construct(Smarty $smarty) {
        $this->_smarty = $smarty;
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
        $this->_smarty->assign($key, $value);
    }

    public function fetch(string $template, ?Smarty_Internal_Data $data = null): string {
        if ($data) {
            $tpl = $this->_smarty->createTemplate($template, $data);
            return $tpl->fetch();
        } else {
            return $this->_smarty->fetch($template);
        }
    }

    public function createChildData(?Smarty_Internal_Data $parent_data = null): Smarty_Internal_Data {
        $parent_template = $this->_smarty;
        if ($parent_data) {
            $parent_template = $parent_data;
        }
        return $this->_smarty->createData($parent_template);
    }

    public function display(string $template): void {
        $this->_smarty->display($template);
    }
}
