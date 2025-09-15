# Intro
Obcato is an initiative to offer a simple, modular and multi-language web content management system. Obcato is open-source, and therefore free. The idea behind a web content management system is to facilitate the creation of a website with as little technical or programming knowledge as possible. Creation of a website using Obcato will involve the following activities:

* Programming Smarty Templates according to the Obcato API's and configuring them in the Obcato WebUI.
* Authoring articles, pages, images, etc. in the Obcato WebUI. Templates will be linked to these presentable elements and will be rendered as HTML.

# Getting started
Obcato is written in PHP and runs on top of a Mysql Database. A hosting platform therefore requires at least a PHP runtime and a Mysql database.

When you look at the source of Obcato, it has 2 sections:

# Installation

## Make a new website
A new website that is based on Obcato starts with the creation of a new PHP packagist application. Make Obcato a dependency. It will be installed to the `vendor` section.

## Upload Obcato
Upload your `vendor` folder to the private webspace of your webhosting. This includes Obcato sources.

## Initialize the frontend and prepare the Obcato WebUI
In the public section (e.g. `httpd.www`) of your webspace, you will need to create one file to render the frontend and the Obcato WebUI:

`index.php`
```
namespace reisvirus\reisvirus;

define("PUBLIC_ROOT", __DIR__);
define("PRIVATE_ROOT", PUBLIC_ROOT . "/<RELATIVE_LOCATION_OF_PRIVATE_WEBSPACE>");
define("OBCATO_ROOT", PRIVATE_ROOT . "/vendor/obcato/obcato/src");

require_once OBCATO_ROOT . "/bootstrap.php";
```

Fill in `<RELATIVE_LOCATION_OF_PRIVATE_WEBSPACE>` as required.

Make a request from the browser to the root of your webspace, e.g. https://www.yourdomain.com.
This will initialize a .htaccess file so that all routing works properly.

## Frontend Templates
You can now start adding frontend templates to `<PRIVATE_DIR>/templates`
