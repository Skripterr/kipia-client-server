<?php

namespace Application\Core\Utils;

/**
 * 
 * Class Request, working with super-global variables.
 *
*/
class Request
{
    private static $instance;
    
    /**
     * Array with elements of super-global variable.
     *
     * @var array
     */
    public $variable = [];

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {}

    /**
     * Get the singleton instance of Request.
     *
     * @return Request
     */
    public static function current()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Request();
        }
        return self::$instance;
    }

    /**
     * $_SERVER super-global variable.
     */
    public function server()
    {
        $this->variable = $_SERVER;
        return $this;
    }

    /**
     * $_REQUEST super-global variable.
     */
    public function request()
    {
        $this->variable = $_REQUEST;
        return $this;
    }

    /**
     * $_POST super-global variable.
     */
    public function post()
    {
        $this->variable = $_POST;
        return $this;
    }

    /**
     * $_GET super-global variable.
     */
    public function get()
    {
        $this->variable = $_GET;
        return $this;
    }

    /**
     * Get all elements of current variable.
     */
    public function all()
    {
        return $this->variable;
    }

    /**
     * Get value of element by name.
     *
     * @param string $elementName
     */
    public function value(string $elementName)
    {
        if (array_key_exists($elementName, $this->variable)) {
            return $this->variable[$elementName];
        }
        return false;
    }

    /**
     * Check that element exists.
     *
     * @param string $elementName
     */
    public function exist(string $elementName)
    {
        return (array_key_exists($elementName, $this->variable) 
        ? true 
        : false);
    }

    /**
     * Check that current request method is POST.
     */
    public function isPOST()
    {
        return ($this->server()->value('REQUEST_METHOD') === 'POST') 
        ? true 
        : false;
    }

    /**
     * Check that current request method is GET.
     */
    public function isGET()
    {
        return ($this->server()->value('REQUEST_METHOD') === 'GET') 
        ? true 
        : false;
    }

    /**
	 * 
     * Returns controller.
	 * 
     */
    public function getController()
    {
        $segments = self::segments();
        return (array_key_exists(0, $segments)) ? $segments[0] : '';
    }

    /**
	 * 
     * Returns action / api endpoint.
	 * 
     */
    public function getAction()
    {
        $segments = self::segments();
        return (array_key_exists(1, $segments)) ? $segments[1] : '';
    }

    /**
	 * 
     * Returns api method.
	 * 
     */
    public function getMethod()
    {
        $segments = self::segments();
        return (array_key_exists(2, $segments)) ? $segments[2] : '';
    }

    /**
	 * 
     * Taking a routes segments from uri.
	 * 
     */
    private static function segments()
    {
        $routes = explode('/', strtok(Request::current()->server()->value('REQUEST_URI'), '?'));
		// Unset empty element, hes forever empty.
        array_shift($routes);
        return $routes;
    }
}

?>