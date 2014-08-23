<?php
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
                    $_SESSION["step1_error"] = "Kon niet verbinden met de database, controleer de gegevens";
                }
                else {
                    createDatabaseConfig($database_url, $database_port, $database_username, $database_password, $database_name);
                    header("Location: /admin/install/index.php?step=2");
                }
            }
        } else if (getStepFromPostRequest() == "2") {

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

        <link rel="stylesheet" href="../static/css/styles.css" type="text/css" />
        <link rel="stylesheet" href="../static/css/install.css" type="text/css" />

        <script type="text/javascript" src="../static/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="../static/js/install.js"></script>
    </head>
    <body>
        <form id="install_form" method="post" action="/admin/install/index.php?step=<?= $_GET["step"] ?>">
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
                        <li>
                            <label class="admin_label" for="database_port">Poort</label>
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
                            <a href="#" onClick="return false;" class="button database_submit_button" title="Test verbinding">Test verbinding</a>
                        </li>
                        <?php if (isset($_SESSION["step1_error"])): ?>
                            <li class="error">
                                <?= $_SESSION["step1_error"] ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <?php if ($_GET["step"] == "2"): ?>
                    <div class="fieldset-title">Initialiseer database</div>
                    <input type="hidden" id="step" name="step" value="2" />
                    <ul class="admin_form">
                        <li class="success">
                            <p>Succesvol verbonden met de database. Klik op "Database initialiseren" om de database te creëren en te vullen.</p>
                        </li>
                        <li>
                            <a href="#" onClick="return false;" class="button database_submit_button" title="Test verbinding">Initialiseer database</a>
                        </li>
                    </ul>
                <?php endif; ?>
            </fieldset>
        </form>
    </body>
</html>