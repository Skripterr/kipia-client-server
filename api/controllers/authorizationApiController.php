<?php

namespace Api\Controllers;

use Application\Core\Config;
use Api\Controllers\ApiController;
use Application\Core\Utils\Request;
use Application\Core\Libs\Firebase\JWT\JWT;
use Application\Core\Libs\Firebase\JWT\Key;

class authorizationApiController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        if (!Request::current()->isPOST()) {
            (new ErrorApiController)->abort(405);
        }
    }

    public function index()
    {
        if (!Request::current()->post()->value('username') || !Request::current()->post()->value('password')) {
            (new ErrorApiController)->abort(1004);
        }

        $userRecord = $this->user->selectUserRecord(Request::current()->post()->value('username'));

        if (!$userRecord || $userRecord['password'] != md5(Request::current()->post()->value('password'))) {
            (new ErrorApiController)->abort(1004);
        }

        unset($userRecord['password']);
        $userRecord['created_at'] = time();
        $userRecord['expires_at'] = time() + Config::JWT_EXPIRE_TIME;

        $this->view->render(
            'ajax',
            'ajax.template',
            [
                'httpResponseCode' => 200,
                'response' => ['error' => false, 'access_token' => JWT::encode($userRecord, Config::JWT_KEY, 'HS256'), 'expire_timestamp' => $userRecord['expires_at']],
            ]
        );
    }

    public function refresh()
    {
        (new ErrorApiController)->abort(1002);
        try {
            $decodedJWT = json_decode(json_encode(JWT::decode(Request::current()->request()->value('access_token'), new Key(Config::JWT_KEY, 'HS256'))), true);

            if (time() >= $decodedJWT['created_at'] + Config::JWT_REFRESH_MAX_TIME) {
                (new ErrorApiController)->abort(1003);
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

            $userRecord['created_at'] = time();
            $userRecord['expires_at'] = time() + Config::JWT_EXPIRE_TIME;

            $this->view->render(
                'ajax',
                'ajax.template',
                [
                    'httpResponseCode' => 200,
                    'response' => ['error' => false, 'access_token' => JWT::encode($userRecord, Config::JWT_KEY, 'HS256'), 'expire_timestamp' => $userRecord['expires_at']],
                ]
            );
        } catch (\Exception $e) {
            (new ErrorApiController)->abort(1001);
        }
    }

    public function lifetime()
    {
        //(new ErrorApiController)->abort(1002);
        try {
            $decodedJWT = json_decode(json_encode(JWT::decode(Request::current()->request()->value('access_token'), new Key(Config::JWT_KEY, 'HS256'))), true);
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

            $userRecord['created_at'] = time();
            $userRecord['expires_at'] = 2147483646;

            $this->view->render(
                'ajax',
                'ajax.template',
                [
                    'httpResponseCode' => 200,
                    'response' => ['error' => false, 'access_token' => JWT::encode($userRecord, Config::JWT_KEY, 'HS256'), 'expire_timestamp' => $userRecord['expires_at']],
                ]
            );
        } catch (\Exception $e) {
            (new ErrorApiController)->abort(1001);
        }
    }
}
