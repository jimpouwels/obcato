<ul class="admin_form">
    <li>{$website_title}</li>
    <li>{$email_field}</li>
    <li>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    {$homepage_picker}
                </td>
                {if !is_null($current_homepage_id)}
                    <td><em><a class="link" href="/admin/index.php?module_id=3&amp;page={$current_homepage_id}">{$current_homepage_title}</a></em></td>
                {/if}
            </tr>
        </table>
    </li>
</ul>
