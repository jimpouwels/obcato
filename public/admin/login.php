<?php

namespace Obcato\Core;

use Obcato\ComponentApi\Session;
use Obcato\Core\admin\authentication\Authenticator;
use Obcato\Core\admin\view\TemplateEngine;
use Obcato\Core\admin\view\views\Button;
use Obcato\Core\admin\view\views\PasswordField;
use Obcato\Core\admin\view\views\Pulldown;
use Obcato\Core\admin\view\views\TextField;

define("_ACCESS", "GRANTED");

require_once "../bootstrap.php";

if (!file_exists(PRIVATE_DIR . "/database_config.php")) {
    header("Location: /admin/index.php");
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get values
    $username = $_POST['username'];
    $password = $_POST['password'];

    Authenticator::logIn($username, $password);
    Session::setCurrentLanguage($_POST['language']);
    if (Authenticator::isAuthenticated()) {
        $redirect_to = '/admin';
        if (isset($_POST['org_url']) && $_POST['org_url'] != '') {
            $redirect_to = $_POST['org_url'];
        }
        header('Location: ' . $redirect_to);
        exit();
    }

    $errors['login_unsuccessful'] = 'Verkeerde gebruikersnaam / wachtwoord combinatie';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
<head>
    <link rel="stylesheet" href="/admin/static/css/styles.css" type="text/css"/>
    <link rel="stylesheet" href="/admin/static/css/login.css" type="text/css"/>

    <script type="text/javascript" src="/static/js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="/static/js/login_functions.js"></script>

    <title>Obcato</title>
    <meta name="robots" content="noindex"/>
</head>
<body>
<div id="login-form-box">
    <form id="form-login" method="post" action="/admin/login.php">
        <fieldset class="loginform">
            <div class="header">
                <p>Obcato</p>
            </div>
            <div class="fields">
                <?php if (isset($_GET['org_url']) && $_GET['org_url'] != ''): ?>
                    <input type="hidden" name="org_url" value="<?= urldecode($_GET['org_url']); ?>"/>
                <?php endif; ?>

                <?php
                $username = new TextField(TemplateEngine::getInstance(), 'username', 'Gebruikersnaam', "", true, false, null);
                echo $username->render();
                $password = new PasswordField(TemplateEngine::getInstance(), 'password', 'Wachtwoord', "", true, false);
                echo $password->render();
                $languages = array();
                $languages[] = array('name' => 'Nederlands', 'value' => 'nl');
                $languages[] = array('name' => 'English', 'value' => 'en');
                $language = new Pulldown(TemplateEngine::getInstance(), 'language', 'Taal', 'nl', $languages, false, null);
                echo $language->render();
                ?>

                <div class="button-holder">
                    <?php
                    $button = new Button(TemplateEngine::getInstance(), "", "Inloggen", "document.getElementById('form-login').submit(); return false;");
                    echo $button->render();
                    ?>
                </div>
            </div>
            <div class="error">
                <?php
                if (!empty($errors['login_unsuccessful'])) {
                    echo "<p class=\"red\">" . $errors['login_unsuccessful'] . "</p>";
                }
                ?>
            </div>
        </fieldset>
    </form>
</div>
</body>
</html>
