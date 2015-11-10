<?php
/**
 * Config - system settings.
 *
 * @author David Carr - dave@daveismyname.com
 * @author Edwin Hoksberg - info@edwinhoksberg.nl
 * @author Bui Vo Quoc Bao - bvqbao@gmail.com
 * @version 2.2
 * @date June 27, 2014
 * @date updated Nov 05, 2015
 */

/**
 * Turn on output buffering.
 */
ob_start();

/**
 * Define relative base path (the root-relative path to 
 *     the folder in which the index.php file resides)
 */
define('DIR', '/');

/**
 * Set default controller and method for legacy calls.
 */
define('DEFAULT_CONTROLLER', 'welcome');
define('DEFAULT_METHOD', 'index');

/**
 * Set the default template.
 */
define('TEMPLATE', 'default');

/**
 * Set a default language.
 */
define('LANGUAGE_CODE', 'vi');

//database details ONLY NEEDED IF USING A DATABASE

/**
 * Database engine default is mysql.
 */
define('DB_TYPE', 'mysql');

/**
 * Database host default is localhost.
 */
define('DB_HOST', 'localhost');

/**
 * Database name.
 */
define('DB_NAME', 'dbname');

/**
 * Database username.
 */
define('DB_USER', 'dbuser');

/**
 * Database password.
 */
define('DB_PASS', 'dbpass');

/**
 * PREFER to be used in database calls default is smvc_
 */
define('PREFIX', 'smvc_');

/**
 * Set prefix for sessions.
 */
define('SESSION_PREFIX', 'smvc_');

/**
 * Optional create a constant for the name of the site.
 */
define('SITETITLE', 'V2.2');

/**
 * Optional set a site email address.
 */
define('SITEEMAIL', 'bvqbao@gmail.com');

/**
 * Turn on custom error handling.
 */
set_exception_handler('Core\Logger::ExceptionHandler');
set_error_handler('Core\Logger::ErrorHandler');

/**
 * Set timezone.
 */
date_default_timezone_set('Asia/Ho_Chi_Minh');

/**
 * Start sessions.
 */
Helpers\Session::init();

/**
 * Configure the database and boot Eloquent
 */
$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection([
    'driver'    => DB_TYPE,
    'host'      => DB_HOST,
    'database'  => DB_NAME,
    'username'  => DB_USER,
    'password'  => DB_PASS,
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => PREFIX
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();