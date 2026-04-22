<?php

namespace Pageflow;

use Pageflow\Core\authentication\Authenticator;
use Pageflow\Core\authentication\Session;
use Pageflow\Core\view\views\Button;
use Pageflow\Core\view\views\PasswordField;
use Pageflow\Core\view\views\Pulldown;
use Pageflow\Core\view\views\TextField;

if (!file_exists(PRIVATE_DIR . "/database_config.php")) {
    header("Location: /admin");
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get values
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (Authenticator::logIn($username, $password)) {
        Authenticator::isAuthenticated();
        Session::setCurrentLanguage($_POST['language']);
        $redirectTo = '/admin';
        if (isset($_POST['orgUrl']) && $_POST['orgUrl'] != '') {
            $redirectTo = $_POST['orgUrl'];
        }
        header('Location: ' . $redirectTo);
        exit();
    }
    $errors['login_unsuccessful'] = 'Verkeerde gebruikersnaam / wachtwoord combinatie';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
<head>
    <link rel="stylesheet" href="/admin?file=/public/css/styles.css" type="text/css" />
    <link rel="stylesheet" href="/admin?file=/public/css/login.css" type="text/css" />

    <script type="text/javascript" src="/admin?file=/public/js/jquery.js"></script>
    <script type="text/javascript" src="/admin?file=/public/js/login_functions.js"></script>

    <title>Pageflow</title>
    <meta name="robots" content="noindex" />
</head>
<body>
<div id="login-form-box">
    <form id="form-login" method="post" action="/admin/login">
        <fieldset class="loginform">
            <div class="header">
                <p>Pageflow</p>
            </div>
            <div class="fields">
                <?php if (isset($_GET['orgUrl']) && $_GET['orgUrl'] != ''): ?>
                    <input type="hidden" name="orgUrl" value="<?= urldecode($_GET['orgUrl']); ?>" />
                <?php endif; ?>

                <?php
                $username = new TextField('username', 'Gebruikersnaam', "", true, false, null);
                echo $username->render();
                $password = new PasswordField('password', 'Wachtwoord', "", true, false);
                echo $password->render();
                $languages = array();
                $languages[] = array('name' => 'Nederlands', 'value' => 'nl');
                $languages[] = array('name' => 'English', 'value' => 'en');
                $language = new Pulldown('language', 'Taal', 'nl', $languages, false, null);
                echo $language->render();
                ?>

                <div class="button-holder">
                    <?php
                    $button = new Button("", "Inloggen", "document.getElementById('form-login').submit(); return false;");
                    echo $button->render();
                    ?>
                </div>
            </div>
            <?php if (!empty($errors['login_unsuccessful'])): ?>
                <div class="error">
                    <p><?= $errors['login_unsuccessful']; ?></p>
                </div>
            <?php endif; ?>
        </fieldset>
    </form>
</div>
</body>
</html>
