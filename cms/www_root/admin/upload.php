<?php
	
	define("_ACCESS", "GRANTED");
	define("FRONTEND_REQUEST", '');
	
	include_once "backend.php";
	
	$backend = new Backend("site_administrator");
	
	include_once FRONTEND_REQUEST . "dao/image_dao.php";
	
	$upload_dir = Settings::find()->getUploadDir();
	
	if (isset($_GET['image']) && $_GET['image'] != '') {
		$image_dao = ImageDao::getInstance();
		$image = $image_dao->getImage($_GET['image']);
		
		$render_image = false;
		if ($image->isPublished()) {
			$render_image = true;
		} else {
			include_once "core/data/session.php";
			$backend->isAuthenticated();
		}
		
		$file_name = NULL;
		if (isset($_GET['thumb']) && $_GET['thumb'] == 'true') {
			$file_name = $image->getThumbFileName();
		} else {
			$file_name = $image->getFileName();
		}
		
		$path = $upload_dir . "/" . $file_name;
		$splits = explode('.', $file_name);
		$extension = $splits[count($splits) - 1];
		
		if ($extension == "jpg") {
			header("Content-Type: image/jpeg");
		} else if ($extension == "gif"){
			header("Content-Type: image/gif");
		} else if ($extension == "png"){
			header("Content-Type: img/png");
		} else {
			header("Content-Type: image/$extension");
		}

		readfile($path);
	} else if (isset($_GET['download']) && $_GET['download'] != '') {
		// TODO
	}
?>