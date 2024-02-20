<?php

namespace Obcato\Core;

interface TemplateDao {
    public function getTemplate(int $id): ?Template;

    public function getTemplatesByScope(Scope $scope): array;

    public function getTemplates(): array;

    public function createTemplate(): Template;

    public function persistTemplate(Template $newTemplate): void;

    public function updateTemplate(Template $template): void;

    public function deleteTemplate(Template $template): void;

    public function getTemplatesForTemplateFile(TemplateFile $templateFile): array;

    public function getTemplateVars(Template $template): array;

    public function storeTemplateVar(Template $template, string $name, ?string $value = ""): TemplateVar;

    public function updateTemplateVar(TemplateVar $templateVar): void;

    public function deleteTemplateVar(TemplateVar $templateVar): void;

    public function getTemplateFiles(): array;

    public function getTemplateFile(int $id): ?TemplateFile;

    public function storeTemplateFile(TemplateFile $templateFile): void;

    public function deleteTemplateFile(TemplateFile $templateFile): void;

    public function updateTemplateFile(TemplateFile $templateFile): void;

    public function getTemplateVarDefs(TemplateFile $template_file): array;

    public function storeTemplateVarDef(TemplateFile $templateFile, string $varDefName): TemplateVarDef;

    public function updateTemplateVarDef(TemplateVarDef $templateVarDef): void;

    public function deleteTemplateVarDef(TemplateVarDef $template_var_def): void;
}