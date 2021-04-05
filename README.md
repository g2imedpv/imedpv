# Note: For security reasons, the app.php file had been removed so that it will not work if you clone the whole project directly.

# CakePHP Application Skeleton
#############################

[![Build Status](https://img.shields.io/travis/cakephp/app/master.svg?style=flat-square)](https://travis-ci.org/cakephp/app)
[![Total Downloads](https://img.shields.io/packagist/dt/cakephp/app.svg?style=flat-square)](https://packagist.org/packages/cakephp/app)

A skeleton for creating applications with [CakePHP](https://cakephp.org) 3.x.

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

## Installation

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app [app_name]`.

If Composer is installed globally, run

```bash
composer create-project --prefer-dist cakephp/app
```

In case you want to use a custom app dir name (e.g. `/myapp/`):

```bash
composer create-project --prefer-dist cakephp/app myapp
```

You can now either use your machine's webserver to view the default home page, or start
up the built-in webserver with:

```bash
bin/cake server -p 8765
```

Then visit `http://localhost:8765` to see the welcome page.

## Update

Since this skeleton is a starting point for your application and various files
would have been modified as per your needs, there isn't a way to provide
automated upgrades, so you have to do any updates manually.

## Configuration

Read and edit `config/app.php` and setup the `'Datasources'` and any other
configuration relevant for your application.

## Layout

The app skeleton uses a subset of [Foundation](http://foundation.zurb.com/) (v5) CSS
framework by default. You can, however, replace it with any other library or
custom styles.

# This line below was made by WENPEI

## Database Connection
After installed the framework, the next thing you need to do that is connectinng the database. In `'app.php'` you can change the user name and password to yours.
![](https://ws1.sinaimg.cn/large/613e8384ly1fx9jvkvizvj20r80b5jsj.jpg)

Based on the MySQL database, the cakephp would "bake" the controller, model and view(template) files with couples of codes. For instance, if the table name is called "users" in database. We could use this code to create controller, model and view files.

```bash
bin/cake bake all users
```

## View Pages
View pages in cakephp was called "Template" which located in `src\Template`. All the pages would be introduced in the following sections.

All the pages we make would apply a layout and these files located in `src\Template\Layout`. Then each page we create will use one of the layout. And the js and css etc. files were indicated in layout as well.

Similarly, some components we usually used would also be encapsulated in `'src\Template\Element'`. For example, the 'Go Top' button we often need would sealed into this place.

## Test