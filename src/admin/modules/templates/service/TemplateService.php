<?php

namespace Obcato\Core\admin\modules\templates\service;

use Obcato\Core\admin\modules\templates\model\Template;
use Obcato\Core\admin\modules\templates\model\TemplateFile;
use Obcato\Core\admin\modules\templates\model\TemplateVar;
use Obcato\Core\admin\modules\templates\model\TemplateVarDef;

interface TemplateService {

    public function getTemplateVarDefByTemplateVar(Template $template, TemplateVar $templateVar): TemplateVarDef;

    public function getTemplateVarDefsByTemplate(Template $template): array;

    public function getTemplateFileForTemplate(Template $template): TemplateFile;
}