<?php

	// No direct access
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "core/data/component.php";
	include_once CMS_ROOT . "core/data/module.php";
	include_once CMS_ROOT . "core/data/element_type.php";
	include_once CMS_ROOT . "libraries/validators/component_validator.php";
	include_once CMS_ROOT . "libraries/utilities/string_utility.php";
	include_once CMS_ROOT . "libraries/utilities/file_utility.php";
	include_once CMS_ROOT . "core/exceptions/component_properties_exception.php";
	include_once CMS_ROOT . "core/exceptions/component_descriptor_exception.php";
	include_once CMS_ROOT . "database/dao/scope_dao.php";
	include_once CMS_ROOT . "database/dao/element_dao.php";
	
	class ComponentService {
	
		/*
			This Service is a singleton, no constructur but
			a getInstance() method instead.
		*/
		private static $instance;
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
		
		/*
			Creates (if not exists) and returns an instance.
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new ComponentService();
			}
			return self::$instance;
		}
		
		/*
			Analyses the provided ZIP file and creates and installs a component object.
			
			@param $zip_location The location of the ZIP file
		*/
		public function installComponentFromZip($zip_location) {
			if ($this->validUploadedFile($zip_location)) {
				$zip_resource = zip_open($zip_location);
				$component = null;
				$descriptor_xml = trim($this->getDescriptorXml($zip_resource));
				if (!is_null($descriptor_xml) && $descriptor_xml != "") {
					$descriptor_document = new DOMDocument();
					try {
						$descriptor_document->loadXml($descriptor_xml);
						$component = $this->processDescriptor($descriptor_document);
					} catch (ComponentDescriptorException $e) {
						throw new ComponentException($e->getMessage());
					} 
				} else {
					throw new ComponentException("Descriptor is leeg");
				}
				
				// install the component
				if ($component->getType() == 'module') {
					$this->installModule($component);
					$this->installComponentFiles($zip_location, 'modules/' . $component->getIdentifier(), $component);
					$this->installStatics($zip_location, 'modules/' . $component->getIdentifier());
				} else if ($component->getType() == 'element') {
					$this->installElement($component);
					$this->installComponentFiles($zip_location, 'elements/' . $component->getIdentifier(), $component);
					$this->installStatics($zip_location, 'elements/' . $component->getIdentifier());
				}
				
				$install_script_sql = $this->getInstallScriptContent($zip_resource, $component);
				$this->executeMultiSqlScript($install_script_sql);
				
				zip_close($zip_resource);
			} else {
				throw new ComponentException("Er is geen valide component ZIP gevonden");
			}
		}
		
		/*
			Uninstalls the given element.
			
			@param $element_type_id The element to uninstall
		*/
		public function uninstallElement($element_type_id) {
			$element_dao = ElementDao::getInstance();
			$element_type = $element_dao->getElementType($element_type_id);
			
			// run destroy script
			$destroy_script = FileUtility::loadFileContents(COMPONENT_DIR . "/elements/" . $element_type->getIdentifier() . "/" . 
										  $element_type->getDestroyScript());
			$this->executeMultiSqlScript($destroy_script);
			
			// delete all component files
			FileUtility::recursiveDelete(COMPONENT_DIR . "/elements/" . $element_type->getIdentifier(), true);
			
			// delete all statics
			FileUtility::recursiveDelete(STATIC_FILES_DIR . "/elements/" . $element_type->getIdentifier(), true);
						
			// delete the element type
			$element_dao = ElementDao::getInstance();
			$element_dao->deleteElementType($element_type->getId());
			
		}
		
		/*
			Uninstalls the given module.
			
			@param $module_id The module to uninstall
		*/
		public function uninstallModule($module_id) {
			
		}
			
		private function validUploadedFile($zip_location) {
			$valid = false;
			if (isset($_FILES['component_file']) && is_uploaded_file($_FILES['component_file']['tmp_name'])) {
				$filename = $_FILES['component_file']['name'];
				if (StringUtility::endsWith($filename, '.zip')) {
					$zip = new ZipArchive;
					if ($zip->open($zip_location) === TRUE) {
						$valid = true;
					}
				}
			}
			return $valid;
		}
		
		private function installComponentFiles($zip_location, $target, $component) {
			$target = COMPONENT_DIR . "/" . $target;
			if (!is_dir($target)) {
				mkdir($target, 0777, true);
			}
			$zip = new ZipArchive();
			$zip->open($zip_location);
			
			$files_to_copy = array();
			for($i = 0; $i < $zip->numFiles; $i++) {
				$entry = $zip->getNameIndex($i);
				if (StringUtility::startsWith($entry, "files") || $entry == $component->getDestroyScript()) {
					array_push($files_to_copy, $entry);
				}
			}
			$zip->extractTo($target, $files_to_copy);
			$zip->close();
			
			// move files from /files directory one up
			$source = "$target/files";
			$destination = "$target";
			
			FileUtility::moveDirectoryContents($source, $destination, true);
		}
		
		private function executeMultiSqlScript($sql) {
			$mysql_database = MysqlConnector::getInstance();
			$queries = preg_split("/;+(?=([^'|^\\\']*['|\\\'][^'|^\\\']*['|\\\'])*[^'|^\\\']*[^'|^\\\']$)/", $sql);
			foreach ($queries as $query){
				if (strlen(trim($query)) > 0) {
					$mysql_database->executeQuery($query);
				}
			} 
		}
		
		private function installStatics($zip_location, $target) {
			$target = STATIC_FILES_DIR . "/" . $target;
			if (!is_dir($target)) {
				mkdir($target, 0777, true);
			}
			$zip = new ZipArchive();
			$zip->open($zip_location);
			
			$files_to_copy = array();
			for($i = 0; $i < $zip->numFiles; $i++) {
				$entry = $zip->getNameIndex($i);
				if (StringUtility::startsWith($entry, "static")) {
					array_push($files_to_copy, $entry);
				}
			}
			
			$zip->extractTo($target, $files_to_copy);
			$zip->close();
			
			// move files from /static directory one up
			$source = "$target/static";
			$destination = "$target";
			
			FileUtility::moveDirectoryContents($source, $destination, true);
		}
		
		private function installModule($component) {			
		}
		
		private function installElement($component) {
			$element_type = new ElementType();
			$element_type->setIdentifier($component->getIdentifier());
			$element_type->setName($component->getName());
			$element_type->setDestroyScript($component->getDestroyScript());
			
			// get and set properties
			$properties = $component->getProperties();
			$element_type->setEditPresentation($properties['edit_presentation']);
			$element_type->setClassName($properties['class']);
			$element_type->setDomainObject($properties['class_location']);
			$element_type->setIconUrl($properties['icon']);
			
			// handle scope
			$element_scope = $properties['scope'];
			$scope_dao = ScopeDao::getInstance();
			$existing_scope = $scope_dao->getScopeByName($element_scope);
			if (is_null($existing_scope)) {
				$new_scope = new Scope();
				$new_scope->setName($element_scope);
				$scope_id = $scope_dao->persistScope($new_scope);
				$element_type->setScopeId($scope_id);
			} else {
				$element_type->setScopeId($existing_scope->getId());
			}
			
			$element_type->setSystemDefault(false);
			
			$element_dao = ElementDao::getInstance();
			$existing_element_type = $element_dao->getElementTypeByIdentifier($element_type->getIdentifier());
			
			if (is_null($existing_element_type)) {
				$element_dao->persistElementType($element_type);
			} else {
				// copy the ID so the element_type will be recognized
				$element_type->setId($existing_element_type->getId());
				$element_dao->updateElementType($element_type);
			}
		}
			
		private function getDescriptorXml($zip_resource) {
			while ($entry = zip_read($zip_resource)) {
				if (zip_entry_name($entry) == 'descriptor.xml') {
					return zip_entry_read($entry, zip_entry_filesize($entry));
				}
			}
		}
		
		private function getInstallScriptContent($zip_resource, $component) {
			while ($entry = zip_read($zip_resource)) {
				if (zip_entry_name($entry) == $component->getInstallScript()) {
					return zip_entry_read($entry, zip_entry_filesize($entry));
				}
			}
		}
			
		private function processDescriptor($descriptor_document) {
			$type = $this->getType($descriptor_document);
			if (!ComponentValidator::validateType($type)) {
				throw new ComponentDescriptorException("Ongeldig component type");
			}
			$identifier = $this->getIdentifier($descriptor_document);
			if (is_null($identifier) || $identifier == "") {
				throw new ComponentDescriptorException("Component heeft geen identifier");
			}
			$name = $this->getName($descriptor_document);
			if (is_null($name) || $name == "") {
				throw new ComponentDescriptorException("Component heeft geen naam");
			}
			$install_script = $this->getInstallScript($descriptor_document);
			$destroy_script = $this->getDestroyScript($descriptor_document);
			$dependencies = $this->getDependencies($descriptor_document);
			$properties = $this->getProperties($descriptor_document);
			
			try {
				if ($type == 'element') {
					ComponentValidator::validateElementProperties($properties);
				} else if ($type == 'module') {
					ComponentValidator::validateModuleProperties($properties);
				}
			} catch (ComponentPropertiesException $e) {
				throw new ComponentDescriptorException($e->getMessage());
			}
			
			$component = new Component();
			$component->setType($type);
			$component->setIdentifier($identifier);
			$component->setName($name);
			$component->setInstallScript($install_script);
			$component->setDestroyScript($destroy_script);
			$component->setProperties($properties);
			$component->setDependencies($dependencies);
			
			return $component;			
		}
		
		private function getIdentifier($descriptor_document) {
			$elements = $descriptor_document->getElementsByTagName('identifier');
			if (is_null($elements) || count($elements) == 0) {
				throw new ComponentDescriptorException("<identifier> tag is missing");
			}
			return $elements->item(0)->nodeValue;
		}
		
		private function getType($descriptor_document) {
			$elements = $descriptor_document->getElementsByTagName('type');
			if (is_null($elements) || count($elements) == 0) {
				throw new ComponentDescriptorException("<type> tag is missing");
			}
			return $elements->item(0)->nodeValue;
		}
				
		private function getName($descriptor_document) {
			$elements = $descriptor_document->getElementsByTagName('name');
			if (is_null($elements) || count($elements) == 0) {
				throw new ComponentDescriptorException("<name> tag is missing");
			}
			return $elements->item(0)->nodeValue;
		}
		
		private function getInstallScript($descriptor_document) {
			$elements = $descriptor_document->getElementsByTagName('install_script');
			if (is_null($elements) || count($elements) == 0) {
				throw new ComponentDescriptorException("<install_script> tag is missing");
			}
			return $elements->item(0)->nodeValue;
		}
		
		private function getDestroyScript($descriptor_document) {
			$elements = $descriptor_document->getElementsByTagName('destroy_script');
			if (is_null($elements) || count($elements) == 0) {
				throw new ComponentDescriptorException("<destroy_script> tag is missing");
			}
			return $elements->item(0)->nodeValue;
		}
		
		private function getProperties($descriptor_document) {
			$properties = array('class' => NULL, 'class_location' => NULL, 'edit_presentation' => NULL, 'icon' => NULL, 'scope' => NULL);
		
			$elements = $descriptor_document->getElementsByTagName('properties');
			if (is_null($elements) || count($elements) == 0) {
				throw new ComponentDescriptorException("<properties> tag is missing");
			}
			$property_elements = $elements->item(0)->getElementsByTagName('property');
			if (is_null($property_elements) || count($property_elements) == 0) {
				throw new ComponentDescriptorException("Component descriptor does not contain properties");
			}
			
			foreach ($property_elements as $property_element) {
				$attribute = $property_element->getAttribute('name');
				if (!is_null($attribute)) {
					$properties[$attribute] = $property_element->nodeValue;
				}
			}
			return $properties;
		}
		
		private function getDependencies($descriptor_document) {
			$dependencies = array();
		
			$elements = $descriptor_document->getElementsByTagName('dependencies');
			if (is_null($elements) || count($elements) == 0) {
				throw new ComponentDescriptorException("<dependencies> tag is missing");
			}
			$property_elements = $elements->item(0)->getElementsByTagName('dependency');
			if (!is_null($dependency_elements) && count($dependency_elements) > 0) {
				foreach ($dependency_elements as $dependency_element) {
					array_push($dependencies, $dependency_element->nodeValue());
				}
			}	
			
			return $dependencies;
		}	
	}
	
?>