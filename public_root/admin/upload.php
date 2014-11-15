<?php
    
    define("_ACCESS", "GRANTED");
    define("CMS_ROOT", '');

    require_once CMS_ROOT . "authenticator.php";
    require_once CMS_ROOT . "database_config.php";
    require_once CMS_ROOT . "constants.php";
    require_once CMS_ROOT . "backend.php";
    
    $backend = new Backend("site_administrator");
    
    include_once CMS_ROOT . "database/dao/image_dao.php";
    
    if (isset($_GET['image']) && $_GET['image'] != '') {
        $image_dao = ImageDao::getInstance();
        $image = $image_dao->getImage($_GET['image']);
        
        $render_image = false;
        if ($image->isPublished())
            $render_image = true;
        else
            Authenticator::isAuthenticated();
        
        $file_name = NULL;
        if (isset($_GET['thumb']) && $_GET['thumb'] == 'true') {
            $file_name = $image->getThumbFileName();
        } else {
            $file_name = $image->getFileName();
        }
        
        $path = UPLOAD_DIR . "/" . $file_name;
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