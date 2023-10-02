<?php
defined('_ACCESS') or die;

interface TemplateDao {
    public function getTemplate(int $id): ?Template;

    public function getTemplatesByScope(Scope $scope): array;

    public function getTemplates(): array;

    public function createTemplate(): Template;

    public function persistTemplate(Template $new_template): void;

    public function updateTemplate(Template $template): void;

    public function deleteTemplate(Template $template): void;

    public function getTemplatesForTemplateFile(TemplateFile $template_file): array;

    public function getTemplateVars(Template $template): array;

    public function storeTemplateVar(Template $template, string $name, ?string $value = ""): TemplateVar;

    public function updateTemplateVar(TemplateVar $template_var): void;

    public function deleteTemplateVar(TemplateVar $template_var): void;

    public function getTemplateFiles(): array;

    public function getTemplateFile(int $id): ?TemplateFile;

    public function storeTemplateFile(TemplateFile $template_file): void;

    public function deleteTemplateFile(TemplateFile $template_file): void;

    public function updateTemplateFile(TemplateFile $template_file): void;

    public function getTemplateVarDefs(TemplateFile $template_file): array;

    public function storeTemplateVarDef(TemplateFile $template_file, string $var_def_name): TemplateVarDef;

    public function updateTemplateVarDef(TemplateVarDef $template_var_def): void;

    public function deleteTemplateVarDef(TemplateVarDef $template_var_def): void;
}