<?php

namespace Application\Core;

/**
 * 
 * Config file, all constants can be edit here.
 *
*/
class Config
{
    const DEBUG = true;

    const DB_HOST = 'localhost';
    const CHARSET = 'utf8';
    const DB_PREFIX = '';
    const DB_USER = 'root';
    const DB_PASSWORD = '';
    const DB_NAME = 'bakery';


    const PROJECT_TITLE = 'KIP&A Учет ' . (self::DEBUG ? '(dev mode)' : '');

    const JWT_KEY = 'kipiaproject';
    const JWT_EXPIRE_TIME = 86000 * 3;
    const JWT_REFRESH_MAX_TIME = 86000 * 7;
}

?>
