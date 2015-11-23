<?php
/**
 * View - load layout pages.
 */

namespace Core;

use Helpers\Arr;

/**
 * View class to load layout and views files.
 */
class View
{
    /**
     * Namespace delimiter value.
     *
     * @var string
     */
    const NAMESPACE_DELIMITER = '::';

    /**
     * The default namespace.
     *
     * @var string
     */
    const DEFAULT_NAMESPACE = 'views';

    /**
     * Array of mappings from namesapce to path.
     * 
     * @var array
     */
    protected static $nsPaths = [
        "views"   => "app/views/",
        "layouts" => "app/views/layouts/"
    ];

    /**
     * Array of shared data.
     * 
     * @var array
     */
    protected static $shared = [];

    /**
     * Data passed to view.
     * 
     * @var array
     */
    protected $data = [];

    /**
     * The view name.
     * 
     * @var string
     */
    protected $view;

    /**
     * The path of the view file.
     * 
     * @var string
     */
    protected $path;

    /**
     * Create a new View instance.
     * 
     * @param string $view
     * @param string $path
     * @param array  $data
     */
    protected function __construct($view, $path, array $data = [])
    {
        $this->view = $view;
        $this->path = $path;
        $this->data = $data;
    }

    /**
     * Create a new View instance.
     * 
     * @param  string $view
     * @param  array  $data
     * @return \Core\View
     */
    public static function make($view, array $data = [])
    {
        return new static($view, static::find($view), $data);
    }

    /**
     * Get the fully qualified location of the view.
     * 
     * @param  string $view
     * @return string
     */
    protected static function find($name)
    {      
        if (static::hasNamespace($name = trim($name))) {
            list($namespace, $view) = static::getNamespaceSegments($name);
            return static::findViewInPath($view, static::$nsPaths[$namespace]);
        }

        return static::findViewInPath($name, 
            static::$nsPaths[static::DEFAULT_NAMESPACE]);        
    }

    /**
     * Extract the namespace from the view name.
     *
     * @param  string  $name
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected static function getNamespaceSegments($name)
    {
        $segments = explode(static::NAMESPACE_DELIMITER, $name);

        if (count($segments) != 2) {
            throw new \InvalidArgumentException("View [$name] has an invalid name.");
        }

        if (! isset(static::$nsPaths[$segments[0]])) {
            throw new \InvalidArgumentException("No path defined for [{$segments[0]}].");
        }

        return $segments;
    }    

    /**
     * Return whether or not the view name contains a namespace.
     *
     * @param  string  $name
     * @return bool
     */
    protected static function hasNamespace($name)
    {
        return strpos($name, static::NAMESPACE_DELIMITER) > 0;
    }    

    /**
     * Find the given view in the given path.
     * 
     * @param  string $name
     * @param  string $path
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected static function findViewInPath($name, $path)
    {
        $viewPath = base_path($path.str_replace('.', '/', $name).'.php');
        if (! file_exists($viewPath)) {
            throw new \InvalidArgumentException("View [$name] not found.");
        }

        return $viewPath;
    }

    /**
     * Set a mapping from namespace to path.
     * 
     * @param string $namespace
     * @param string $path
     */
    public static function setNamespace($namespace, $path)
    {
        static::$nsPaths[$namespace] = $path;
    }   

    /**
     * Get the evaluated content of the view.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->evaluatePath($this->path, $this->gatherData());
    }

    /**
     * Get the evaluated content of the view at the given path.
     * 
     * @param  string $path
     * @param  array  $data
     * @return string
     */
    private function evaluatePath($path, array $data = [])
    {
        $obLevel = ob_get_level();

        ob_start();

        extract($data);

        try {
            include($path);
        } catch (\Exception $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }
            throw $e;
        }

        return ltrim(ob_get_clean());
    }    

    /**
     * Get the data bound to the view instance.
     *
     * @return array
     */
    protected function gatherData()
    {
        $data = array_merge(static::$shared, $this->data);
        foreach ($data as $key => $value) {
            if ($value instanceof View) {
                $data[$key] = $value->getContent();
            }
        }

        return $data;
    }

    /**
     * Add a view instance to the view data.
     *
     * @param  string  $key
     * @param  string  $view
     * @param  array   $data
     * @return $this
     */
    public function nest($key, $view, array $data = [])
    {
        return $this->with($key, static::make($view, $data));
    }    

    /**
     * Add a piece of data to the view.
     *
     * @param  string|array  $key
     * @param  mixed   $value
     * @return $this
     */
    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        
        return $this;
    } 

    /**
     * Add a piece of shared data.
     *
     * @param  array|string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public static function share($key, $value = null)
    {
        if (! is_array($key)) {
            return static::$shared[$key] = $value;
        }
        foreach ($key as $innerKey => $innerValue) {
            static::share($innerKey, $innerValue);
        }
    }    

    /**
     * Get an item from the shared data.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public static function shared($key, $default = null)
    {
        return Arr::get(static::$shared, $key, $default);
    }

    /**
     * Get all of the shared data.
     *
     * @return array
     */
    public static function getShared()
    {
        return static::$shared;
    }    

    /**
     * Get the name of the view.
     *
     * @return string
     */
    public function getName()
    {
        return $this->view;
    }

    /**
     * Get the array of view data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the path to the view file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the path to the view.
     *
     * @param  string  $path
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }    

    /**
     * Get the string content of the view.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getContent();
    }    

    /**
     * Get a piece of data from the view.
     *
     * @param  string  $key
     * @return mixed
     */
    public function &__get($key)
    {
        return $this->data[$key];
    }

    /**
     * Set a piece of data on the view.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->with($key, $value);
    }

    /**
     * Check if a piece of data is bound to the view.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Remove a piece of bound data from the view.
     *
     * @param  string  $key
     * @return bool
     */
    public function __unset($key)
    {
        unset($this->data[$key]);
    }    
}
