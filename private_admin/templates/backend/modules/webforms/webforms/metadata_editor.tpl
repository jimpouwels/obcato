<ul class="admin_form">
	<li>{$title_field}</li>
	<li>{$template_picker}</li>
	<li>{$include_captcha_field}</li>
	<li class="{$captcha_key_field_class}{if !$include_captcha} displaynone"{/if}">{$captcha_key_field}</li>
	<li class="{$captcha_key_field_class}{if !$include_captcha} displaynone"{/if}">{$captcha_secret_field}</li>
</ul>