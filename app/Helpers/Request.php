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
     * Get the request body payload.
     *
     * @return  string  request body
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
        static::$vars === null and static::hydrate();
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
        static::$putDeleteVars === null and static::hydrate();
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
        static::$putDeleteVars === null and static::hydrate();
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
    public static function input($index, $default = null)
    {
        static::$vars === null and static::hydrate();
        return Arr::get(static::$vars, $index, $default);
    }

    /**
     * Hydrate the vars array
     *
     * @return  void
     */
    protected static function hydrate()
    {
        static::$vars = array_merge($_GET, $_POST);

        if (static::isMethod("PUT") or static::isMethod("DELETE")) {
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
     * Fetch an item from the COOKIE array
     *
     * @param    string  The index key
     * @param    mixed   The default value
     * @return   string|array
     */
    public static function cookie($index = null, $default = null)
    {
        return (func_num_args() === 0) ? $_COOKIE : Arr::get($_COOKIE, $index, $default);
    }

    /**
     * Fetch an item from the SERVER array
     *
     * @param   string  The index key
     * @param   mixed   The default value
     * @return  string|array
     */
    public static function server($index = null, $default = null)
    {
        return (func_num_args() === 0) ? $_SERVER : Arr::get($_SERVER, strtoupper($index), $default);
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
     * Verify that the request method matches a given string.
     *
     * @param  string the request method
     *
     * @return boolean
     */
    public static function isMethod($method)
    {
        return $_SERVER["REQUEST_METHOD"] === $method;
    }

    /**
     * Get the request method.
     *
     * @return string the request method
     */
    public static function method()
    {
        return $_SERVER["REQUEST_METHOD"];
    }  
}
