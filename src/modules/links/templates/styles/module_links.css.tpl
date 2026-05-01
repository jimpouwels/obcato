.links-module-root .content_left_column {
    width: 420px;
}

.links-module-root .links-content-right-column {
    margin-left: 440px;
}

.links-tree {
    padding: 6px 0;
}

.links-folder {
    margin-bottom: 1px;
}

.links-folder-header {
    display: flex;
    align-items: center;
    position: relative;
    padding: 3px 8px;
    border-radius: 4px;
}

.links-folder-header:hover {
    background: #f0f0f0;
}

.links-folder-name {
    flex: 1;
    font-weight: 600;
    font-size: 13px;
    color: #333;
    text-decoration: none;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.links-folder-name:hover {
    text-decoration: underline;
}

.links-folder-name.selected {
    color: var(--color-primary, #2a7ab8);
}

.links-folder-actions {
    display: none;
    position: absolute;
    right: 4px;
    top: 50%;
    transform: translateY(-50%);
    gap: 2px;
    align-items: center;
    background: #f0f0f0;
    border-radius: 4px;
    padding: 0 2px;
}

.links-folder-header:hover .links-folder-actions {
    display: flex;
}

.links-folder-actions a,
.links-folder-actions button {
    background: none;
    border: none;
    cursor: pointer;
    padding: 2px 3px;
    font-size: 12px;
    border-radius: 3px;
    text-decoration: none;
    color: #555;
}

.links-folder-actions a:hover,
.links-folder-actions button:hover {
    background: #ddd;
}

.links-folder-children {
    padding-left: 12px;
    border-left: 2px solid #e0e0e0;
    margin-left: 8px;
}

.links-link-item {
    display: flex;
    align-items: center;
    padding: 3px 8px;
    border-radius: 4px;
    cursor: grab;
    font-size: 13px;
    overflow: hidden;
}

.links-link-item.links-dragging {
    opacity: 0.3;
}

.links-drag-placeholder {
    background: #e8f0fe;
    border: 2px dashed #4a90d9;
    border-radius: 4px;
    margin: 2px 0;
}

.links-link-item:hover {
    background: #f5f5f5;
}

.links-link-item.selected {
    background: #e8f0fe;
    border-left: 3px solid var(--color-primary, #2a7ab8);
}

.links-link-item.drag-over {
    background: #e8f0fe;
    outline: 2px dashed #4a90d9;
}

.links-link-icon {
    flex-shrink: 0;
    font-size: 14px;
    line-height: 1;
    align-self: center;
}

.links-link-title {
    flex: 1;
    min-width: 0;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    font-size: 13px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.links-link-title:hover {
    text-decoration: underline;
}

.links-link-item.selected .links-link-title {
    color: var(--color-primary, #2a7ab8);
    font-weight: 600;
}

.links-empty-state {
    padding: 20px;
    color: #888;
    font-style: italic;
    font-size: 13px;
}

/* Drop zone on folders */
.links-folder-header.drop-target {
    background: #e8f0fe;
    outline: 2px dashed #4a90d9;
}

/* Delete folder dialog */
.links-delete-dialog-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.4);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.links-delete-dialog {
    background: #fff;
    border-radius: 6px;
    padding: 24px;
    min-width: 340px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.links-delete-dialog h3 {
    margin: 0 0 12px;
    font-size: 15px;
}

.links-delete-dialog p {
    font-size: 13px;
    color: #555;
    margin: 0 0 18px;
}

.links-delete-dialog-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}

.links-delete-dialog-actions button {
    padding: 6px 14px;
    border-radius: 4px;
    border: 1px solid #ccc;
    cursor: pointer;
    font-size: 13px;
}

.links-delete-dialog-actions .btn-danger {
    background: #c0392b;
    color: #fff;
    border-color: #c0392b;
}

.links-delete-dialog-actions .btn-danger:hover {
    background: #a93226;
}
