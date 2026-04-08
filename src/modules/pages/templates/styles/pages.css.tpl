/* PAGE TREE - Compact with proper nesting and tree lines */
.page_tree {
    overflow-x: auto;
    padding: 0;
}

.page_tree ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.page_tree ul ul {
    padding-left: 20px;
    position: relative;
}

/* Vertical line for nested items */
.page_tree ul ul::before {
    content: '';
    position: absolute;
    left: 18px; /* Further right */
    top: 0;
    bottom: 8px;
    width: 1px;
    background: var(--color-gray-300, #d1d5db);
}

.page_tree li {
    margin: 0;
    padding: 0;
    border-bottom: none;
    position: relative;
}

/* Horizontal line connecting to parent */
.page_tree ul ul > li::before {
    content: '';
    position: absolute;
    left: -2px; /* Starts at vertical line (18px absolute) */
    top: 15px; /* Good height */
    width: 8px; /* Slightly longer */
    height: 1px;
    background: var(--color-gray-300, #d1d5db);
}

/* Hide vertical line after last child */
.page_tree ul ul > li:last-child::after {
    content: '';
    position: absolute;
    left: -2px;
    top: 16px;
    bottom: 0;
    width: 1px;
    background: var(--color-white, white);
    z-index: 1;
}

.page_tree_link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 4px 8px;
    text-decoration: none;
    font-size: 13px;
    line-height: 14px; /* Match icon height for perfect alignment */
    border-radius: 2px;
    transition: background 0.15s;
    border-bottom: none;
    position: relative;
    z-index: 2;
}

.page_tree_link:hover {
    background: var(--color-gray-50, #f9fafb);
    text-decoration: none;
}

.published .page_tree_link::before {
    content: '';
    display: inline-block;
    width: 14px;
    margin-right: 5px;
    height: 14px;
    background-image: url(/admin?file=/modules/pages/img/pages.png);
    background-size: contain;
    background-repeat: no-repeat;
    flex-shrink: 0;
}

.depublished .page_tree_link::before {
    content: '';
    display: inline-block;
    width: 14px;
    height: 14px;
    background-image: url(/admin?file=/modules/pages/img/page_non_published.png);
    background-size: contain;
    background-repeat: no-repeat;
    flex-shrink: 0;
}

.published {
    font-style: normal;
}

.depublished {
    font-style: italic;
}

.not_in_navigation {
    color: #999;
}

.active {
    font-weight: bold;
    background: rgba(34, 113, 177, 0.08);
}

.page_blocks {
    /* Removed old fixed positioning */
}

/* Modern Block Selector */
.block-selector-panel {
    display: flex;
    gap: var(--spacing-lg);
    align-items: flex-start;
    margin-top: var(--spacing-md);
}

.block-selector-column {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.selected-blocks-column {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.block-selector-label {
    display: block;
    font-weight: 600;
    color: var(--color-gray-900);
    font-size: 13px;
}

.block-selector-select {
    width: 100%;
    padding: 8px;
    border: 1px solid var(--color-gray-300);
    border-radius: 6px;
    background: var(--color-white);
    font-size: 13px;
    line-height: 1.5;
    min-height: 200px;
}

.block-selector-select:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.block-selector-select option {
    padding: 4px 8px;
    border-radius: 4px;
}

.selected-blocks-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-height: 200px;
    padding: 8px;
    background: var(--color-gray-50);
    border: 1px solid var(--color-gray-200);
    border-radius: 6px;
}

.selected-block-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    background: var(--color-white);
    border: 1px solid var(--color-gray-200);
    border-radius: 6px;
    gap: var(--spacing-sm);
}

.selected-block-item:hover {
    border-color: var(--color-gray-300);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.selected-block-item.unpublished {
    border-color: #fee;
    background: #fff5f5;
}

.selected-block-item.unpublished .block-title {
    color: #dc2626;
    font-style: italic;
}

.block-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.block-title {
    font-size: 13px;
    color: var(--color-gray-900);
    font-weight: 500;
}

.block-position {
    font-size: 12px;
    color: var(--color-gray-600);
}

.block-delete {
    flex-shrink: 0;
}

.block-delete .admin_label {
    display: none;
}

.no-blocks-message {
    padding: var(--spacing-md);
    text-align: center;
    color: var(--color-gray-500);
    font-style: italic;
    font-size: 13px;
}

.page_metadata_editor textarea {
    width: 95%;
}

.page_metadata_editor .keywords_field {
    width: 95%;
}
