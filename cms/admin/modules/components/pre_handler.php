<?php
	// No direct access
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "libraries/system/notifications.php";

	// handle post requests
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'install_component':
					installComponent();
					break;
				case 'uninstall_element':
					uninstallElement();
					break;
			}
		}
	}
	
	// ============ UNINSTALL ELEMENT ===============================================================================
	function uninstallElement() {
		include_once CMS_ROOT . "services/component_service.php";
		$element_id = $_POST['element_id_to_uninstall'];
		$component_service = ComponentService::getInstance();
		$component_service->uninstallElement($element_id);
		Notifications::setSuccessMessage("Element succesvol verwijderd");
		header('Location: /admin/index.php');
		exit();
	}	
	
	// ============ INSTALL COMPONENT ===============================================================================
	
	function installComponent() {	
		include_once CMS_ROOT . "services/component_service.php";
		include_once CMS_ROOT . "core/exceptions/component_exception.php";
	
		global $process_log;
		global $install_errors;
		$install_errors = array();
		$process_log = array();
		
		$zip_location = $_FILES['component_file']['tmp_name'];

		try {
			$component_service = ComponentService::getInstance();
			$component = $component_service->installComponentFromZip($zip_location);
			Notifications::setSuccessMessage("Component succesvol geinstalleerd");
		} catch (ComponentException $e) {
			array_push($install_errors, $e->getMessage());
			Notifications::setFailedMessage("Component niet geinstalleerd");
		}
	}
	
	function getZipResource($zip_loc) {
		return zip_open($zip_loc);
	}
	
?>