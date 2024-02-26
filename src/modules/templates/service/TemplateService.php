<?php

namespace Obcato\Core\modules\templates\service;

use Obcato\Core\modules\templates\model\Template;
use Obcato\Core\modules\templates\model\TemplateFile;
use Obcato\Core\modules\templates\model\TemplateVar;
use Obcato\Core\modules\templates\model\TemplateVarDef;

interface TemplateService {

    public function getTemplateVarDefByTemplateVar(Template $template, TemplateVar $templateVar): TemplateVarDef;

    public function getTemplateVarDefsByTemplate(Template $template): array;

    public function getTemplateFileForTemplate(Template $template): TemplateFile;
}