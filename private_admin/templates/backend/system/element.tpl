<div class="element_root_wrapper" id="element_index_{$index}">
    <span class="element_id_holder displaynone">{$id}</span>
    <div class="element_wrapper {$identifier}">
        <div class="element_header">
            <div class="element_header_left">
                <img src="{$icon_url}" alt="{$type}" />&nbsp;{$type}
            </div>
            <div class="element_header_right">
                {if $include_in_table_of_contents}
                    <div class="include_in_table_of_contents">
                        {$include_in_table_of_contents}
                    </div>
                {/if}
                <div class="template_picker">
                    {$template_picker}
                </div>
                <div class="element_action_buttons">
                    <a href="#" onclick="toggleElement('{$id}'); return false;" title="{$text_resources.element_button_label_minimize}">
                        <img src="/admin/static.php?file=/default/img/default_icons/minimize.png" width="16px" height="16px" alt="{$text_resources.element_button_label_minimize}" title="{$text_resources.element_button_label_minimize}" />
                    </a>
                    <a href="#" onclick="toggleAllElements('{$id}'); return false;" title="{$text_resources.element_button_label_minimize_all}">
                        <img src="/admin/static.php?file=/default/img/default_icons/minimize_all.png" width="16px" height="16px" alt="{$text_resources.element_button_label_minimize_all}" title="{$text_resources.element_button_label_minimize_all}" />
                    </a>
                    <a href="#" onclick="deleteElement('{$id}','{$delete_element_form_id}', '{$text_resources.element_holder_delete_element_confirm_message}'); return false;" title="{$text_resources.element_button_label_delete}">
                        <img src="/admin/static.php?file=/default/img/default_icons/delete_small.png" alt="{$text_resources.element_button_label_delete}" title="{$text_resources.element_button_label_delete}" />
                    </a>
                </div>
            </div>
        </div>
        <div class="element_editor_body">
            <p id="element_summary_text_{$id}" class="element_summary_text">{$summary_text}</p>
            <div id="element_editor_body_{$id}" class="admin_form">
                {$element_form}
            </div>
        </div>
    </div>
</div>