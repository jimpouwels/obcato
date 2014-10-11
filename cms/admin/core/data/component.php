<?php

	
	defined('_ACCESS') or die;

	class Component {
	
		private $myType;
		private $myIdentifier;
		private $myName;
		private $myInstallScript;
		private $myDestroyScript;
		private $myDependencies;
		private $myProperties;
		
		public function __construct() {
			$myDependencies = array();
			$myProperties = array();
		}
		
		public function getType() {
			return $this->myType;
		}
		
		public function setType($type) {
			$this->myType = $type;
		}
		
		public function getName() {
			return $this->myName;
		}
		
		public function setName($name) {
			$this->myName = $name;
		}
		
		public function getIdentifier() {
			return $this->myIdentifier;
		}
		
		public function setIdentifier($identifier) {
			$this->myIdentifier = $identifier;
		}
		
		public function getInstallScript() {
			return $this->myInstallScript;
		}
		
		public function setInstallScript($install_script) {
			$this->myInstallScript = $install_script;
		}
		
		public function getDestroyScript() {
			return $this->myDestroyScript;
		}
		
		public function setDestroyScript($destroy_script) {
			$this->myDestroyScript = $destroy_script;
		}
		
		public function addDependency($dependency) {
			array_push($dependency);
		}
		
		public function setDependencies($dependencies) {
			$this->myDependencies = $dependencies;
		}
		
		public function addProperty($name, $value) {
			$myProperties[$name] = $value;
		}
		
		public function setProperties($properties) {
			$this->myProperties = $properties;
		}
		
		public function getProperties() {
			return $this->myProperties;
		}
	
	}
	
?>