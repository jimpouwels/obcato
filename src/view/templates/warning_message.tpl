<div class="information-block">
    <div class="info-icon">
        <img src="/admin/index.php?file=/default/img/default_icons/warning.png" alt="notification" />
    </div>
    <div class="info-message">
        <p>
            <em>
                {if isset($text_resources[$message_resource_identifier])}
                    {$text_resources[$message_resource_identifier]}
                {else}
                    {$message_resource_identifier}
                {/if}
            </em>
        </p>
    </div>
</div>