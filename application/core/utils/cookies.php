<?php

namespace Application\Core\Utils;

/**
 * 
 * Class Cookies, working with cookies.
 *
*/
class Cookies
{
    /**
	 * 
     * Get all cookies with names and values.
	 * 
     */
    public static function getCookies()
    {
        return $_COOKIE;
    }

    /**
	 * 
     * Clear all existing cookies.
	 * 
     */
    public static function clearCookies()
    {
        if ($_COOKIE != [])
        {
            foreach($_COOKIE as $cookieName => $cookieValue)
            {
                self::clearCookie($cookieName);
            }
        }
    }

    /**
	 * 
     * Get cookie by name.
	 * 
	 * @param string $cookieName
	 * 
     */
    public static function getCookie(string $cookieName)
    {
        return (array_key_exists($cookieName, $_COOKIE))
        ? $_COOKIE[$cookieName]
        : false;
    }

    /**
	 * 
     * Edit the cookie, delete first if it already exists and re-create.
	 * 
	 * @param string $cookieName
     * @param string $cookieValue
     * @param int $lifetime 3600
	 * 
     */
    public static function editCookie(string $cookieName, string $cookieValue, int $lifetime = 3600)
    {
        if (self::existCookie($cookieName))
        {
            self::clearCookie($cookieName);
        }
        return self::addCookie($cookieName, $cookieValue, $lifetime);
    }

    /**
	 * 
     * Clear cookie by name.
	 * 
	 * @param string $cookieName
	 * 
     */
    public static function clearCookie(string $cookieName)
    {
        unset($_COOKIE[$cookieName]);
        return !self::addCookie($cookieName, '', - 3600);
    }

    /**
	 * 
     * Create new cookie.
	 * 
	 * @param string $cookieName
     * @param string $cookieValue
     * @param int $lifetime 3600
	 * 
     */
    public static function addCookie(string $cookieName, string $cookieValue, int $lifetime = 3600)
    {
        setcookie($cookieName, $cookieValue, time() + $lifetime);
        return self::existCookie($cookieName);
    }

    /**
	 * 
     * Check that cookie already exists.
	 * 
	 * @param string $cookieName
	 * 
     */
    public static function existCookie(string $cookieName)
    {
        return (self::getCookie($cookieName))
        ? true
        : false;
    }

}

?>