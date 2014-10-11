<?php
	
	
	defined('_ACCESS') or die;
	
	require_once CMS_ROOT . "core/data/settings.php";

    $website_settings = Settings::find();
	
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
	define("SYSTEM_VERSION", "1.0.0");
    define("DB_VERSION", $website_settings->getDatabaseVersion());
	
	// DEFINE TIME OUT
	define("SESSION_TIMEOUT", 1800);

    // DIRECTORIES
	define("COMPONENT_DIR", $website_settings->getComponentDir());
    define("UPLOAD_DIR", $website_settings->getUploadDir());
    define("FRONTEND_TEMPLATE_DIR", $website_settings->getFrontendTemplateDir());
    define("BACKEND_TEMPLATE_DIR", $website_settings->getBackendTemplateDir());
    define("STATIC_DIR", $website_settings->getStaticDir());

    // OTHER
    define("WEBSITE_TITLE", $website_settings->getWebsiteTitle());
?>