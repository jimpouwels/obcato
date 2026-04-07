<div class="element-insert-button" data-insert-position="{$position}">
    <button type="button" class="insert-btn" onclick="showElementSelector({$position}); return false;" title="Element invoegen">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M8 3V13M3 8H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </button>
    <div class="insert-indicator">
        <div class="insert-line"></div>
        <div class="insert-arrow">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                <path d="M6 2L6 10M6 10L3 7M6 10L9 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </div>
</div>
