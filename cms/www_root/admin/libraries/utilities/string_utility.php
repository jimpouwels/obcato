<?php

	// No direct access
	defined('_ACCESS') or die;
	
	class StringUtility {
		
		/*
			Private constructor.
		*/
		private function __construct() {
		}
	
		/*
			Hashes the given string.
			
			@param $string_value The string value to hash
		*/
		public static function hashStringValue($string_value) {
			return md5($string_value);
		}
		
		/*
			Unescapes XML characters.
			
			@param $string_value The string value to unescapes
		*/
		public static function unescapeXml($string_value) {
			$string_value = str_replace("&lt;", "<", $string_value);
			$string_value = str_replace("&gt;", ">", $string_value);
			$string_value = str_replace("&amp;", "&", $string_value);
			$string_value = str_replace("&quot;", '"', $string_value);
			$string_value = str_replace("&ndash;", "–", $string_value);
			$string_value = str_replace("&mdash;", "—", $string_value);
			$string_value = str_replace("&copy;", "©", $string_value);
			$string_value = str_replace("&iexcl;", "¡", $string_value);
			$string_value = str_replace("&iquest;", "¿", $string_value);
			$string_value = str_replace("&ldquo;", "“", $string_value);
			$string_value = str_replace("&rdquo;", "”", $string_value);
			$string_value = stripslashes($string_value);
			return $string_value;
		}
		
		/*
			Escapes XML characters.
			
			@param $string_value The string value to unescapes
		*/
		public static function escapeXml($string_value) {
			$string_value = str_replace("<", "&lt;", $string_value);
			$string_value = str_replace(">", "&gt;", $string_value);
			$string_value = str_replace("&", "&amp;", $string_value);
			$string_value = str_replace("\"", "&quot;", $string_value);
			$string_value = str_replace("–", "&ndash;", $string_value);
			$string_value = str_replace("—", "&mdash;", $string_value);
			$string_value = str_replace("©", "&copy;", $string_value);
			$string_value = str_replace("¡", "&iexcl;", $string_value);
			$string_value = str_replace("¿", "&iquest;", $string_value);
			$string_value = str_replace("'", "&#39;", $string_value);
			$string_value = str_replace("“", "&ldquo;", $string_value);
			$string_value = str_replace("”", "&rdquo;", $string_value);
			return $string_value;
		}
		
		/*
			Checks if the given string ends with the second given string.
			
			@param $full_string The string to check
			@param $end The end string
		*/
		public static function endsWith($full_string, $end) {
			return substr($full_string, strlen($full_string) - strlen($end)) === $end;
		}
		
		/*
			Checks if the given string starts with the second given string.
			
			@param $full_string The string to check
			@param $end The end string
		*/
		public static function startsWith($full_string, $start) {
			return substr($full_string, 0, strlen($start)) === $start;
		}
		
	}
	
?>