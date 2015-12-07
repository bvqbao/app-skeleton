<?php

use Core\Container;

if (!function_exists('container')) {
    /**
     * Get the available container instance.
     *
     * @param  string  $key
     * @return mixed
     */
    function container($key = null)
    {
        if (is_null($key)) {
            return Container::getInstance();
        }

        return Container::getInstance()->get($key);
    }
}

if (!function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string  $path
     * @return string
     */
    function app_path($path = '')
    {
        return container('path') . ltrim($path, DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('sys_path')) {
    /**
     * Get the path to the system folder.
     *
     * @param  string  $path
     * @return string
     */
    function sys_path($path = '')
    {
        return container('path.system') . ltrim($path, DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     */
    function base_path($path = '')
    {
        return container('path.base') . ltrim($path, DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @return string
     */
    function config_path($path = '')
    {
        return container('path.config') . ltrim($path, DIRECTORY_SEPARATOR);
    }
}

if (!function_exists('url')) {
    /**
     * Create a root-relative url.
     *
     * @param string $path
     * @param array $queryParams Optional query string parameters
     * @return string
     */
    function url($path = '', array $queryParams = [])
    {
        $uri = container('request')->getUri();
        $url = $uri->getBasePath() . '/' . ltrim($path, '/');

        if ($queryParams) {
            $url .= '?' . http_build_query($queryParams);
        }

        return $url;
    }
}

if (!function_exists('html_errors')) {
    /**
     * Put errors inside divs.
     *
     * @param  array  $errors
     * @param  string $class
     * @return string
     */
    function html_errors($errors, $class = 'alert alert-danger')
    {
        if (is_array($errors)) {
            $row = '';
            foreach ($errors as $error) {
                $row .= "<div class='$class'>$error</div>";
            }
            return $row;
        } else {
            if (isset($errors)) {
                return "<div class='$class'>$errors</div>";
            }
        }
    }
}

if (!function_exists('slug')) {
    /**
     * This function converts an url segment to an safe one, for example:
     * `test name @132` will be converted to `test-name--123`
     * Basicly it works by replacing every character that isn't an letter or an number to an dash sign
     * It will also return all letters in lowercase.
     *
     * @param $slug - The url slug to convert
     *
     * @return mixed|string
     */
    function slug($slug)
    {
        setlocale(LC_ALL, "en_US.utf8");

        $slug = preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $slug));

        $slug = htmlentities($slug, ENT_QUOTES, 'UTF-8');

        $pattern = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $slug = preg_replace($pattern, '$1', $slug);

        $slug = html_entity_decode($slug, ENT_QUOTES, 'UTF-8');

        $pattern = '~[^0-9a-z]+~i';
        $slug = preg_replace($pattern, '-', $slug);

        return strtolower(trim($slug, '-'));
    }
}
