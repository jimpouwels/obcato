<form method="post" action="{$backend_base_url}&amp;folder={$folder_id}" id="folder-editor-form">
    <input type="hidden" name="action" value="update_folder" />
    <input type="hidden" name="folder_id" value="{$folder_id}" />
    <div class="admin_form_v2">
        {$name_field}
    </div>
    <button type="submit" class="displaynone" id="submit_update_folder">Opslaan</button>
</form>
