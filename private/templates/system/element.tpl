{if isset($close_previous_separator)}
  {$close_previous_separator}
{/if}
{if $include_in_table_of_contents}
 <a class="anchor" id="{$toc_reference}"></a>
{/if}
{$element_html}