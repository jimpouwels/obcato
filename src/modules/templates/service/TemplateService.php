<?php

namespace Pageflow\Core\modules\templates\service;

use Pageflow\Core\modules\templates\model\Template;
use Pageflow\Core\modules\templates\model\TemplateFile;
use Pageflow\Core\modules\templates\model\TemplateVar;
use Pageflow\Core\modules\templates\model\TemplateVarDef;

interface TemplateService {

    public function getTemplateVarDefByTemplateVar(Template $template, TemplateVar $templateVar): TemplateVarDef;

    public function getTemplateVarDefsByTemplate(Template $template): array;

    public function getTemplateFileForTemplate(Template $template): TemplateFile;
}