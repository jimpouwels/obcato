/* IMAGE SEARCH PANEL */
.image_search_wrapper {
    /* Wrapper for search, flows naturally */
}

.image_search {
    /* Fits in content_left_column */
}

.image_search_form {
    /* Form flows naturally */
}

.image_search .admin_form_field_v2 {
    display: block;
    margin-bottom: var(--spacing-md, 12px);
}

.image_search .admin_label_wrapper {
    margin-bottom: var(--spacing-xs, 4px);
}

.image_search .admin_field_wrapper {
    width: 100%;
}

.image_search .admin_field_wrapper input,
.image_search .admin_field_wrapper select {
    width: 100%;
    box-sizing: border-box;
}

.button_container {
    display: flex;
    gap: var(--spacing-sm, 8px);
    margin-top: var(--spacing-md, 12px);
}

/* IMAGE LIST */
.images_list {
    /* Flows in content_right_column */
}

.article_list_searched_by_text {
    margin-bottom: var(--spacing-md, 12px);
    padding: var(--spacing-md, 12px);
    background: var(--color-gray-50, #f9fafb);
    border-left: 3px solid var(--color-primary, #2271b1);
    font-size: 13px;
}

/* Image list thumbnails */
.images_list .listing tbody td:first-child {
    width: 120px;
    text-align: center;
    vertical-align: middle;
    padding: var(--spacing-md, 12px);
}

.images_list .listing tbody td:first-child img {
    max-width: 100px;
    max-height: 100px;
    width: auto;
    height: auto;
    object-fit: contain;
    display: block;
    margin: 0 auto;
    border-radius: 2px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* IMAGE EDITOR */
.image_editor {
    /* Flows in content_right_column */
}

.image_editor_wrapper .admin_label_wrapper {
    flex: 0 0 180px;
}

.image_editor_wrapper .admin_field {
    width: 100%;
    max-width: 500px;
}

/* IMAGE METADATA */
.image_meta {
    /* Flows in content_right_column */
    margin-top: var(--spacing-lg, 16px);
}

/* FORM OVERRIDES FOR IMAGES MODULE */
.image_search .admin_form,
.image_editor .admin_form {
    padding: 0;
}

/* IMAGE IMPORT FORM */
.image-import-form {
    max-width: 600px;
}

.image-import-form .import-form-content {
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: 8px;
    padding: var(--spacing-xl);
}

.image-import-form .import-help-text {
    margin: 0 0 var(--spacing-lg) 0;
    color: var(--color-gray-600);
    font-size: 14px;
    line-height: 1.6;
}

.image-import-form .import-fields {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.image-import-form .admin_form_field_v2 {
    margin: 0;
}

/* Style file input as button (import + image editor) */
.image-import-form input[type="file"],
#image-editor-form input[type="file"] {
    display: inline-block;
    padding: 8px 16px;
    background: var(--color-primary);
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.image-import-form input[type="file"]:hover,
#image-editor-form input[type="file"]:hover {
    background: var(--color-primary-dark);
}

.image-import-form input[type="file"]::file-selector-button,
#image-editor-form input[type="file"]::file-selector-button {
    display: none;
}
