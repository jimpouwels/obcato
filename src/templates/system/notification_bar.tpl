<div id="notification-bar">
    <div id="notification-slider">
        {if isset($success) && isset($message)}
            {if {$success}}
                {assign var='class' value='success'}
            {else}
                {assign var='class' value='fail'}
            {/if}
            <div class="notification-holder notification-holder-{$class}">
                <p class="{$class}">{$message}</p>
            </div>
        {/if}
    </div>
</div>

