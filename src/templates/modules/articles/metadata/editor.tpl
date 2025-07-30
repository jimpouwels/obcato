<form id="metadata_field_form" method="post" action="{$backend_base_url}&metadata_field={$id}">
    <input type="hidden" value="" name="action" id="action" />
    <input type="hidden" value="{$id}" name="metadata_field" id="metadata_field" />

    <div class="admin_form_v2">
        {$name_field}
        {$default_value_field}
    </div>
</form>
