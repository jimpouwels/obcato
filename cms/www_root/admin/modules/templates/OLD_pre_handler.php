<?php
	// No direct access
	defined('_ACCESS') or die;
		
	include_once "database/dao/template_dao.php";
	include_once "libraries/validators/form_validator.php";
	include_once "libraries/handlers/form_handler.php";
	include_once "libraries/system/notifications.php";
	
	// dispatch action
	if (isset($_POST['action'])) {
		switch ($_POST['action']) {
			case 'update_template':
				updateTemplate();
				break;
			case 'add_template':
				addTemplate();
				break;
			case 'delete_template':
				deleteTemplates();
				break;
		}
	}
	
	/*
		Updates the Template that is currently being edited.
	*/
	function updateTemplate() {
		// obtain the current template
		$template_id = '';
		if (isset($_GET['template'])) {
			$template_id = $_GET['template'];
		} else if (isset($_POST['template_id']) && $_POST['template_id'] != '') {
			$template_id = $_POST['template_id'];
		}
		$template_dao = TemplateDao::getInstance();
		$current_template = $template_dao->getTemplate($template_id);
		
		global $errors;
		if (isset($current_template) && !is_null($current_template)) {
			// get the template dir
			$template_dir = Settings::find()->getFrontendTemplateDir();
		
			$name = FormValidator::checkEmpty('name', 'Naam is verplicht');
			$file_name = FormHandler::getFieldValue('file_name', 'Bestandsnaam is verplicht');
			
			// check if the filename does not exist already
			if ($file_name != $current_template->getFileName() && !is_uploaded_file($_FILES['template_file']['tmp_name'])) {
				$check_template = $template_dao->getTemplateByFileName($file_name);
				if (!is_null($check_template)) {
					$errors['file_name_error'] = "Deze bestandsnaam bestaat al voor een ander template";
				}
			}
			// check if the uploaded file already exists
			if (is_uploaded_file($_FILES['template_file']['tmp_name'])) {
				// check if the filename does not exist yet for another template
				if (file_exists($template_dir . "/" . $_FILES['template_file']['name']) && $current_template->getName() != $_FILES['template_file']['name']) {
					$errors['template_file_error'] = "Er bestaat al een ander template met dezelfde naam";
				}
			}
			$scopeId = FormValidator::checkEmpty('scope', 'Scope is verplicht');
			if (count($errors) == 0) {
				// check uploaded template file
				$old_file_name = $template_dir . "/" . $current_template->getFileName();
				if (is_uploaded_file($_FILES['template_file']['tmp_name'])) {
					// first delete the old file
					if (file_exists($old_file_name)) {
						unlink($old_file_name);
					}
					move_uploaded_file($_FILES['template_file']['tmp_name'], $template_dir . "/" . $_FILES['template_file']['name']);
					$current_template->setFileName($_FILES['template_file']['name']);
				} else if ($file_name != '') {
					// rename the file
					if ($current_template->getFileName() != '' && file_exists($old_file_name)) {
						rename($template_dir . "/" . $current_template->getFileName(), $template_dir . "/" . $file_name);
					}
					
					$current_template->setFileName($file_name);
				}
			
				$current_template->setName($name);
				$current_template->setScopeId($scopeId);
				
				$template_dao->updateTemplate($current_template);

				Notifications::setSuccessMessage("Template succesvol opgeslagen");
			} else {
				Notifications::setFailedMessage("Template niet opgeslagen, verwerk de fouten");
			}
		}
	}		
	
	/*
		Deletes the selected templates.
	*/
	function deleteTemplates() {
		$template_dao = TemplateDao::getInstance();
		foreach ($template_dao->getTemplates() as $template) {
			if (isset($_POST['template_' . $template->getId() . '_delete'])) {
				$template_dao->deleteTemplate($template);
			}
		}
		Notifications::setSuccessMessage("Template(s) succesvol verwijderd");
	}
	
	/*
		Adds a new template.
	*/
	function addTemplate() {
		$template_dao = TemplateDao::getInstance();
		$new_template = $template_dao->createTemplate();
		Notifications::setSuccessMessage("Template succesvol aangemaakt");
		header('Location: /admin/index.php?template=' . $new_template->getId());
		exit();
	}
?>