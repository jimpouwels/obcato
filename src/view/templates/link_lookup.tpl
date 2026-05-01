<div class="link-lookup" id="link-lookup-{$field_name}">
    <input type="hidden" id="{$field_name}" name="{$field_name}" value="{$field_value}" />

    {* No link selected *}
    <div id="link-lookup-empty-{$field_name}" {if $selected_link_id}style="display:none;"{/if}>
        <button type="button" class="link-lookup-trigger-btn"
                onclick="openLinkPickerModal(function(link) { selectLinkLookup('{$field_name}', link.id, link.name, link.url); });">
            &#128279; Link selecteren&hellip;
        </button>
    </div>

    {* Link selected: preview card *}
    <div class="link-lookup-preview" id="link-lookup-preview-{$field_name}" {if !$selected_link_id}style="display:none;"{/if}>
        <div class="link-lookup-preview-info">
            <div class="link-lookup-preview-title" id="link-lookup-title-{$field_name}">{$selected_link_name|escape}</div>
            <div class="link-lookup-preview-url" id="link-lookup-url-{$field_name}">{$selected_link_url|escape}</div>
        </div>
        <div class="link-lookup-preview-actions">
            <button type="button" class="link-lookup-preview-change"
                    onclick="openLinkPickerModal(function(link) { selectLinkLookup('{$field_name}', link.id, link.name, link.url); });">Wijzig</button>
            <button type="button" class="clear-selection"
                    onclick="clearLinkLookup('{$field_name}');" title="Selectie wissen">&times;</button>
        </div>
    </div>
</div>

<script type="text/javascript">
(function () {
    window.selectLinkLookup = window.selectLinkLookup || function (fieldName, linkId, linkTitle, linkUrl) {
        document.getElementById(fieldName).value = linkId;
        document.getElementById('link-lookup-title-' + fieldName).textContent = linkTitle || '';
        document.getElementById('link-lookup-url-' + fieldName).textContent = linkUrl || '';
        document.getElementById('link-lookup-empty-' + fieldName).style.display = 'none';
        document.getElementById('link-lookup-preview-' + fieldName).style.display = 'flex';
        var defaultField = document.getElementById('link-lookup-default-value-' + fieldName);
        if (defaultField) defaultField.style.display = 'none';
    };

    window.clearLinkLookup = window.clearLinkLookup || function (fieldName) {
        document.getElementById(fieldName).value = '';
        document.getElementById('link-lookup-empty-' + fieldName).style.display = '';
        document.getElementById('link-lookup-preview-' + fieldName).style.display = 'none';
        var defaultField = document.getElementById('link-lookup-default-value-' + fieldName);
        if (defaultField) defaultField.style.display = '';
    };
})();
</script>
