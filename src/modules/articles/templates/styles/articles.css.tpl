/* ARTICLE SEARCH PANEL */
.article_search {
    /* Fits in content_left_column */
}

.article_search .panel {
    margin-bottom: var(--spacing-md, 12px);
}

.article_search .admin_form_v2 {
    margin-bottom: var(--spacing-md, 12px);
}

.article_search .admin_form_field_v2 {
    display: block;
    margin-bottom: var(--spacing-md, 12px);
}

.article_search .admin_label_wrapper {
    margin-bottom: var(--spacing-xs, 4px);
}

.article_search .admin_field_wrapper {
    width: 100%;
}

.article_search .admin_field_wrapper input,
.article_search .admin_field_wrapper select {
    width: 100%;
    box-sizing: border-box;
}

.button_container {
    display: flex;
    gap: var(--spacing-sm, 8px);
    margin-top: var(--spacing-md, 12px);
}

.show_all_link {
    display: block;
    margin: var(--spacing-md, 12px) 0;
    font-size: 13px;
}

.show_all_link a {
    color: var(--color-gray-600, #6b7280);
}

.show_all_link a:hover {
    color: var(--color-primary, #2271b1);
}

/* ARTICLE LIST */
.article_list {
    /* Flows in content_right_column */
}

.search_job {
    margin-bottom: var(--spacing-lg, 16px);
    padding: var(--spacing-md, 12px);
    background: var(--color-gray-50, #f9fafb);
    border-left: 3px solid var(--color-primary, #2271b1);
    font-size: 13px;
}

.search_job strong {
    color: var(--color-gray-700, #374151);
}

.search_results {
    width: 100%;
    max-width: 100%;
}

.search_results colgroup {
    width: auto;
}

/* ARTICLE METADATA EDITOR */
.article_metadata_editor {
    /* Flows in content_right_column */
}

.article_metadata_editor textarea {
    width: 100%;
    min-height: 120px;
}

.article_metadata_editor .keywords_field {
    width: 100%;
}

/* TERM SELECTOR */
.term_selector {
    /* Flows in content_right_column */
}

/* DATEPICKER */
.datepicker {
    width: auto;
    max-width: 120px;
}

/* ADD IMAGE BUTTON */
.add_image_button {
    margin-top: var(--spacing-sm, 8px);
}

/* TABLE STYLING */
.listing .center_column {
    text-align: center;
}

.listing .center {
    text-align: center;
}

.listing td.center img {
    display: inline-block;
    vertical-align: middle;
}

/* TERM EDITOR */
.term_editor_panel {
    max-width: 900px;
    margin: 0 auto var(--spacing-xl, 24px) auto;
}

.term_list_panel {
    max-width: 900px;
    margin: 0 auto;
}

.terms_listing {
    margin: var(--spacing-md, 12px);
}

/* TARGET PAGES */
.target_pages_fieldset {
    max-width: 900px;
    margin: 0 auto;
}

/* SELECTED PARENT ARTICLE */
.selected_parent_article {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm, 8px);
    padding: var(--spacing-sm, 8px) var(--spacing-md, 12px);
    background: var(--color-gray-50, #f9fafb);
    border: 1px solid var(--color-gray-200, #e5e7eb);
    border-radius: 4px;
    margin-right: var(--spacing-sm, 8px);
    margin-bottom: var(--spacing-sm, 8px);
}

.selected_parent_article p {
    margin: 0;
    font-size: 13px;
}

.selected_parent_article_delete_button {
    display: inline-flex;
}

.selected_parent_article_delete_button a,
.selected_parent_article_delete_button button {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3px;
    border-radius: 2px;
    transition: all 0.15s;
    background: transparent;
    border: none;
    cursor: pointer;
    width: 20px;
    height: 20px;
}

.selected_parent_article_delete_button a:hover,
.selected_parent_article_delete_button button:hover {
    background: var(--color-gray-200, #e5e7eb);
}

.selected_parent_article_delete_button img {
    width: 12px;
    height: 12px;
    opacity: 0.5;
}

.selected_parent_article_delete_button button:hover img {
    opacity: 0.8;
}

/* CHILD ARTICLES */
.article-child-articles {
    padding: var(--spacing-lg, 16px);
    background: var(--color-gray-50, #f9fafb);
    border-radius: 4px;
    margin-top: var(--spacing-md, 12px);
}

/* IMAGE PICKER HIDDEN */
.image-picker-hidden {
    position: absolute;
    left: -9999px;
    width: 1px;
    height: 1px;
    overflow: hidden;
}

/* SELECTED IMAGE DISPLAY (compact view with title) */
.selected_image_display {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm, 8px);
}

.selected_image_info {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm, 8px);
    padding: var(--spacing-sm, 8px) var(--spacing-md, 12px);
    background: var(--color-gray-50, #f9fafb);
    border: 1px solid var(--color-gray-200, #e5e7eb);
    border-radius: 4px;
    margin-right: var(--spacing-sm, 8px);
    margin-bottom: var(--spacing-sm, 8px);
}

.selected_image_info p {
    margin: 0;
    font-size: 13px;
}

.selected_image_info a {
    color: var(--color-primary, #2271b1);
    text-decoration: none;
}

.selected_image_info a:hover {
    text-decoration: underline;
}

.selected_image_delete_button {
    display: inline-flex;
}

.selected_image_delete_button button {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3px;
    border-radius: 2px;
    transition: all 0.15s;
    background: transparent;
    border: none;
    cursor: pointer;
    width: 20px;
    height: 20px;
}

.selected_image_delete_button button:hover {
    background: var(--color-gray-200, #e5e7eb);
}

.selected_image_delete_button img {
    width: 12px;
    height: 12px;
    opacity: 0.5;
}

.selected_image_delete_button button:hover img {
    opacity: 0.8;
}

/* IMAGE MODAL */
.image-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.85);
    animation: fadeIn 0.3s ease;
}

.image-modal-content {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
    z-index: 10001;
    animation: zoomIn 0.3s ease;
}

.image-modal-close {
    position: absolute;
    top: -40px;
    right: 0;
    font-size: 40px;
    color: white;
    cursor: pointer;
    line-height: 1;
    z-index: 10002;
    transition: transform 0.2s ease;
}

.image-modal-close:hover {
    transform: scale(1.2);
}

.image-modal .selected-image-preview {
    max-width: 90vw;
    max-height: 90vh;
}

.image-modal .selected-image-preview img {
    max-width: 90vw;
    max-height: 90vh;
    width: auto;
    height: auto;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes zoomIn {
    from { 
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* SELECTED IMAGE/WALLPAPER PREVIEW */
.selected-image-preview {
    position: relative;
    display: inline-block;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    background: var(--color-gray-100, #f3f4f6);
    min-width: 300px;
    min-height: 200px;
}

.selected-image-preview:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.selected-image-preview img {
    display: block;
    width: 100%;
    min-width: 300px;
    min-height: 200px;
    max-width: 600px;
    height: auto;
    object-fit: cover;
    border-radius: 8px;
}

.selected-image-preview .image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.7) 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    gap: var(--spacing-sm, 8px);
    padding: var(--spacing-xl, 20px);
    opacity: 0;
    transition: opacity 0.25s ease;
    border-radius: 8px;
}

.selected-image-preview:hover .image-overlay {
    opacity: 1;
}

.image-action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-sm, 8px);
    padding: 14px 24px;
    background: rgba(255, 255, 255, 0.95);
    border: none;
    border-radius: 24px;
    color: var(--color-gray-900, #111827);
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    backdrop-filter: blur(10px);
    width: 100%;
    max-width: 200px;
}

.image-action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.image-action-btn.delete-image-btn,
.image-action-btn.delete-image-btn-modal {
    background: rgba(220, 38, 38, 0.95);
    color: white;
}

.image-action-btn.delete-image-btn:hover,
.image-action-btn.delete-image-btn-modal:hover {
    background: rgb(185, 28, 28);
    transform: translateY(-3px) scale(1.05);
}

.image-action-btn.change-image-btn {
    background: rgba(34, 113, 177, 0.95);
    color: white;
}

.image-action-btn.change-image-btn:hover {
    background: rgb(29, 78, 216);
    transform: translateY(-3px) scale(1.05);
}

.image-action-btn .icon-upload,
.image-action-btn .icon-delete {
    display: inline-block;
    width: 20px;
    height: 20px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    filter: brightness(0) invert(1);
}

.image-action-btn .icon-upload {
    background-image: url("/admin?file=/default/img/default_icons/upload.png");
}

.image-action-btn .icon-delete {
    background-image: url("/admin?file=/default/img/default_icons/delete.png");
}

/* METADATA FIELDS COLLAPSIBLE SECTION */
.metadata-fields-section {
    margin-top: var(--spacing-lg, 16px);
}

.metadata-fields-toggle {
    cursor: pointer;
    user-select: none;
    padding: var(--spacing-md, 12px);
    background: var(--color-gray-50, #f9fafb);
    border: 1px solid var(--color-gray-200, #e5e7eb);
    border-radius: 4px;
    margin: 0 0 var(--spacing-md, 12px) 0;
    font-size: 14px;
    font-weight: 600;
    color: var(--color-gray-900, #111827);
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.2s ease;
}

.metadata-fields-toggle:hover {
    background: var(--color-gray-100, #f3f4f6);
}

.metadata-fields-toggle .toggle-icon {
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 6px solid var(--color-gray-600, #6b7280);
    transition: transform 0.2s ease;
}

.metadata-fields-section.collapsed .toggle-icon {
    transform: rotate(-90deg);
}

.metadata-fields-section.collapsed .metadata-fields-content {
    display: none;
}

.metadata-fields-content {
    padding-top: var(--spacing-sm, 8px);
}
}