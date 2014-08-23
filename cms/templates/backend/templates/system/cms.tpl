<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl_NL" lang="nl_NL">
	<head>
		<title>Site Administrator</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
		<link rel="stylesheet" href="/admin/static/css/styles.css" type="text/css" />
        <link rel="stylesheet" href="/admin/static.php?file=/default/css/styles.css" type="text/css" />
        <link rel="stylesheet" href="/admin/static.php?file=/default/css/jquery-ui-1.8.23.custom.css" type="text/css" />
		
		<script type="text/javascript" src="/admin/static.php?file=/default/js/jquery-1.8.0.min.js"></script>
		<script type="text/javascript" src="/admin/static.php?file=/default/js/jquery-ui-1.8.23.custom.min.js"></script>
		<script type="text/javascript" src="/admin/static.php?file=/default/js/functions.js"></script>
		
		{if isset($module_head_includes)}
			{$module_head_includes}
		{/if}
	</head>
	<body>
		<div id="header-wrapper">
			<div class="header" id="top">
				<a href="index.php" class="title"><img class="header-text" alt="Site Administration" src="/admin/static/img/header_text.png" /></a>
			</div>
			<div class="version_info">
				<p>SYS-version: {$system_version} / DB-version: {$db_version}</p>
			</div>
			
			{$navigation_menu}
			
			{$notification_bar}
			
		
			{if isset($actions_menu)}
				{$actions_menu}
			{/if}
		</div>
		<div id="content-wrapper">
			<div class="module_title_wrapper">
				<h1>
					{if isset($page_title)}
						{$page_title}
					{/if}
				</h1>
			</div>
			
			{$content_pane}
		</div>
	</body>
</html>