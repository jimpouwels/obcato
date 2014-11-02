<?php

	
	defined('_ACCESS') or die;
	
	abstract class HttpRequestHandler {
	
		public function handle() {
			if ($_SERVER["REQUEST_METHOD"] === "POST") {
				$this->handlePost();
			} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
				$this->handleGet();
			}
		}
	
		abstract function handleGet();
		
		abstract function handlePost();

	}