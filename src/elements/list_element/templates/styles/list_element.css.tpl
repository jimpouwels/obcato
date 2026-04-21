/* Compact panel title for list element */
.admin_form_v2 .panel-title {
    padding: 10px 12px;
    font-size: 13px;
    margin-top: 16px;
    margin-bottom: 0;
}

/* Empty state message styling */
.panel-title + em {
    display: block;
    padding: 12px;
    color: var(--color-gray-600, #6b7280);
    font-style: italic;
}

.list-element-sortable-items {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.list_element .button {
    margin-top: 15px;
}

.list_element_values {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-bottom: 0;
    padding: 8px 10px;
    border: 1px solid var(--color-gray-200, #d1d5db);
    border-radius: 8px;
    background: var(--color-white, #fff);
}

/* Add spacing after panel-title */
.panel-title + .list_element_values {
    margin-top: 12px;
}

.panel-title + .list-element-sortable-items {
    margin-top: 12px;
}

.list_element_item_drag_handle {
    display: flex;
    flex-direction: column;
    gap: 3px;
    flex-shrink: 0;
    padding-top: 10px;
    cursor: move;
}

.list_element_item_drag_handle span {
    display: block;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: var(--color-gray-500, #6b7280);
}

.list-element-sortable-item-active {
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14);
}

.list-element-sortable-items .ui-sortable-placeholder {
    visibility: visible !important;
    border: 1px dashed var(--color-gray-400, #9ca3af);
    border-radius: 8px;
    background: var(--color-gray-50, #f9fafb);
    min-height: 64px;
}

.list_element_empty_state {
    display: block;
}

.list-element-add-btn-wrapper {
    margin-top: var(--spacing-lg, 16px);
}

/* Hide labels in list items */
.list_element_values .admin_label_wrapper {
    display: none;
}

.list_element_item_value_field {
    flex: 1;
    min-width: 0; /* Prevent flex item from overflowing */
}

.list_element_item_value_field .admin_form_field_v2 {
    margin: 0; /* Remove default margin on form field */
    width: 100%;
}

.list_element_item_value_field .admin_field_wrapper {
    width: 100% !important;
    max-width: 100% !important;
    margin: 0; /* Remove default margin */
}

.list_element_item_value_field textarea.admin_field_text_area {
    width: 100% !important;
    min-height: 36px;
    resize: none;
    overflow: hidden;
    font-family: inherit;
    line-height: 1.5;
    padding: 8px 12px;
    box-sizing: border-box;
}

.list_element_item_value_field .admin_form_field_v2 {
    margin: 0; /* Remove default margin on form field */
}

.list_element_item_value_delete_field {
    flex-shrink: 0;
    display: flex;
    align-items: center;
}

.list_element_item_value_delete_field .admin_form_field_v2 {
    margin: 0;
}

.list_element_item_value_delete_field .admin_field_wrapper {
    margin: 0;
}

.add_list_item_button {
    margin-top: 8px;
}