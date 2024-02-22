<div class="panel {$class}">
    <div class="panel-title">
        <p>
            {if isset($text_resources[$title_resource_identifier])}
                {$text_resources[$title_resource_identifier]}
            {else}
                {$title_resource_identifier}
            {/if}
        </p>
    </div>
    <div class="panel-content">
        {$content}
    </div>
</div>
