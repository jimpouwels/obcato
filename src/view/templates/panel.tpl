<div class="panel {$class}">
    {if $title_resource_identifier}
    <div class="panel-title">
        <p>
            {if isset($text_resources[$title_resource_identifier])}
                {$text_resources[$title_resource_identifier]}
            {else}
                {$title_resource_identifier}
            {/if}
        </p>
    </div>
    {/if}
    <div class="panel-content">
        {$content}
    </div>
</div>
