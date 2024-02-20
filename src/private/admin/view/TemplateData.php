<?php

namespace Obcato\Core;

use Obcato\ComponentApi\TemplateData as ITemplateData;
use Smarty_Internal_Data;

class TemplateData implements ITemplateData {

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