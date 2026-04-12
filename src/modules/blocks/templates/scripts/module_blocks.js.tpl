$(document).ready(function () {
    // update block button
    $('#update_element_holder').on('click', function () {
        $('#action').attr('value', 'update_element_holder');
        $('#element_holder_form_id').trigger('submit');
    });

    // delete block button
    $('#delete_element_holder').on('click', function () {
        confirmDialog("{$text_resources.blocks_confirm_delete_block}").then(function(confirmed) {
            if (confirmed) {
                $('#action').attr('value', 'delete_block');
                $('#element_holder_form_id').trigger('submit');
            }
        });
        return false;
    });

    // add block button
    $('#add_element_holder').on('click', function () {
        $('#add_block_action').attr('value', 'add_block');
        $('#add_form_hidden').trigger('submit');
    });

    // update position button
    $('#update_position').on('click', function () {
        $('#action').attr('value', 'update_position');
        $('#position_form').trigger('submit');
    });

    // add position
    $('#add_position').on('click', function () {
        $('#add_position_form').trigger('submit');
    });

    // delete position button
    $('#delete_positions').on('click', function () {
        var $checked = false;
        $('input:checkbox').each(function () {
            if ($(this).prop('checked')) {
                $checked = true;
            }
        });
        if (!$checked) {
            alert("{$text_resources.blocks_alert_no_positions_selected}");
        } else {
            confirmDialog("{$text_resources.blocks_confirm_delete_positions|escape:'javascript'}").then(function(confirmed) {
                if (confirmed) {
                    $('#position_delete_action').attr('value', 'delete_positions');
                    $('#positions_delete_form').trigger('submit');
                }
            });
        }
    });
});