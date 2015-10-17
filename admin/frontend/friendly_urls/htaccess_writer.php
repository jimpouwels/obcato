<?php
    defined('_ACCESS') or die;

    require_once CMS_ROOT . 'database/dao/settings_dao.php';
    require_once CMS_ROOT . 'utilities/string_utility.php';

    class HtAccessWriter {

        private $_htaccess_file_path;

        public function __construct() {
            $public_root_dir = SettingsDao::getInstance()->getSettings()->getPublicRootDir();
            $this->_htaccess_file_path = $public_root_dir . '/.htaccess';
            $this->createHtaccessFileIfNotExists();
        }

        public function addOrUpdateRewriteRule($rule_id, $source_url, $target_url) {
            $handle = fopen($this->_htaccess_file_path, 'r');
            $found_rule = null;
            while (($line = fgets($handle)) !== false) {
                if (StringUtility::endsWith($line, '#' . $rule_id)) {
                    $found_rule = $line;
                }
            }
            fclose($handle);
            $new_rule = "\nRewriteRule ^" . $target_url . ' ' . $source_url . ' [NC,L] #' . $rule_id;
            if ($found_rule != null) {
                $htaccess_content = file_get_contents($this->_htaccess_file_path);
                $htaccess_content = str_replace($found_rule, $new_rule, $htaccess_content);
                file_put_contents($this->_htaccess_file_path, $htaccess_content);
            } else {
                file_put_contents($this->_htaccess_file_path, $new_rule, FILE_APPEND);
            }
        }

        private function createHtaccessFileIfNotExists() {
            if (!file_exists($this->_htaccess_file_path)) {
                $handle = fopen($this->_htaccess_file_path, 'w');
                fclose($handle);
                file_put_contents($this->_htaccess_file_path, "RewriteEngine on\n");
            }
        }

    }
