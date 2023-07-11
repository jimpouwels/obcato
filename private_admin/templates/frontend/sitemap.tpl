<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {foreach from=$pages item=page}
        <url>
            <loc>{$page.url}</loc>
            <lastmod>{$page.last_modified}</lastmod>
        </url>
    {/foreach}
    {foreach from=$articles item=article}
        <url>
            <loc>{$article.url}</loc>
            <lastmod>{$article.last_modified}</lastmod>
        </url>
    {/foreach}
</urlset>