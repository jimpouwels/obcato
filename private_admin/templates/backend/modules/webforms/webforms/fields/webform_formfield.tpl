<div class="draggable_wrapper">
    <div class="draggable_header">
        <div class="draggable_header_left">
            {$type}
        </div>
        <div class="draggable_header_right">
            <div class="template_picker">
            </div>
            <div class="draggable_action_buttons">
                <a href="#" onclick="deleteWebFormField('{$id}','<TODO_DELETE_FORM_ID>', 'zeker wetuh?'); return false;" title="<TODO TITLE>">
                    <img src="/admin/static.php?file=/default/img/default_icons/delete_small.png" alt="<TODO TITLE>" title="<TODO TITLE>" />
                </a>
            </div>
        </div>
    </div>
    <div class="element_editor_body">
        <div id="element_editor_body_{$id}" class="admin_form">
            <div class="form_field_editor_wrapper">
                <ul class="admin_form">
                    <li>
                        {$name_field}
                    </li>
                    <li>
                        {$label_field}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


