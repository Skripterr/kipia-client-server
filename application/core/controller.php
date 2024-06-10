<?php

namespace Application\Core;

use Application\Models\userModel;
use Application\Controllers\ErrorController;
use Application\Core\Libs\Firebase\JWT\JWT;
use Application\Core\Libs\Firebase\JWT\Key;
use Application\Core\Utils\Cookies;

/**
 * 
 * Class Controller, interact with controllers.
 *
 */
class Controller
{
	/**
	 * View object
	 *
	 * @var Application\Core\View
	 */
	public $view;

	/**
	 * User model;
	 *
	 * @var Application\Models\userModel
	 */
	public $user;

	/**
	 * 
	 * Initialize view class.
	 * 
	 */
	function __construct()
	{
		$this->view = new View();
		$this->user = new userModel();

		self::validate();
	}

	public function protectedMethod($grantedRole)
	{
		if ($grantedRole > $this->user->role) {
			(new ErrorController)->abort(403);
		}
	}

	public function validate()
	{
		try {
			$decodedJWT = json_decode(json_encode(JWT::decode(Cookies::getCookie('access_token'), new Key(Config::JWT_KEY, 'HS256'))), true);

			if (time() - $decodedJWT['expires_at'] >= 0) {
				return;
			}

			$this->user->selectUserRecord($decodedJWT['username']);
		} catch (\Exception $e) {
			return;
		}
	}
}
