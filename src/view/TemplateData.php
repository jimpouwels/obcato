<?php

namespace Obcato\Core\view;

use Smarty_Internal_Data;

class TemplateData {

    private Smarty_Internal_Data $data;

    public function __construct(Smarty_Internal_Data $data) {
        $this->data = $data;
    }

    public function assign($tpl_var, $value = null): void {
        $this->data->assign($tpl_var, $value);
    }

    public function getData(): Smarty_Internal_Data {
        return $this->data;
    }

}