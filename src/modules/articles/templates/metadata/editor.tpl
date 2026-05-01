<form id="metadata_field_form" method="post" action="{$backend_base_url}&metadata_field={$id}">
    <input type="hidden" value="" name="action" id="action" />
    <input type="hidden" value="{$id}" name="metadata_field" id="metadata_field" />

    <div class="admin_form_v2">
        {$name_field}
        <div id="link-lookup-default-value-link_id" {if $has_link}style="display:none;"{/if}>
            {$default_value_field}
        </div>
        {$link_field}
    </div>
</form>
