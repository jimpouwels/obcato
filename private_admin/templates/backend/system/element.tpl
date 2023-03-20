<div id="element_index_{$index}">
    <span class="element_id_holder displaynone">{$id}</span>
    <div class="element_wrapper {$identifier}">
        <div class="element_header">
            <img src="{$icon_url}" alt="{$type}" />&nbsp;{$type}
            <div class="element_header_right">
                <div class="template_picker">
                    {$template_picker}
                </div>
                <div class="delete_button">
                    <a href="#" onclick="deleteElement('{$id}','{$delete_element_form_id}');" title="Verwijder element">
                        <img src="/admin/static.php?file=/default/img/default_icons/delete_small.png" alt="Verwijderen" title="Verwijderen" />
                    </a>
                </div>
            </div>
        </div>
        <div class="element_form admin_form">
            {$element_form}
        </div>
    </div>
</div>