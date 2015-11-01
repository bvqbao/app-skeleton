<?php
/**
 * Request Class
 *
 * @version 2.2
 * @date updated Nov 01, 2015
 */

namespace Helpers;

use Helpers\Arr;

/**
 * It contains the request information and provide methods to fetch request body.
 */
class Request
{
    /**
     * @var  $vars All of the GET, POST, PUT and DELETE variables.
     */
    protected static $vars = null;

    /**
     * @var  $putDeleteVars  All of the PUT or DELETE variables.
     */
    protected static $putDeleteVars = null;    

    /**
     * @var  $phpInput  Cache for the php://input stream.
     */
    protected static $phpInput = null;

    /**
     * Get the request body.
     *
     * @return  string  request body content.
     */
    public static function body()
    {
        static::$phpInput === null and static::$phpInput = file_get_contents("php://input");
        return static::$phpInput;
    }    

    /**
     * Return all of the GET, POST, PUT and DELETE variables.
     *
     * @return  array
     */
    public static function all()
    {
        static::$vars === null and static::readVars();
        return static::$vars;
    }

    /**
     * Get the specified GET variable.
     *
     * @param   string  $index    The index to get
     * @param   string  $default  The default value
     * @return  string|array
     */
    public static function get($index = null, $default = null)
    {
        return (func_num_args() === 0) ? $_GET : Arr::get($_GET, $index, $default);
    }

    /**
     * Fetch an item from the POST array
     *
     * @param   string  The index key
     * @param   mixed   The default value
     * @return  string|array
     */
    public static function post($index = null, $default = null)
    {
        return (func_num_args() === 0) ? $_POST : Arr::get($_POST, $index, $default);
    }

    /**
     * Fetch an item from the php://input for put arguments
     *
     * @param   string  The index key
     * @param   mixed   The default value
     * @return  string|array
     */
    public static function put($index = null, $default = null)
    {
        static::$putDeleteVars === null and static::readVars();
        return (func_num_args() === 0) ? static::$putDeleteVars : Arr::get(static::$putDeleteVars, $index, $default);
    }

    /**
     * Fetch an item from the php://input for delete arguments
     *
     * @param   string  The index key
     * @param   mixed   The default value
     * @return  string|array
     */
    public static function delete($index = null, $default = null)
    {
        static::$putDeleteVars === null and static::readVars();
        return (is_null($index) and func_num_args() === 0) ? static::$putDeleteVars : Arr::get(static::$putDeleteVars, $index, $default);
    }

    /**
     * Fetch an item from the FILE array
     *
     * @param   string  The index key
     * @param   mixed   The default value
     * @return  string|array
     */
    public static function file($index = null, $default = null)
    {
        return (func_num_args() === 0) ? $_FILES : Arr::get($_FILES, $index, $default);
    }

    /**
     * Fetch an item from either the GET, POST, PUT, or DELETE array
     *
     * @param   string  The index key
     * @param   mixed   The default value
     * @return  string|array
     */
    public static function param($index, $default = null)
    {
        static::$vars === null and static::readVars();
        return Arr::get(static::$vars, $index, $default);
    }

    /**
     * Read all variables
     *
     * @return  void
     */
    protected static function readVars()
    {
        static::$vars = array_merge($_GET, $_POST);

        if (static::isPut() or static::isDelete()) {
            static::$phpInput === null and static::$phpInput = file_get_contents("php://input");
            if (strpos(Arr::get($_SERVER, "CONTENT_TYPE"), "www-form-urlencoded") !== false) {
                static::$phpInput = urldecode(static::$phpInput);
            }
            parse_str(static::$phpInput, static::$putDeleteVars);
            static::$vars = array_merge(static::$vars, static::$putDeleteVars);
        } else {
            static::$putDeleteVars = array();
        }
    }    

    /**
     * Detect if request is Ajax.
     *
     * @static static method
     *
     * @return boolean
     */
    public static function isAjax()
    {
        if (!empty($_SERVER["HTTP_X_REQUESTED_WITH"])) {
            return strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest";
        }
        return false;
    }

    /**
     * Detect if request is POST request.
     *
     * @static static method
     *
     * @return boolean
     */
    public static function isPost()
    {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }

    /**
     * Detect if request is GET request.
     *
     * @static static method
     *
     * @return boolean
     */
    public static function isGet()
    {
        return $_SERVER["REQUEST_METHOD"] === "GET";
    }

    /**
     * Detect if request is PUT request.
     *
     * @static static method
     *
     * @return boolean
     */
    public static function isPut()
    {
        return $_SERVER["REQUEST_METHOD"] === "PUT";
    }

    /**
     * Detect if request is DELETE request.
     *
     * @static static method
     *
     * @return boolean
     */
    public static function isDelete()
    {
        return $_SERVER["REQUEST_METHOD"] === "DELETE";
    }    
}
