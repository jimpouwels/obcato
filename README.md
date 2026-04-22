# Intro
Pageflow is an initiative to offer a simple, modular and multi-language web content management system. Pageflow is open-source, and therefore free. The idea behind a web content management system is to facilitate the creation of a website with as little technical or programming knowledge as possible. Creation of a website using Pageflow will involve the following activities:

* Programming Smarty Templates according to the Pageflow API's and configuring them in the Pageflow WebUI.
* Authoring articles, pages, images, etc. in the Pageflow WebUI. Templates will be linked to these presentable elements and will be rendered as HTML.

# Getting started
Pageflow is written in PHP and runs on top of a Mysql Database. A hosting platform therefore requires at least a PHP runtime and a Mysql database.

When you look at the source of Pageflow, it has 2 sections:

# Installation

## Make a new website
A new website that is based on Pageflow starts with the creation of a new PHP packagist application. Make Pageflow a dependency. It will be installed to the `vendor` section.

## Upload Pageflow
Upload your `vendor` folder to the private webspace of your webhosting. This includes Pageflow sources.

## Initialize the frontend and prepare the Pageflow WebUI
In the public section (e.g. `httpd.www`) of your webspace, you will need to create one file to render the frontend and the Pageflow WebUI:

`index.php`
```
namespace reisvirus\reisvirus;

define("PUBLIC_ROOT", __DIR__);
define("PRIVATE_ROOT", PUBLIC_ROOT . "/<RELATIVE_LOCATION_OF_PRIVATE_WEBSPACE>");
define("PAGEFLOW_ROOT", PRIVATE_ROOT . "/vendor/pageflow/pageflow/src");

require_once PAGEFLOW_ROOT . "/bootstrap.php";
```

Fill in `<RELATIVE_LOCATION_OF_PRIVATE_WEBSPACE>` as required.

Make a request from the browser to the root of your webspace, e.g. https://www.yourdomain.com.
This will initialize a .htaccess file so that all routing works properly.

## Frontend Templates
You can now start adding frontend templates to `<PRIVATE_DIR>/templates`
