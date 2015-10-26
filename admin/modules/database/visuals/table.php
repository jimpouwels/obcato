<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "database/dao/database_dao.php";

    class TablePanel extends Panel {

        private static $TABLES_TEMPLATE = "modules/database/table.tpl";
        private $_template_engine;
        private $_table;

        public function __construct($table) {
            parent::__construct($table['name'], 'table_details_panel');
            $this->_template_engine = TemplateEngine::getInstance();
            $this->_table = $table;
        }

        public function render() {
            return parent::render();
        }

        public function renderPanelContent() {
            $this->_template_engine->assign("table", $this->_table);
            return $this->_template_engine->fetch(self::$TABLES_TEMPLATE);
        }
    }
