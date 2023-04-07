<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "database/dao/database_dao.php";

    class TablePanel extends Panel {

        private static $TABLES_TEMPLATE = "modules/database/table.tpl";
        private $_table;

        public function __construct($table) {
            parent::__construct($table['name'], 'table_details_panel');
            $this->_table = $table;
        }

        public function renderVisual(): string {
            return parent::renderVisual();
        }

        public function renderPanelContent() {
            $this->getTemplateEngine()->assign("table", $this->_table);
            return $this->getTemplateEngine()->fetch(self::$TABLES_TEMPLATE);
        }
    }
