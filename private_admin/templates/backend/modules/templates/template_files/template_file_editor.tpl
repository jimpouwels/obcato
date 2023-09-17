<input type="hidden" name="template_file_id" id="template_file_id" value="{$id}" />
<div class="admin_form_v2">
    {$name_field}
    {$filename_field}
    {foreach from=$var_defs item=var_def}
        {$var_def}
    {/foreach}
</div>