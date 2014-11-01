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