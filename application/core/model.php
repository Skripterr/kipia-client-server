<?php

namespace Application\Core;

use Application\Core\Database;

/**
 * 
 * Class Model, interact with database.
 *
*/
class Model
{
     /**
     * Database class
     *
     * @var Application\Core\Database
     */
	public $database;

	/**
	 * 
     * Initialize database object.
	 * 
     */
	function __construct()
	{
          $this->database = new Database();
	}
}

?>