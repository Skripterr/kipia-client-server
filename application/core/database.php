<?php

namespace Application\Core;

use Application\Core\Config;
use Application\Controllers\ErrorController;
use Exception;

class Database extends Config
{
    static private $db;

    protected static $instance = null;

    public function __construct()
    {
        try
            {
            if (self::$instance === null)
            {
                self::$db = new \PDO('mysql:host=' . parent::DB_HOST . ';dbname=' . parent::DB_NAME, parent::DB_USER, parent::DB_PASSWORD, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING, //\PDO::ERRMODE_EXCEPTION, 
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, 
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . parent::CHARSET
                ]);
            }
            return self::$instance;
        }
        catch(\Exception $e)
        {
            (new ErrorController)->abort(510);
        }
    }

    static public function lastInsertId()
    {
        return self::$db->lastInsertId();
    }

    public static function run($query, $args = [])
    {
        try
        {
            if (self::$db === null)
            {
                throw new Exception("Database instance is empty.");
            }

            if (!$args)
            {
                return self::$db->query($query);
            }
            $stmt = self::$db->prepare($query);
            $stmt->execute($args);
            return $stmt;
        }
        catch(Exception $e)
        {
            (new ErrorController)->abort(511);
        }
    }

    public static function getRow($query, $args = [])
    {
        return self::run($query, $args)->fetch();
    }

    public static function getRows($query, $args = [])
    {
        return self::run($query, $args)->fetchAll();
    }

    public static function getValue($query, $args = [])
    {
        $result = self::getRow($query, $args);
        if (!empty($result))
        {
            $result = array_shift($result);
        }
        return $result;
    }

    public static function getColumn($query, $args = [])
    {
        return self::run($query, $args)->fetchAll(\PDO::FETCH_COLUMN);
    }

    public static function sql($query, $args = [])
    {
        return self::run($query, $args);
    }

    public static function formQuery($sql, $conditions) 
    {
        $sql .= " WHERE ";
        $where = [];

        foreach ($conditions as $column => $value) 
        {
            if (strpos($column, "<") !== false) 
            {
                $column = str_replace("<", "", $column);
                $formedConditions[$column] = $value;
                $operator = "<";
            } 
            elseif (strpos($column, ">") !== false) 
            {
                $column = str_replace(">", "", $column);
                $formedConditions[$column] = $value;
                $operator = ">";
            } 
            elseif (strpos($column, ">=") !== false) 
            {
                $column = str_replace(">=", "", $column);
                $formedConditions[$column] = $value;
                $operator = ">=";
            } 
            elseif (strpos($column, "=<") !== false) 
            {
                $column = str_replace("=<", "", $column);
                $formedConditions[$column] = $value;
                $operator = "=<";
            } 
            else 
            {
                $column = str_replace("=", "", $column);
                $formedConditions[$column] = $value;
                $operator = "=";
            }

            $where[] = "$column $operator :$column";
        }

        $sql .= implode(' AND ', $where);

        return [$sql, $formedConditions];
    }
}

