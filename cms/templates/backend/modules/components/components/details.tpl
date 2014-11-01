{if !is_null($current_module) || !is_null($current_element)}
    <fieldset class="admin_fieldset component-details-fieldset">
        <div class="fieldset-title">Component details</div>
        <ul>
            {if !is_null($current_module)}
                <li>Titel: {$current_module.title}</li>
            {/if}
            {if !is_null($current_element)}
                <li>Titel: {$current_element.name}</li>
            {/if}
        </ul>
    </fieldset>
{/if}