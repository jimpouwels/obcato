<?php

namespace Obcato\Core\request_handlers;

use Obcato\Core\authentication\Authenticator;
use Obcato\Core\core\BlackBoard;
use Obcato\Core\core\model\Module;
use Obcato\Core\database\dao\ImageDaoMysql;
use Obcato\Core\database\dao\ModuleDao;
use Obcato\Core\database\dao\ModuleDaoMysql;
use Obcato\Core\utilities\UrlHelper;
use const Obcato\Core\UPLOAD_DIR;

class BackendRequestHandler extends HttpRequestHandler {

    private ModuleDao $moduleDao;
    private ?Module $currentModule = null;

    public function __construct() {
        $this->moduleDao = ModuleDaoMysql::getInstance();
    }

    public function handleGet(): void {
        if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/image')) {
            $this->loadImage();
        } else if (str_starts_with($_SERVER['REQUEST_URI'], '/admin/download')) {
            // TODO
        } else {
            $this->loadCurrentModule();
        }
    }

    public function handlePost(): void {
        $this->loadCurrentModule();
    }

    public function getCurrentModule(): ?Module {
        return $this->currentModule;
    }

    public function loadCurrentModule(): void {
        $moduleId = intval($this->getParam('module_id'));
        if ($moduleId) {
            $this->currentModule = $this->moduleDao->getModule($moduleId);
            BlackBoard::$MODULE_ID = $moduleId;
            $moduleTabId = intval($this->getParam('module_tab_id'));
            BlackBoard::$MODULE_TAB_ID = $moduleTabId;
        }
    }

    private function getParam(string $name): ?string {
        $value = null;
        if (isset($_GET[$name])) {
            $value = $_GET[$name];
        } else if (isset($_POST[$name])) {
            $value = $_POST[$name];
        }
        return $value;
    }

    private function loadImage(): void
    {
        $urlParts = UrlHelper::splitIntoParts($_SERVER['REQUEST_URI']);
        $id = $urlParts[count($urlParts) - 1];
        $imageDao = ImageDaoMysql::getInstance();
        $image = $imageDao->getImage($id);

        if (!$image->isPublished()) {
            Authenticator::isAuthenticated();
        }

        if (isset($_GET['thumb']) && $_GET['thumb'] == 'true') {
            $filename = $image->getThumbFileName();
        } else if (isset($_GET['mobile']) && $_GET['mobile'] == 'true') {
            $filename = $image->getMobileFilename();
        } else {
            $filename = $image->getFilename();
        }

        $path = UPLOAD_DIR . "/" . $filename;
        $splits = explode('.', $filename);
        $extension = $splits[count($splits) - 1];

        if ($extension == "jpg" || $extension == "jpeg") {
            header("Content-Type: image/jpeg");
        } else if ($extension == "gif") {
            header("Content-Type: image/gif");
        } else if ($extension == "png") {
            header("Content-Type: img/png");
        } else {
            header("Content-Type: image/$extension");
        }
        readfile($path);
    }

}