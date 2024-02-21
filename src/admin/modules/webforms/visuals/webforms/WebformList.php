<?php

namespace Obcato\Core\admin\modules\webforms\visuals\webforms;

use Obcato\ComponentApi\TemplateData;
use Obcato\Core\admin\database\dao\WebformDao;
use Obcato\Core\admin\database\dao\WebformDaoMysql;
use Obcato\Core\admin\modules\webforms\model\Webform;
use Obcato\Core\admin\modules\webforms\WebformRequestHandler;
use Obcato\Core\admin\view\views\Panel;

class WebformList extends Panel {

    private ?WebForm $_current_webform;
    private WebformDao $_webform_dao;

    public function __construct(?WebForm $current_webform, WebformRequestHandler $webform_request_handler) {
        parent::__construct("webforms_list_panel_title", 'webforms_list');
        $this->_current_webform = $current_webform;
        $this->_webform_dao = WebformDaoMysql::getInstance();
    }

    public function getPanelContentTemplate(): string {
        return "modules/webforms/webforms/list.tpl";
    }

    public function loadPanelContent(TemplateData $data): void {
        $webforms = $this->_webform_dao->getAllWebForms();
        $webforms_data = array();
        foreach ($webforms as $webform) {
            $webform_data = array();
            $webform_data["id"] = $webform->getId();
            $webform_data["title"] = $webform->getTitle();
            $is_selected = false;
            if ($this->_current_webform) {
                $is_selected = $webform->getId() == $this->_current_webform->getId();
            }
            $webform_data["is_selected"] = $is_selected;
            $webforms_data[] = $webform_data;
        }
        $data->assign("webforms", $webforms_data);
    }

}
