.fimg-module-root .content_left_column {
    width: 420px;
}

.fimg-module-root .fimg-content-right-column {
    margin-left: 440px;
}

.fimg-tree {
    padding: 6px 0;
}

.fimg-folder {
    margin-bottom: 0;
}

/* Padding specific to fimg tree header/items */
.fimg-folder-header {
    padding: 0 8px;
}

.fimg-image-item {
    display: flex;
    align-items: center;
    padding: 0 8px;
    border-radius: 4px;
    cursor: grab;
    font-size: 13px;
    overflow: hidden;
}

.fimg-image-item.fimg-dragging {
    opacity: 0.3;
}

.fimg-image-item:hover {
    background: #f5f5f5;
}

.fimg-image-item.selected {
    background: #e8f0fe;
    border-left: 3px solid var(--color-primary, #2a7ab8);
}

.fimg-image-item.drag-over {
    background: #e8f0fe;
    outline: 2px dashed #4a90d9;
}

.fimg-image-icon {
    flex-shrink: 0;
    font-size: 14px;
    line-height: 1;
    align-self: center;
    margin-right: 4px;
}

.fimg-image-title {
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

.fimg-image-title:hover {
    text-decoration: underline;
}

.fimg-image-item.selected .fimg-image-title {
    color: var(--color-primary, #2a7ab8);
    font-weight: 600;
}

.fimg-preview {
    margin-top: 12px;
    padding: 8px;
    background: #f5f5f5;
    border-radius: 4px;
    display: inline-block;
}
