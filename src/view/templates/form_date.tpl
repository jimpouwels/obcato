<input class="admin_field datepicker {$classes}" type="text" name="{$field_name}" id="{$field_name}"
       value="{$field_value}" />
<span class="date_format_hint">dd-mm-yyyy</span>
<script type="text/javascript">$(document).ready(function () {literal}{
        {/literal}$('#{$field_name}').each(function () {literal}{
            {/literal}$(this).datepicker({literal}{{/literal}dateFormat: 'dd-mm-yy'{literal}}{/literal}, $.datepicker.regional['nl']);{literal}}{/literal});{literal}}{/literal});</script>