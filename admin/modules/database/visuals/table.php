<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . "database/mysql_connector.php";
    require_once CMS_ROOT . "database/dao/database_dao.php";

    class TablePanel extends Panel {

        private static string $TABLES_TEMPLATE = "modules/database/table.tpl";
        private array $_table;

        public function __construct(array $table) {
            parent::__construct($table['name'], 'table_details_panel');
            $this->_table = $table;
        }

        public function render(): string {
            return parent::render();
        }

        public function renderPanelContent(): string {
            $this->getTemplateEngine()->assign("table", $this->_table);
            return $this->getTemplateEngine()->fetch(self::$TABLES_TEMPLATE);
        }
    }
