<?php

interface TemplateService {

    public function getTemplateVarDefByTemplateVar(TemplateVar $templateVar): TemplateVarDef;
}