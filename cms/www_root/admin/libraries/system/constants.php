<?php
	
	// No direct access
	defined('_ACCESS') or die;
	
	require_once "core/data/settings.php";
	
	// EDITOR FORM CONSTANTS
	define("ADD_ELEMENT_FORM_ID", 'add_element_type_id');
	define("EDIT_ELEMENT_HOLDER_ID", 'element_holder_id');
	define("ACTION_FORM_ID", 'action');
	define("DELETE_ELEMENT_FORM_ID", 'delete_element');
	define('ELEMENT_HOLDER_FORM_ID', 'element_holder_form_id');
	define('ELEMENT_ORDER_ID', 'element_order'); 
	
	// ELEMENT HOLDER TYPES
	define("ELEMENT_HOLDER_PAGE", 'ELEMENT_HOLDER_PAGE');
	define("ELEMENT_HOLDER_ARTICLE", 'ELEMENT_HOLDER_ARTICLE');
	define("ELEMENT_HOLDER_BLOCK", 'ELEMENT_HOLDER_BLOCK');
	
	// DEFINE SYSTEM VFERSION
	define("SYSTEM_VERSION", "0.0.5");
	
	// DEFINE TIME OUT
	define("SESSION_TIMEOUT", 1800);
	
	$website_settings = Settings::find();
	
	// DEFINE DB-VERSION
	define("DB_VERSION", $website_settings->getDatabaseVersion());
	define("STATIC_FILES_DIR", $website_settings->getStaticDir());
	define("ROOT_DIR", $website_settings->getRootDir());
	define("COMPONENT_DIR", $website_settings->getComponentDir());
	define("TEMPLATE_ENGINE_DIR", $website_settings->getBackendTemplateDir());
	define("STATIC_FILES_URL", "/admin/static.php?static=");
	define("DEFAULT_ELEMENT_ICON_URL", STATIC_FILES_URL . "/default/img/element_icons/");
	
?>