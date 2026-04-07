$(document).ready(function () {
    function submitTemplateForm(action, form_id) {
        let $actionField = $('#' + form_id + ' #action');
        if ($actionField.length == 0) {
            alert("{$text_resources.templates_alert_action_error}");
        } else {
            $actionField.attr('value', action);
            let $template_form = $('#' + form_id);
            if ($template_form.length == 0) {
                alert("{$text_resources.templates_alert_save_error}");
            } else {
                $template_form.submit();
            }
        }
    }

    $('#update_template').click(function () {
        submitTemplateForm('update_template', 'template_editor_form');
    });

    $('#add_template').click(function () {
        submitTemplateForm('add_template', 'template_add_form');
    });

    $('#delete_template').click(function () {
        let $checked = false;
        $('input:checkbox').each(function () {
            if ($(this).attr('checked')) {
                $checked = true;
            }
        });
        if (!$checked) {
            alert("{$text_resources.templates_alert_no_templates_selected}");
        } else {
            let confirmed = confirm("{$text_resources.templates_confirm_delete_templates}");
            if (confirmed) {
                submitTemplateForm('delete_templates', 'template_editor_form');
            }
        }
    });

    $('#add_template_file').click(function () {
        submitTemplateForm('add_template_file', 'template_add_form');
    });

    $('#update_template_file').click(function () {
        submitTemplateForm('update_template_file', 'template_file_form');
    });

    $('#reload_template_file').click(function () {
        submitTemplateForm('reload_template_file', 'template_file_form');
    });

    $('#delete_template_file').click(function () {
        let confirmed = confirm("{$text_resources.templates_confirm_delete_template_file}");
        if (confirmed) {
            submitTemplateForm('delete_template_file', 'template_file_form');
        }
    });

});