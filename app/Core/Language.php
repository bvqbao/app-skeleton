<?php
/**
 * Language - simple language handler.
 *
 * @author Bartek Kuśmierczuk - contact@qsma.pl - http://qsma.pl
 * @version 2.2
 * @date November 18, 2014
 * @date updated November 12, 2015
 */

namespace Core;

use Core\Error;

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
     * @param string $name
     * @param string $code
     */
    public function load($name, $code = null)
    {
        /** if there is no specified language, use the default one */
        $code = $code ?: self::getLocale() ;

        /** lang file */
        $file = SMVC."app/language/$code/$name.php";

        /** check if is readable */
        if (is_readable($file)) {
            /** require file */
            $this->array = include($file);
        } else {
            /** display error */
            echo Error::display("Could not load language file '$code/$name.php'");
            die;
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
     * @param  string $name  name of file with language
     * @param  string $code  optional, language code
     *
     * @return string
     */
    public static function show($value, $name, $code = null)
    {
        /** if there is no specified language, use the default one */
        $code = $code ?: self::getLocale() ;

        /** lang file */
        $file = SMVC."app/language/$code/$name.php";

        /** check if is readable */
        if (is_readable($file)) {
            /** require file */
            $array = include($file);
        } else {
            /** display error */
            echo Error::display("Could not load language file '$code/$name.php'");
            die;
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
        self::$locale = $locale;
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
        /** if there is no specified language, use the default one */
        $code = $code ?: self::getLocale() ;

        $content = [];

        /** lang file */
        $file = SMVC."app/language/$code/$name.php";

        /** check if is readable */
        if (is_readable($file)) {
            /** require file */
            $content = include($file);
        }

        return $content;
    } 
}
