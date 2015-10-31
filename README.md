![Simple MVC Framework](http://simplemvcframework.com/app/templates/smvc/img/logo.png)

#What is Simple MVC Framework?

Simple MVC Framework (SMVC) is a PHP 5.5 MVC system. It's designed to be lightweight and modular, allowing developers to build better and easy to maintain code with PHP.

The base framework comes with a range of helper classes.

## Version: 2.2

This is a customized version of the SMVC v2.2 which pulls out publicly-accessible files (js/css/img files) from the ```app``` folder and places them in a separate folder. This setup enables more flexibility in organizing the application folder structure (i.e. placing the app and vendor folders outside the web root becomes easier now).

## Documentation

Full docs & tutorials are available at [simplemvcframework.com](http://simplemvcframework.com).

## Requirements

 The framework requirements are limited:

 - Apache Web Server or equivalent with mod rewrite support.
 - PHP 5.5 or greater is required.

 Although a database is not required, if a database is to be used the system is designed to work with a MySQL database. The framework can be changed to work with another database type.

## Installation

1. Download the framework.
2. Unzip the package.
3. To run composer, navigate to your project on a terminal/command prompt then run ```composer install``` that will update the vendor folder. Or use the vendor folder as is (composer is not required for this step). Upload the framework files to your server (only the contents of the ```public``` folder are required to be in your web root).
4. Open the ```app/Core/routes.php``` file with a text editor, setup your routes.
5. Open ```app/Core/Config.php```, set your base path (it is the path from the web root to the folder where the ```index.php``` resides), and database credentials (if a database is needed).
6. Edit ```.htaccess``` file (located in your public folder) and save the base path (it is the path from the web root to the folder where the ```index.php``` resides).