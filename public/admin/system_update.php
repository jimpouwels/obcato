<?php

namespace Obcato;

use DOMDocument;
use Obcato\Core\authentication\Authenticator;
use Obcato\Core\database\dao\SettingsDaoMysql;
use Obcato\Core\database\MysqlConnector;
use const Obcato\Core\CONFIG_DIR;
use const Obcato\core\SYSTEM_VERSION;

require_once "../bootstrap.php";

// INCLUDE SYSTEM CONSTANTS
require_once CMS_ROOT . "/authentication/Authenticator.php";
require_once PRIVATE_DIR . "/database_config.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/database/dao/SettingsDaoMysql.php";

// AUTHENTICATE
Authenticator::isAuthenticated();

$settings_dao = SettingsDaoMysql::getInstance();
$website_settings = $settings_dao->getSettings();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'update_database') {
        $update_script = CONFIG_DIR . '/update_scripts/update_database.xml';
        $doc = new DOMDocument();
        $doc->load($update_script);

        $new_version = null;
        $current_version_found = false;
        $versions = $doc->getElementsByTagName("version");

        $mysql_database = MysqlConnector::getInstance();
        foreach ($versions as $version) {
            if ($current_version_found) {
                $queries = $version->getElementsByTagName('query');
                foreach ($queries as $query) {
                    $mysql_database->executeQuery($query->nodeValue);
                }
                $new_version = $version->getElementsByTagName('number')->item(0)->nodeValue;
            } else {
                $version_number = $version->getElementsByTagName('number')->item(0)->nodeValue;
                if ($version_number == $website_settings->getDatabaseVersion() || !$website_settings->getDatabaseVersion()) {
                    $current_version_found = true;
                }
            }
        }
        $website_settings->setDatabaseVersion($new_version);
        $settings_dao->update($website_settings);
    }
}
// update settings object
$website_settings = $settings_dao->getSettings();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl_NL" lang="nl_NL">
<head>
    <title>Site Administration - Updates</title>
</head>
<body>
<table>
    <tr>
        <td><strong>System version:</strong></td>
        <td><?= SYSTEM_VERSION; ?></td>
    </tr>
    <tr>
        <td><strong>Database version:</strong></td>
        <td><?= $website_settings->getDatabaseVersion(); ?></td>
    </tr>
</table>
<?php if (SYSTEM_VERSION != $website_settings->getDatabaseVersion()): ?>
    <form action="/admin/system_update.php" method="post">
        <input type="hidden" value="update_database" name="action"/>
        <input type="submit" value="Update database"/>
    </form>
<?php endif; ?>
</body>
</html>
