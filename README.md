# Intro
Obcato is an initiative to offer a simple, modular and multi-language web content management system. Obcato is open-source, and therefore free. The idea behind a web content management system is to facilitate the creation of a website with as little technical or programming knowledge as possible. Creation of a website using Obcato will involve the following activities:

* Programming Smarty Templates according to the Obcato API's and configuring them in the Obcato WebUI.
* Authoring articles, pages, images, etc. in the Obcato WebUI. Templates will be linked to these presentable elements and will be rendered as HTML.

# Getting started
Obcato is written in PHP and runs on top of an Mysql Database. A hosting platform therefore requires at least a PHP runtime and a Mysql database.

On the highest level, Obcato consists of two sections:

## Private

This section contains the following:
* Directories containing static data
  * `config` directory, containing configuration data
  * `upload` directory, containing uploaded images and files via the Obcato WebUI
  * `templates` directory, containing frontend templates
* `vendor` directory, containing all software that is required to run Obcato

The private section must be located in a location that is not served by the webserver, as the contents are not to be directly downloaded directory via the internet.

## Public

This section contains the files that are to be accessed directly via the internet. These will be the entrypoint of both the Obcato WebUI and the frontend. Any static files required by the frontend, such as stylesheets, images and javascript source files can be placed here.

The public section must be located in the root of the directory that is served to the internet by the webserver.
