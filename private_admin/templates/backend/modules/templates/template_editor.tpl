<form id="template_editor_form" name="template_editor_form" method="post" action="{$backend_base_url}&template={$template_id}" enctype="multipart/form-data">
    <input type="hidden" name="template_id" id="template_id" value="{$template_id}" />
    <input type="hidden" name="action" id="action" value="update_template" />
    <div class="admin_form_v2">
        {$name_field}
        {$filename_field}
        {$scopes_field}
        {$upload_field}
    </div>
</form>
