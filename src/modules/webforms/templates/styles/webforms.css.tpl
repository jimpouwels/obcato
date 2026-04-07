.webforms_editor_add_buttons {
    overflow-y: auto;
}

.webforms_editor_form_fields {
    clear: both;
    margin-top: 15px;
    overflow-y: auto;
}

.webforms_list ul {
    padding: 0;
    margin: 0;
    list-style: none;
}

.webforms_list li {
    margin: 0;
    border-bottom: 1px solid var(--color-gray-200);
}

.webforms_list a {
    text-decoration: none;
    color: var(--color-gray-900);
    padding: var(--spacing-sm) var(--spacing-md);
    display: block;
    font-size: 13px;
    transition: all 0.2s;
}

.webforms_list a:hover {
    background: var(--color-gray-50);
}

.webforms_list .selected a {
    background: var(--color-blue-50);
    color: var(--color-primary);
    font-weight: 600;
    border-left: 3px solid var(--color-primary);
    padding-left: calc(var(--spacing-md) - 3px);
}

.form_field_editor_wrapper textarea {
    width: 100%;
}