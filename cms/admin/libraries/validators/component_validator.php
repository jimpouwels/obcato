<?php

	
	defined('_ACCESS') or die;
	
	include_once CMS_ROOT . "core/exceptions/component_properties_exception.php";
	
	class ComponentValidator {
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
	
		/*
			Checks if the given component type is valid.
			
			@param $component_type The type to check
		*/
		public static function validateType($component_type) {
			if (is_null($component_type) || ($component_type != 'element' && $component_type != 'module')) {
				return false;
			}
			
			return true;
		}
		
		/*
			Validates the given properties for an element.
			
			@param $properties The properties to validate
		*/
		public static function validateElementProperties($properties) {
			if (!is_null($properties) && count($properties) > 0) {
				if (!array_key_exists('class', $properties) || is_null($properties['class'])) {
					throw new ComponentPropertiesException("Element heeft een verwijzing naar een class nodig");
				}
				if (!array_key_exists('class_location', $properties) || is_null($properties['class_location'])) {
					throw new ComponentPropertiesException("Element heeft een locatie van de class nodig");
				}
				if (!array_key_exists('edit_presentation', $properties) || is_null($properties['edit_presentation'])) {
					throw new ComponentPropertiesException("Element heeft een verwijzing naar een edit template nodig");
				}
				if (!array_key_exists('scope', $properties) || is_null($properties['scope'])) {
					throw new ComponentPropertiesException("Element heeft een scope nodig");
				}
			} else {
				throw new ComponentPropertiesException("Geen properties gevonden");
			}
		}
		
		/*
			Validates the given properties for a module.
			
			@param $properties The properties to validate
		*/
		public static function validateModuleProperties($properties) {
			if (!is_null($properties) && count($properties) > 0) {
				
			} else {
				throw new ComponentPropertiesException("Geen properties gevonden");
			}
		}
	
	}
	
?>