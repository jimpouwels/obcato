<?php

namespace Pageflow\Core\modules\images\visuals\functional;

use Pageflow\Core\modules\images\FunctionalImageRequestHandler;
use Pageflow\Core\modules\images\model\FunctionalImage;
use Pageflow\Core\modules\images\model\FunctionalImageFolder;
use Pageflow\Core\view\views\Visual;

class FunctionalImagesTab extends Visual {

    private FunctionalImageRequestHandler $requestHandler;

    public function __construct(FunctionalImageRequestHandler $requestHandler) {
        parent::__construct();
        $this->requestHandler = $requestHandler;
    }

    public function getTemplateFilename(): string {
        return "images/templates/functional/root.tpl";
    }

    public function load(): void {
        $dao  = \Pageflow\Core\database\dao\FunctionalImageDaoMysql::getInstance();
        $tree = $dao->getFolderTree();

        $currentImage  = $this->requestHandler->getCurrentImage();
        $currentFolder = $this->requestHandler->getCurrentFolder();

        $this->assign('root_folders',    $this->buildFolderData($tree['folders']));
        $this->assign('root_images',     $this->buildImageData($tree['images']));
        $this->assign('current_image_id',  $currentImage  ? $currentImage->getId()  : null);
        $this->assign('current_folder_id', $currentFolder ? $currentFolder->getId() : null);

        if ($currentImage) {
            $this->assign('editor_mode', 'image');
            $this->assign('editor', (new FunctionalImageEditor($currentImage))->render());
        } elseif ($currentFolder) {
            $this->assign('editor_mode', 'folder');
            $this->assign('editor', (new FunctionalFolderEditor($currentFolder))->render());
        } else {
            $this->assign('editor_mode', 'none');
        }
    }

    private function buildFolderData(array $folders): array {
        return array_map(function (FunctionalImageFolder $folder) {
            return [
                'id'          => $folder->getId(),
                'name'        => $folder->getName(),
                'sub_folders' => $this->buildFolderData($folder->getSubFolders()),
                'images'      => $this->buildImageData($folder->getImages()),
            ];
        }, $folders);
    }

    private function buildImageData(array $images): array {
        return array_map(function (FunctionalImage $img) {
            return [
                'id'    => $img->getId(),
                'title' => $img->getTitle(),
            ];
        }, $images);
    }
}
