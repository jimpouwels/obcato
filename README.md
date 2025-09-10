# Intro
Obcato is an initiative to offer a simple, modular and multi-language web content management system. Obcato is open-source, and therefore free. The idea behind a web content management system is to facilitate the creation of a website with as little technical or programming knowledge as possible. Creation of a website using Obcato will involve the following activities:

* Programming Smarty Templates according to the Obcato API's and configuring them in the Obcato WebUI.
* Authoring articles, pages, images, etc. in the Obcato WebUI. Templates will be linked to these presentable elements and will be rendered as HTML.

# Getting started
Obcato is written in PHP and runs on top of a Mysql Database. A hosting platform therefore requires at least a PHP runtime and a Mysql database.

When you look at the source of Obcato, it has 2 sections:

## Public
This section contains the files that are to be accessed directly via the internet. These will be the entrypoint of both the Obcato WebUI.

## src
This contains all the sources for the Obcato WebUI.

# Installation

## Make a new website
A new website that is based on Obcato starts with the creation of a new PHP packagist application. Make Obcato a dependency. It will be installed to the `vendor` section.

## Initialize the frontend and prepare the Obcato WebUI
In the public section (e.g. `httpd.www`) of your webspace, you will need two files at a minimum to run the frontend and the Obcato WebUI:

`index.php`
```
<?php

namespace <your_namespace>; // e.g. <reisvirus/reisvirus>
require_once "bootstrap.php";

\Obcato\renderFrontend();
```

`config.php`
```
<?php
const PRIVATE_DIR_PRODUCTION = "<relative_path_to_public_section_of_webspace>"; // e.g. "/../httpd.private";

// for local runs (when host is localhost)
const PRIVATE_DIR_LOCAL = "<relative_path_to_private_section_of_webspace>"; // e.g. "/../private";
```

Upload the contents of the `/public` directory in the Obcato source to the public section of your webspace.

Create directory:
```
<PRIVATE_DIR_PRODUCTION>/templates
<PRIVATE_DIR_PRODUCTION>/config
<PRIVATE_DIR_PRODUCTION>/upload
```

## Frontend Templates
You can now start adding frontend templates to `<PRIVATE_DIR_PRODUCTION>/templates`
