<form id="position_form" method="post" action="{$backend_base_url}&position={$id}">
    <input type="hidden" value="" name="action" id="action" />
    <input type="hidden" value="{$id}" name="position_id" id="position_id" />

    <ul class="admin_form">
        <li>{$name_field}</li>
        <li>{$explanation_field}</li>
    </ul>
</form>
