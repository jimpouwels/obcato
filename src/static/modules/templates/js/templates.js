$(document).ready(function () {
    function submitTemplateForm(action, form_id) {
        var $actionField = $('#action');
        if ($actionField.length == 0) {
            alert('Fout: Kan de actie niet bepalen');
        } else {
            $actionField.attr('value', action);
            var $template_form = $('#' + form_id);
            if ($template_form.length == 0) {
                alert('Fout: Kan template niet opslaan');
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
        var $checked = false;
        $('input:checkbox').each(function () {
            if ($(this).attr('checked')) {
                $checked = true;
            }
        });
        if (!$checked) {
            alert('U heeft geen templates geselecteerd');
        } else {
            var confirmed = confirm('Weet u zeker dat u de geselecteerde templates wilt verwijderen?');
            if (confirmed) {
                submitTemplateForm('delete_templates', 'template_editor_form');
            }
        }
    });

    $('#add_template_file').click(function () {
        submitTemplateForm('add_template_file', 'template_file_form');
    });

    $('#update_template_file').click(function () {
        submitTemplateForm('update_template_file', 'template_file_form');
    });

    $('#reload_template_file').click(function () {
        submitTemplateForm('reload_template_file', 'template_file_form');
    });

    $('#delete_template_file').click(function () {
        var confirmed = confirm('Weet u zeker dat u de geselecteerde template bestand wilt verwijderen?');
        if (confirmed) {
            submitTemplateForm('delete_template_file', 'template_file_form');
        }
    });

});