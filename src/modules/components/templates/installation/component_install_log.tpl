{if count($log_messages) > 0}
    <div class="install-log">
        {foreach from=$log_messages item=message}
            {$message}
            <br/>
        {/foreach}
    </div>
{/if}
