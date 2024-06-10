<?php

namespace Application\Core\Utils;

/**
 * 
 * Class Headers, working with income and outcome headers.
 *
*/
class Headers
{

    /**
     * Array with allowed mime types.
     *
     * @var array
     */
    protected static $mimeTypes = [
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'zip' => 'application/zip',
        'exe' => 'application/exe',
    ];

    /**
	 * 
     * Get all income headers.
     * 
     */
    public static function getHeaders()
    {
        return getallheaders();
    }

    /**
	 * 
     * Get value by header's name.
	 * 
	 * @param string $headerName
     * 
     */
    public static function getValue(string $headerName)
    {
        foreach(self::getHeaders() as $name => $value)
        {
            if ($name == $headerName)
                return $value;
        }
        return false;
    }

    /**
	 * 
     * Generage headers to outcome.
	 * 
	 * @param int $httpResponseCode
	 * @param string $contentType html
	 * @param string $contentDirection  
     * 
     */
    public static function createHeaders(int $httpResponseCode, string $contentType = 'html', array $customHeaders = [])
    {
        if (array_key_exists($contentType, self::$mimeTypes))
        {
            header('Content-Type: '. self::$mimeTypes[$contentType]);
        }
        
        foreach ($customHeaders as $customHeader)
        {
            header($customHeader);
        }

        if ($httpResponseCode >= 200 && $httpResponseCode <= 520)
        {
            http_response_code($httpResponseCode);
        }
    }

    /**
	 * 
     * Redirects to specified direction.
	 * 
	 * @param string $direction
	 * @param int $httpResponseCode 302
	 * @param int delay 0
     * 
     */
    public static function redirect(string $direction, int $httpResponseCode = 302, int $delay = 0)
    {
        // Check that specified path on our server.
		if (filter_var($direction, FILTER_VALIDATE_URL) === false) 
		{
            if ($delay == 0)
                header('Location: ' . $direction, true, $httpResponseCode);
            else
                header('Refresh:' . $delay . '; url=' . $direction, true, $httpResponseCode);
		}
		exit();
    }

    /**
	 * 
     * Get referer direction.
     * 
     */
    public static function getReferer()
    {
        return self::getValue('Referer');
    }

    /**
	 * 
     * Get authorization header's value.
     * 
     */
    public static function getAuthorizationToken()
    {
        return self::getValue('Authorization');
    }
}

?>