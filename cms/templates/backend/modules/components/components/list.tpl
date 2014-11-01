<div class="components-list">
    <fieldset class="admin_fieldset component-list-fieldset">
        <div class="fieldset-title">Modules</div>
        <ul>
            {foreach from=$modules item=module}
                <li style="list-style-image: url('{$module.icon_url}')"><a href="/admin/index.php?module={$module.id}" title="{$module.title}">{$module.title}</a></li>
            {/foreach}
        </ul>
    </fieldset>

    <fieldset class="admin_fieldset component-list-fieldset">
        <div class="fieldset-title">Elementen</div>
        <ul>
            {foreach from=$elements item=element}
                <li style="list-style-image: url('{$element.icon_url}')"><a href="/admin/index.php?element={$element.id}" title="{$element.name}">{$element.name}</a></li>
            {/foreach}
        </ul>
    </fieldset>
</div>