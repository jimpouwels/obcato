<form method="post" action="{$backend_base_url}&amp;fimg_folder={$folder_id}" id="fimg-folder-editor-form">
    <input type="hidden" name="action" value="update_functional_image_folder" />
    <input type="hidden" name="fimg_folder_id" value="{$folder_id}" />
    <div class="admin_form_v2">
        {$name_field}
    </div>
    <button type="submit" class="displaynone" id="submit_update_fimg_folder">Opslaan</button>
</form>
