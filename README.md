![Simple MVC Framework](http://simplemvcframework.com/app/templates/publicthemes/smvc/images/logo.png)

#What is Simple MVC Framework?

Simple MVC Framework (SMVC) is a PHP 5.5 MVC system. It's designed to be lightweight and modular, allowing developers to build better and easy to maintain code with PHP.

The base framework comes with a range of helper classes.

## Version 2.2+orm

This is a customized version of the SMVC v2.2 which integrates Laravel's ***Illuminate\Database*** and ***Illuminate\Validation*** packages into the framework.

## Documentation

Full docs & tutorials are available at [simplemvcframework.com](http://simplemvcframework.com) (although this is a customized version the documentation of the original one is still applicable).

## Requirements

 The framework requirements are limited:

 - Apache Web Server or equivalent with mod rewrite support.
 - PHP 5.5 or greater is required.

 Although a database is not required, if a database is to be used the system is designed to work with a MySQL database. The framework can be changed to work with another database type.

## Installation

1. Download the framework.
2. Unzip the package.
3. Upload the framework files to your server (only the contents of the ```public``` folder are required to be in your web root).
4. Open ```app/Core/config.php```, set your base path (it is the path from the web root to the folder in which the ```index.php``` file resides), and database credentials (if a database is needed).
5. Edit ```.htaccess``` file (located in your public folder) and save the base path (it is the path from the web root to the folder in which the ```index.php``` file resides).
6. Navigate to your project on a terminal/command prompt then run ```composer install``` that will update the vendor folder.
7. If you have done the above steps correctly, you should have a working application now. Start working with your application by setting up your routes at ```app/Core/routes.php```.
