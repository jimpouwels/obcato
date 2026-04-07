/* TEMPLATE EDITOR */
.admin_form > li {
    list-style: none outside none;
    clear: both;
}

/* TEMPLATE SELECTOR */
.scope_selector_panel {
    float: left;
    width: 355px;
}

.scope_selector_panel ul {
    padding-left: 0;
    margin: 0;
    list-style: none;
}

.scope_selector_panel li {
    margin: 0;
    border-bottom: 1px solid var(--color-gray-200);
}

.scope_selector_panel a {
    text-decoration: none;
    color: var(--color-gray-900);
    padding: var(--spacing-sm) var(--spacing-md);
    display: block;
    font-size: 13px;
    transition: all 0.2s;
}

.scope_selector_panel a:hover {
    background: var(--color-gray-50);
}

.scope_selector_panel li.active a,
.scope_selector_panel li.selected a {
    background: var(--color-blue-50);
    color: var(--color-primary);
    font-weight: 600;
    border-left: 3px solid var(--color-primary);
    padding-left: calc(var(--spacing-md) - 3px);
}

/* TEMPLATE LIST */

.template_listing .last .admin_label {
    display: none;
}

/* TEMPLATE FILES */
.template_files_list_panel {
    width: 355px;
    float: left;
}

.template_files_list_panel ul {
    padding: 0;
    margin: 0;
    list-style: none;
}

.template_files_list_panel li {
    margin: 0;
    border-bottom: 1px solid var(--color-gray-200);
}

.template_files_list_panel a {
    text-decoration: none;
    color: var(--color-gray-900);
    padding: var(--spacing-sm) var(--spacing-md);
    display: block;
    font-size: 13px;
    transition: all 0.2s;
}

.template_files_list_panel a:hover {
    background: var(--color-gray-50);
}

.template_files_list_panel li.active a {
    background: var(--color-blue-50);
    color: var(--color-primary);
    font-weight: 600;
    border-left: 3px solid var(--color-primary);
    padding-left: calc(var(--spacing-md) - 3px);
}

.template_file_editor_panel {
    margin: auto;
    width: 1050px;
}

.template_file_editor_panel .admin_label_wrapper {
    width: 30%;
}

.template_file_editor_panel .admin_field {
    width: 250px;
}

.template_var_migration_panel {
    width: 1050px;
    margin: 30px auto auto;
}

.template_content_panel {
    margin: 30px auto auto;
    font-size: 1.2em;
    width: 1050px;
}

.template_content_panel .markup {
    color: gray;
}