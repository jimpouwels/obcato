// Auto-resize textareas in text elements
$(document).ready(function() {
    $('.text-element-textarea').attr('rows', '5').on('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    }).trigger('input');
});
