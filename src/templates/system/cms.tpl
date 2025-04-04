<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl_NL" lang="nl_NL">
<head>
    <title>Obcato</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="/admin/static/css/styles.css" type="text/css" />
    <link rel="stylesheet" href="/admin/static.php?file=/default/css/styles.css" type="text/css" />
    <link rel="stylesheet" href="/admin/static.php?file=/default/css/jquery-ui-1.8.23.custom.css" type="text/css" />

    <script type="text/javascript" src="/admin/static.php?file=/default/js/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="/admin/static.php?file=/default/js/jquery-ui-1.8.23.custom.min.js"></script>
    <script type="text/javascript" src="/admin/static.php?file=/default/js/functions.js?v=7"></script>

    {if isset($module_head_includes)}
        {$module_head_includes}
    {/if}
</head>
<body>
<div id="header-wrapper">
    <div id="top">
        <p class="title">Obcato</p>
        <p class="version">SYS-version: {$system_version} / DB-version: {$db_version}</p>
    </div>

    <div id="navigation_wrapper">
        {$navigation_menu}
        {$current_user_indicator}
    </div>

    {$notification_bar}

    {if isset($actions_menu)}
        {$actions_menu}
    {/if}
</div>
<div id="content-wrapper">
    {if isset($system_logs)}
        {$system_logs}
    {/if}
    <div class="module_title_wrapper">
        <h1>
            {if isset($page_title)}
                {$page_title}
            {/if}
        </h1>
    </div>
    <div id="content-pane-wrapper">
        {$tab_menu}
        {$content_pane}
    </div>
</div>
</body>
</html>
