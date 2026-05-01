<form method="post" action="{$backend_base_url}&amp;link={$link_id}" id="link-editor-form">
    <input type="hidden" name="action" value="update_link" />
    <input type="hidden" name="link_id" value="{$link_id}" />
    <div class="admin_form_v2">
        {$name_field}
        {$title_field}
        {$url_field}
    </div>
    <button type="submit" class="displaynone" id="submit_update_link">Opslaan</button>
</form>
