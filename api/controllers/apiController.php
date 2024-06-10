<?php

namespace Api\Controllers;

use Application\Core\Config;
use Application\Core\Controller;
use Application\Core\Utils\Request;
use Application\Core\Libs\Firebase\JWT\JWT;
use Application\Core\Libs\Firebase\JWT\Key;
use Api\Controllers\ErrorApiController;

class ApiController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function protectedMethod($grantedRole)
    {
        if ($grantedRole > $this->user->role) {
            (new ErrorApiController)->abort(403);
        }
    }

    public function validateJWT()
    {
        try {
            $decodedJWT = json_decode(json_encode(JWT::decode(Request::current()->request()->value('access_token'), new Key(Config::JWT_KEY, 'HS256'))), true);

            if (time() - $decodedJWT['expires_at'] >= 0) {
                (new ErrorApiController)->abort(1001);
            }

            $userRecord = $this->user->selectUserRecord($decodedJWT['username']);

            if (!$userRecord) {
                (new ErrorApiController)->abort(1001);
            }

            unset($userRecord['password']);
            unset($decodedJWT['created_at']);
            unset($decodedJWT['expires_at']);

            if (count(array_diff($decodedJWT, $userRecord)) > 0) {
                (new ErrorApiController)->abort(1001);
            }

            return $decodedJWT;
        } catch (\Exception $e) {
            (new ErrorApiController)->abort(1001);
        }
    }
}
