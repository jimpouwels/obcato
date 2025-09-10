<form action="{$backend_base_url}&user={$user_id}" method="post" id="user_form" name="update_user">
    <input type="hidden" name="user_id" value="{$user_id}" />
    <input type="hidden" id="action" name="action" value="" />

    <div class="admin_form_v2">
        {$username_field}
        {$firstname_field}
        {$prefix_field}
        {$lastname_field}
        {$email_field}
        {if isset($new_password_first) && isset($new_password_second)}
            {$new_password_first}
            {$new_password_second}
        {/if}
    </div>
</form>
