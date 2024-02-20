<?php
defined('_ACCESS') or die;

require_once "../../frontend_bootstrap.php";
require_once CMS_ROOT . "/database/MysqlConnector.php";
require_once CMS_ROOT . "/database/dao/SettingsDaoMysql.php";

if (file_exists(PRIVATE_DIR . "/database_config.php")) {
    require_once PRIVATE_DIR . "/database_config.php";
    $mysql_connector = MySqlConnector::getInstance();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (getStepFromPostRequest() == "1") {
        $database_url = $_POST["database_url"];
        $database_port = $_POST["database_port"];
        $database_username = $_POST["database_username"];
        $database_password = $_POST["database_password"];
        $database_name = $_POST["database_name"];
        if (empty($database_name)) {
            $_SESSION["step1_error"] = "Geef een database naam op";
        } else {
            error_reporting(E_ERROR); // don't show mysql errors
            $link = mysqli_connect($database_url . ":" . $database_port, $database_username, $database_password);
            if (!$link || empty($database_url)) {
                $_SESSION["step1_error"] = "Kan niet verbinden met de database, controleer de gegevens";
            } else {
                createDatabaseConfig($database_url, $database_port, $database_username, $database_password, $database_name);
                header("Location: /admin/index.php?mode=install&step=2");
            }
        }
    } else if (getStepFromPostRequest() == "2") {
        $mysql_connector = MySqlConnector::getInstance();
        $mysql_connector->executeQuery("CREATE DATABASE IF NOT EXISTS " . DATABASE_NAME);
        $mysql_connector->executeSql(file_get_contents("./install/install_script.sql"));
        header("Location: /admin/index.php?mode=install&step=3");
    } else if (getStepFromPostRequest() == "3") {
        if ($_POST["installation_finish_type"] == "delete_install_files")
            deleteInstallationFiles();
        header("Location: /admin/login.php");
    }
}

function createDatabaseConfig($url, $port, $username, $password, $name) {
    $db_config_file = fopen(PRIVATE_DIR . "/database_config.php", "w") or die("No write access to create database configuration");
    fwrite($db_config_file, "<?php\n");
    fwrite($db_config_file, "define(\"HOST\", \"" . $url . "\");\n");
    fwrite($db_config_file, "define(\"PORT\", \"" . $port . "\");\n");
    fwrite($db_config_file, "define(\"USERNAME\", \"" . $username . "\");\n");
    fwrite($db_config_file, "define(\"PASSWORD\", \"" . $password . "\");\n");
    fwrite($db_config_file, "define(\"DATABASE_NAME\", \"" . $name . "\");\n");
    fwrite($db_config_file, "?>");
}

function getStepFromPostRequest() {
    if (isset($_POST["step"]))
        return $_POST["step"];
    else
        return "";
}

function deleteInstallationFiles(): void {
    $full_path = getcwd();
    deleteFolder($full_path . "/install");
    deleteFile($full_path . "/static/css/install.css");
    deleteFile($full_path . "/static/js/install.js");
}

function deleteFolder($dir) {
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? deleteFolder("$dir/$file") : deleteFile("$dir/$file");
    }
    return rmdir($dir);
}

function deleteFile($path) {
    unlink($path);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl_NL" lang="nl_NL">
<head>
    <title>Obcato - Installation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <link rel="stylesheet" href="static/css/styles.css" type="text/css"/>
    <link rel="stylesheet" href="static/css/install.css" type="text/css"/>

    <script type="text/javascript" src="static/js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="static/js/install.js"></script>
</head>
<body>
<form id="install_form" method="post" action="/admin/index.php?mode=install&amp;step=<?= $_GET["step"] ?>">
    <fieldset class="panel">
        <?php if ($_GET["step"] == "1"): ?>
            <div class="panel-title">Configureer database</div>
            <div class="content">
                <p>Obcato vereist opslag van data in een MySQL database met InnoDB als storage engine.
                    Vul de volgende gegevens van uw database in om het systeem correct te laten werken.</p>
                <input type="hidden" id="step" name="step" value="1"/>
                <ul class="admin_form">
                    <li>
                        <label class="admin_label" for="database_url">URL</label>
                        <input type="text" value="<?= isset($database_url) ? $database_url : "" ?>" id="database_url"
                               name="database_url" class="admin_field">
                    </li>
                    <li><label class="admin_label" for="database_port">Poort</label>
                        <input type="text" value="<?= isset($database_port) ? $database_port : "" ?>" id="database_port"
                               name="database_port" class="admin_field">
                    </li>
                    <li>
                        <label class="admin_label" for="database_username">Gebruikersnaam</label>
                        <input type="text" value="<?= isset($database_username) ? $database_username : "" ?>"
                               id="database_username" name="database_username" class="admin_field">
                    </li>
                    <li>
                        <label class="admin_label" for="database_password">Wachtwoord</label>
                        <input type="password" value="<?= isset($database_password) ? $database_password : "" ?>"
                               id="database_password" name="database_password" class="admin_field">
                    </li>
                    <li>
                        <label class="admin_label" for="database_name">Database naam</label>
                        <input type="text" value="<?= isset($database_name) ? $database_name : "" ?>" id="database_name"
                               name="database_name" class="admin_field">
                    </li>
                    <li>
                        <a href="#" onClick="return false;" class="button install_submit_button"
                           title="Test verbinding">Test verbinding</a>
                    </li>
                    <?php if (isset($_SESSION["step1_error"])): ?>
                        <li class="error">
                            <?= $_SESSION["step1_error"] ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php elseif ($_GET["step"] == "2"): ?>
            <div class="panel-title">Initialiseer database</div>
            <input type="hidden" id="step" name="step" value="2"/>
            <ul class="admin_form">
                <li class="success">
                    <p>Succesvol verbonden met de database. Klik op "Database initialiseren" om de database te creëren
                        en te vullen.</p>
                </li>
                <li>
                    <a href="#" onClick="return false;" class="button install_submit_button" title="Test verbinding">Initialiseer
                        database</a>
                </li>
            </ul>
        <?php elseif ($_GET["step"] == "3"): ?>
            <div class="panel-title">Installatie succesvol</div>
            <div class="content">
                <input type="hidden" id="step" name="step" value="4"/>
                <ul class="admin_form">
                    <input type="hidden" value="" name="installation_finish_type" id="installation_finish_type"/>
                    <li>
                        <p>De installatie is succesvol afgerond. Wilt u de installatiebestanden verwijderen?</p>
                    </li>
                    <li>
                        <a href="#" onClick="return false;" class="button delete_install_files_button"
                           title="Verwijder installatiebestanden">Verwijder installatiebestanden</a>
                    </li>
                    <li>
                        <a href="#" onClick="return false;" class="button redirect_to_login"
                           title="Direct naar de loginpagina">Direct naar de loginpagina</a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </fieldset>
</form>
</body>
</html>
