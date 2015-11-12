<?php
/**
 * Language - simple language handler.
 *
 * @author Bartek Kuśmierczuk - contact@qsma.pl - http://qsma.pl
 * @author Bui Vo Quoc Bao
 * @version 2.2
 * @date November 18, 2014
 * @date updated November 12, 2015
 */

namespace Core;

/**
 * Language class to load the requested language file.
 */
class Language
{
    /**
     * Variable holds array with language.
     *
     * @var array
     */
    private $array;

    /**
     * Variable holds the default locale.
     * 
     * @var string
     */
    private static $locale = LANGUAGE_CODE;

    /**
     * Load language function.
     *
     * @param string $name name of language file
     * @param string $code optional, language code
     */
    public function load($name, $code = null)
    {
        $code = $code ?: self::getLocale();

        try {
            $this->array = self::tryToLoad($name, $code);
        } catch (\Exception $e) {
            Logger::newMessage($e);
            Logger::customErrorMsg();
        }
    }

    /**
     * Get element from language array by key.
     *
     * @param  string $value
     *
     * @return string
     */
    public function get($value)
    {
        if (!empty($this->array[$value])) {
            return $this->array[$value];
        } else {
            return $value;
        }
    }   

    /**
     * Get lang for views.
     *
     * @param  string $value this is "word" value from language file
     * @param  string $name  name of language file
     * @param  string $code  optional, language code
     *
     * @return string
     */
    public static function show($value, $name, $code = null)
    {
        $code = $code ?: self::getLocale();

        try {
            $array = self::tryToLoad($name, $code);
        } catch (\Exception $e) {
            Logger::newMessage($e);
            Logger::customErrorMsg();
        }

        if (!empty($array[$value])) {
            return $array[$value];
        } else {
            return $value;
        }
    }

    /**
     * Get the current locale.
     * 
     * @return string
     */
    public static function getLocale()
    {
        return self::$locale;
    }

    /**
     * Set locale.
     * 
     * @param string $locale
     */
    public static function setLocale($locale)
    {
        self::assertValidLocale($locale);
        self::$locale = $locale;
    }

    /**
     * Asserts that the locale is valid, throws an Exception if not.
     *
     * @param string $locale Locale to test
     *
     * @throws \InvalidArgumentException If the locale contains invalid characters
     */
    private static function assertValidLocale($locale)
    {
        if (1 !== preg_match('/^[a-z0-9@_\\.\\-]*$/i', $locale)) {
            throw new \InvalidArgumentException(sprintf('Invalid "%s" locale.', $locale));
        }
    }    

    /**
     * Load a language file.
     * 
     * @param  string $name name of language file
     * @param  string $code optional, language code
     * @return array
     */
    public static function silentlyLoad($name, $code = null)
    {
        $code = $code ?: self::getLocale();

        try {
            $content = self::tryToLoad($name, $code);
        } catch (\Exception $e) {
            Logger::newMessage($e);
            /** return an empty array if something went wrong */
            $content = [];
        }

        return $content;
    } 

    /**
     * Try to load a language file using file name and language code.
     * 
     * @param  string $name name of language file
     * @param  string $code optional, language code
     * @return array
     * @throws \Exception if the language file is not readable
     */
    private static function tryToLoad($name, $code)
    {
        $file = SMVC."app/language/$code/$name.php";
        if (is_readable($file)) {
            return include($file);
        } else {
            throw new \Exception(sprintf('File "%s" is not readable.', $file));
        }
    }
}
