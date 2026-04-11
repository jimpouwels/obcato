<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl_NL" lang="nl_NL">
<head>
    <title>Obcato</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    {if isset($main_css)}
        <style>
            {$main_css}
        </style>
    {/if}

    <link rel="stylesheet" href="/admin?file=/default/css/jquery-ui-1.8.23.custom.css?v=14" type="text/css" />

    <script type="text/javascript" src="/admin?file=/default/js/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="/admin?file=/default/js/jquery-ui-1.8.23.custom.min.js"></script>
    <script type="text/javascript" src="/admin?file=/default/js/functions.js?v=23"></script>
    <script type="text/javascript" src="/admin?file=/default/js/rich_text_editor.js?v=10"></script>

    {if isset($module_styles)}
        {foreach from=$module_styles item=style}
            <style>
                {$style}
            </style>
        {/foreach}
    {/if}
    
    {if isset($module_scripts)}
        {foreach from=$module_scripts item=script}
            <script type="text/javascript">
                {$script}
            </script>
        {/foreach}
    {/if}
</head>
<body>
<div id="header-wrapper">
    <div id="top">
        <p class="title">Obcato <span class="version">v{$system_version}</span></p>
        <div class="header-right">
            {$current_user_indicator}
        </div>
    </div>
</div>

<div id="sidebar-navigation">
    {$navigation_menu}
</div>

{$notification_bar}

<div id="main-content">
    {if isset($actions_menu)}
        {$actions_menu}
    {/if}
    
    {if isset($system_logs)}
        {$system_logs}
    {/if}
    <div id="content-pane-wrapper">
        {$tab_menu}
        {$content_pane}
    </div>
</div>

{include file="confirm_dialog.tpl"}
{include file="link_editor_dialog.tpl"}

</body>
</html>
