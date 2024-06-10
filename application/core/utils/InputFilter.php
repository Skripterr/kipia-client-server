<?php

namespace Application\Core\Utils;

/**
 * 
 * Class for Filter data.
 *
*/
class InputFilter 
{

    public static function filterArray($input) 
    {
        $filteredArray = array_filter($input, function($value) {
            return self::checkNotEmpty(self::filterString($value));
        });
        return $filteredArray;
    }

    public static function filterString($input) 
    {
        $filteredInput = trim($input);
        $filteredInput = stripslashes($filteredInput);
        //$filteredInput = htmlspecialchars($filteredInput);
        return (string) $filteredInput;
    }
    
    public static function filterInteger($input) 
    {
        $filteredInput = filter_var(self::filterString($input), FILTER_SANITIZE_NUMBER_INT);
        return $filteredInput;
    }
    
    public static function filterFloat($input) 
    {
        $filteredInput = filter_var(self::filterString($input), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        return (float) $filteredInput;
    }
    
    public static function filterEmail($input) 
    {
        $filteredInput = filter_var(self::filterString($input), FILTER_SANITIZE_EMAIL);
        return (string) $filteredInput;
    }
    
    public static function filterURL($input) 
    {
        $filteredInput = filter_var(self::filterString($input), FILTER_SANITIZE_URL);
        return (string) $filteredInput;
    }
    
    public static function checkNotEmpty($input) 
    {
        if (is_numeric($input) && $input == 0) 
        {
            return true;
        } 
        else if (empty($input) || $input === null) 
        {
            return false;
        } 
        
        return true;
    }
}

?>