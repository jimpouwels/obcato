<?php

namespace Obcato\Core;

use Obcato\Core\admin\authentication\Authenticator;
use Obcato\Core\admin\Backend;
use Obcato\Core\admin\database\dao\ImageDaoMysql;
use const Obcato\Core\admin\UPLOAD_DIR;

define("_ACCESS", "GRANTED");

require_once "../bootstrap.php";

require_once CMS_ROOT . "/view/views/Panel.php";
require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once CMS_ROOT . "/Backend.php";

$backend = new Backend();

require_once CMS_ROOT . "/database/dao/ImageDaoMysql.php";

if (isset($_GET['image']) && $_GET['image'] != '') {
    $image_dao = ImageDaoMysql::getInstance();
    $image = $image_dao->getImage($_GET['image']);

    if (!$image->isPublished())
        Authenticator::isAuthenticated();

    $file_name = NULL;
    if (isset($_GET['thumb']) && $_GET['thumb'] == 'true') {
        $file_name = $image->getThumbFileName();
    } else {
        $file_name = $image->getFilename();
    }

    $path = UPLOAD_DIR . "/" . $file_name;
    $splits = explode('.', $file_name);
    $extension = $splits[count($splits) - 1];

    if ($extension == "jpg") {
        header("Content-Type: image/jpeg");
    } else if ($extension == "gif") {
        header("Content-Type: image/gif");
    } else if ($extension == "png") {
        header("Content-Type: img/png");
    } else {
        header("Content-Type: image/$extension");
    }

    readfile($path);
} else if (isset($_GET['download']) && $_GET['download'] != '') {
    // TODO
}