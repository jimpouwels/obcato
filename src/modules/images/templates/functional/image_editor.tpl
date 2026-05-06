<form method="post" action="{$backend_base_url}&amp;fimg={$fimg_id}" id="fimg-editor-form" enctype="multipart/form-data">
    <input type="hidden" name="action" value="update_functional_image" />
    <input type="hidden" name="fimg_id" value="{$fimg_id}" />
    <div class="admin_form_v2">
        {$title_field}
        {$alt_field}
        {$published_field}
        {$upload_field}
    </div>
    {if $preview_url}
        <div class="fimg-preview">
            <img src="{$preview_url}" alt="{$image_title|escape}" title="{$image_title|escape}" style="max-width: 300px; max-height: 300px;" />
        </div>
    {/if}
    <button type="submit" class="displaynone" id="submit_update_fimg">Opslaan</button>
</form>
