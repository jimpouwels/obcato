<div class="draggable_wrapper">
    <span class="draggable_id_holder displaynone">{$id}</span>
    <div class="draggable_header">
        <div class="draggable_header_left">
            {$type}
        </div>
        <div class="draggable_header_right">
            <div class="template_picker">
                {$template_picker}
            </div>
            <div class="draggable_action_buttons">
                <a href="#"
                   onclick="deleteFormField('{$id}', '{$text_resources.webforms_delete_item_confirm_message}'); return false;"
                   title="<TODO TITLE>">
                    <img src="/admin/static.php?file=/default/img/default_icons/delete_small.png" alt="<TODO TITLE>"
                         title="<TODO TITLE>"/>
                </a>
            </div>
        </div>
    </div>
    <div class="draggable_body">
        <div id="collapsable_body_{$id}" class="admin_form">
            <div class="form_field_editor_wrapper">
                <div class="admin_form_v2">
                    {$name_field}
                    {$label_field}
                    {$item_editor}
                </div>
            </div>
        </div>
    </div>
</div>


