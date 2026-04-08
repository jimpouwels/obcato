// Auto-resize textareas in text elements (not rich text editors)
$(document).ready(function() {
    $('.text-element-textarea:not(.rich-text-content)').attr('rows', '5').on('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    }).trigger('input');
});
