{assign var='onclickhtml' value=''}
{assign var='idhtml' value=''}
{if $onclick != ""}
    {assign var='onclickhtml' value='onclick="'|cat:{$onclick}|cat:'"'}
{/if}
{if $id != ""}
    {assign var='idhtml' value='id="'|cat:{$id}|cat:'"'}
{/if}

{if isset($text_resources[$label_resource_identifier])}
    {assign var="label" value=$text_resources[$label_resource_identifier]}
{else}
    {assign var="label" value=$label_resource_identifier}
{/if}
<a href="#" class="button" title="{$label}" {$onclickhtml} {$idhtml}>{$label}</a>