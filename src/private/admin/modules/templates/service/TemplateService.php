<?php

namespace Obcato\Core;

interface TemplateService {

    public function getTemplateVarDefByTemplateVar(Template $template, TemplateVar $templateVar): TemplateVarDef;

    public function getTemplateVarDefsByTemplate(Template $template): array;

    public function getTemplateFileForTemplate(Template $template): TemplateFile;
}