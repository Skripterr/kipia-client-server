<?php

namespace Application\Core\Utils;

/**
 * 
 * Class Session, working with sessions.
 *
*/
class Session
{
    /**
	 * 
     * Get id of current session.
	 * 
     */
    public static function getSessionId()
    {
        return session_id();
    }

    /**
	 * 
     * Get name of current session.
	 * 
     */
    public static function getSessionName()
    {
        return session_name();
    }

    /**
	 * 
     * Get all session elements.
	 * 
     */
    public static function getSession()
    {
        return $_SESSION;
    }

    /**
	 * 
     * Clear all existing elements of session.
	 * 
     */
    public static function clearSession()
    {
        session_destroy();
    }

    /**
	 * 
     * Get session element by name.
	 * 
	 * @param string $sessionName
	 * 
     */
    public static function getElement(string $sessionName)
    {
        // Make sure that element is exists.
        return (array_key_exists($sessionName, $_SESSION))
        ? $_SESSION[$sessionName]
        : false;
    }

    /**
	 * 
     * Edit session's element, delete first if it already exists and re-create.
	 * 
	 * @param string $sessionName
     * @param string $sessionValue
	 * 
     */
    public static function editElement(string $sessionName, string $sessionValue)
    {
        if (self::exist($sessionName))
        {
            // DK, why are we doing this.
            self::clearElement($sessionName);
        }
        return self::addElement($sessionName, $sessionValue);
    }

    /**
	 * 
     * Clear session's element by name.
	 * 
	 * @param string $sessionName
	 * 
     */
    public static function clearElement(string $sessionName)
    {
        unset($_SESSION[$sessionName]);
        return $_SESSION;
    }

    /**
	 * 
     * Create new element.
	 * 
	 * @param string $sessionName
     * @param mixed $sessionValue
	 * 
     */
    public static function addElement(string $sessionName, $sessionValue)
    {
        $_SESSION[$sessionName] = $sessionValue;
        return $_SESSION;
    }

    /**
	 * 
     * Check that session's element already exists.
	 * 
	 * @param string $sessionName
	 * 
     */
    public static function exist(string $sessionName)
    {
        return (self::getElement($sessionName))
        ? true
        : false;
    }

}

?>