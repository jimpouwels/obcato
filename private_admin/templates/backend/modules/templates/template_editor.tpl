<form id="template_editor_form" name="template_editor_form" method="post" action="{$backend_base_url}&template={$template_id}" enctype="multipart/form-data">
    <input type="hidden" name="template_id" id="template_id" value="{$template_id}" />
    <input type="hidden" name="action" id="action" value="update_template" />
    <ul class="admin_form">
        <li>{$name_field}</li>
        <li>{$filename_field}</li>
        <li>{$scopes_field}</li>
        <li>{$upload_field}</li>
    </ul>
</form>
