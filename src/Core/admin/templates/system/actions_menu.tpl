<div id="actions_menu">
    {if count($buttons) > 0}
        <ul>
            {foreach from=$buttons item=button}
                {$button}
            {/foreach}
        </ul>
    {/if}
</div>