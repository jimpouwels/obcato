<fieldset class="admin_fieldset modules-list-fieldset">
    <div class="fieldset-title">Modules</div>
    <ul>
        {foreach from=$modules item=module}
            <li style="list-style-image: url('{$module.icon_url}')"><a href="/admin/index.php?module={$module.id}" title="{$module.title}">{$module.title}</a></li>
        {/foreach}
    </ul>
</fieldset>
{if !is_null($current_module) || !is_null($current_element)}
    <fieldset class="admin_fieldset component-details-fieldset">
        <div class="fieldset-title">Component details</div>
        {if !is_null($current_module)}
            <ul>
                <li>Titel: {$current_module.title}</li>
            </ul>
        {/if}
    </fieldset>
{/if}