<?php

namespace Obcato\Core\view;

use Obcato\Core\view\views\Visual;
use Smarty_Internal_Data;

class TemplateData {

    private Smarty_Internal_Data $data;

    public function __construct(Smarty_Internal_Data $data) {
        $this->data = $data;
    }

    public function assign($tplVar, $value = null): void {
        $this->data->assign($tplVar, $value);
    }

    public function assignVisual($tplVar, ?Visual $presentable = null): void {
        $this->data->assign($tplVar, $presentable ? $presentable->render() : "");
    }

    public function getData(): Smarty_Internal_Data {
        return $this->data;
    }

}