{if isset($elements)}
    <div id="element_container" class="draggable_items">
        {foreach from=$elements item=element}
            {$element}
        {/foreach}
    </div>
{else}
    {$message}
{/if}
