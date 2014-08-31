<?php
    // No direct access
    defined('_ACCESS') or die;

    require_once "database/mysql_connector.php";
    require_once "install/install_folders_form.php";

    if (file_exists("database_config.php")) {
        require_once "database_config.php";
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
                $link = mysql_connect($database_url . ":" . $database_port, $database_username, $database_password);
                if (!$link || empty($database_url)) {
                    $_SESSION["step1_error"] = "Kan niet verbinden met de database, controleer de gegevens";
                }
                else {
                    createDatabaseConfig($database_url, $database_port, $database_username, $database_password, $database_name);
                    header("Location: /admin/install/index.php?mode=install&step=2");
                }
            }
        } else if (getStepFromPostRequest() == "2") {
            $mysql_connector = MySqlConnector::getInstance();
            $mysql_connector->executeQuery("CREATE DATABASE " . DATABASE_NAME);
            $mysql_connector->executeSql(file_get_contents("install_script.sql"));
            header("Location: /admin/install/index.php?mode=install&step=3");
        } else if (getStepFromPostRequest() == "3") {
            $form = new InstallFoldersForm();
            try {
                $form->loadFields();
                header("Location: /admin/install/index.php?mode=install&step=4");
            } catch (FormException $e) {
                global $errors;
            }
        }
    }

    function createDatabaseConfig($url, $port, $username, $password, $name) {
        $db_config_file = fopen("../database_config.php", "w") or die("No write access to create database configuration");
        fwrite($db_config_file, "<?php\n");
        fwrite($db_config_file, "defined('_ACCESS') or die;\n");
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl_NL" lang="nl_NL">
    <head>
        <title>Site Administrator - Installation</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" href="static/css/styles.css" type="text/css" />
        <link rel="stylesheet" href="static/css/install.css" type="text/css" />

        <script type="text/javascript" src="static/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="static/js/install.js"></script>
    </head>
    <body>
        <form id="install_form" method="post" action="/admin/index.php?mode=install&amp;step=<?= $_GET["step"] ?>">
            <fieldset class="admin_fieldset">
                <?php if ($_GET["step"] == "1"): ?>
                <div class="fieldset-title">Configureer database</div>
                <div class="content">
                    <p>Site Administrator vereist opslag van data in een MySQL database met InnoDB als storage engine.
                       Vul de volgende gegevens van uw database in om het systeem correct te laten werken.</p>
                    <input type="hidden" id="step" name="step" value="1" />
                    <ul class="admin_form">
                        <li>
                            <label class="admin_label" for="database_url">URL</label>
                            <input type="text"  value="<?= isset($database_url) ? $database_url : "" ?>" id="database_url" name="database_url" class="admin_field">
                        </li>
                        <li><label class="admin_label" for="database_port">Poort</label>
                            <input type="text" value="<?= isset($database_port) ? $database_port : "" ?>" id="database_port" name="database_port" class="admin_field">
                        </li>
                        <li>
                            <label class="admin_label" for="database_username">Gebruikersnaam</label>
                            <input type="text" value="<?= isset($database_username) ? $database_username : "" ?>" id="database_username" name="database_username" class="admin_field">
                        </li>
                        <li>
                            <label class="admin_label" for="database_password">Wachtwoord</label>
                            <input type="password" value="<?= isset($database_password) ? $database_password : "" ?>" id="database_password" name="database_password" class="admin_field">
                        </li>
                        <li>
                            <label class="admin_label" for="database_name">Database naam</label>
                            <input type="text" value="<?= isset($database_name) ? $database_name : "" ?>" id="database_name" name="database_name" class="admin_field">
                        </li>
                        <li>
                            <a href="#" onClick="return false;" class="button install_submit_button" title="Test verbinding">Test verbinding</a>
                        </li>
                        <?php if (isset($_SESSION["step1_error"])): ?>
                            <li class="error">
                                <?= $_SESSION["step1_error"] ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php elseif ($_GET["step"] == "2"): ?>
                    <div class="fieldset-title">Initialiseer database</div>
                    <input type="hidden" id="step" name="step" value="2" />
                    <ul class="admin_form">
                        <li class="success">
                            <p>Succesvol verbonden met de database. Klik op "Database initialiseren" om de database te creëren en te vullen.</p>
                        </li>
                        <li>
                            <a href="#" onClick="return false;" class="button install_submit_button" title="Test verbinding">Initialiseer database</a>
                        </li>
                    </ul>
                <?php elseif ($_GET["step"] == "3"): ?>
                    <div class="fieldset-title">Configureer mappen</div>
                    <div class="content">
                        <input type="hidden" id="step" name="step" value="3" />
                        <ul class="admin_form">
                            <li style="color: red;">
                                <p>Let op! Zorg ervoor dat de volgende directories buiten de publieke backend map staan!
                                   Op deze manier zijn ze niet op te vragen zonder ingelogd te zijn. Via de onderstaande
                                   mapconfiguraties zorgt het systeem ervoor dat ze veilig worden ingeladen.</p>
                            </li>
                            <li>
                                Huidige map: <?= dirname(__FILE__) ?>
                            </li>
                            <li>
                                <label class="admin_label" for="frontend_template_dir">Frontend templates map</label>
                                <input type="text"  value="<?= isset($frontend_template_dir) ? $frontend_template_dir : "" ?>" id="frontend_template_dir" name="frontend_template_dir" class="admin_field">
                            </li>
                            <li>
                                <label class="admin_label" for="$backend_root_dir">Configuration map</label>
                                <input type="text" value="<?= isset($backend_root_dir) ? $backend_root_dir : "" ?>" id="$backend_root_dir" name="$backend_root_dir" class="admin_field">
                            </li>
                            <li>
                                <label class="admin_label" for="backend_static_files_dir">Backend statische bestanden map</label>
                                <input type="text" value="<?= isset($backend_static_files_dir) ? $backend_static_files_dir : "" ?>" id="$backend_static_files_dir" name="$backend_static_files_dir" class="admin_field">
                            </li>
                            <li>
                                <label class="admin_label" for="config_dir">Configuratie map</label>
                                <input type="text" value="<?= isset($config_dir) ? $config_dir : "" ?>" id="config_dir" name="config_dir" class="admin_field">
                            </li>
                            <li>
                                <label class="admin_label" for="upload_dir">Upload map</label>
                                <input type="text" value="<?= isset($upload_dir) ? $upload_dir : "" ?>" id="upload_dir" name="upload_dir" class="admin_field">
                            </li>
                            <li>
                                <label class="admin_label" for="backend_template_dir">Backend templates map</label>
                                <input type="text" value="<?= isset($backend_template_dir) ? $backend_template_dir : "" ?>" id="backend_template_dir" name="backend_template_dir" class="admin_field">
                            </li>
                            <li>
                                <label class="admin_label" for="component_dir">Component map</label>
                                <input type="text" value="<?= isset($component_dir) ? $component_dir : "" ?>" id="component_dir" name="component_dir" class="admin_field">
                            </li>
                            <li>
                                <a href="#" onClick="return false;" class="button install_submit_button" title="Sla mappen op">Sla mappen op</a>
                            </li>
                            <?php if (isset($errors) && count($errors) > 0): ?>
                            <li>
                                <p class="error">Vul a.u.b. alle velden in</p>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </fieldset>
        </form>
    </body>
</html>