<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Core\Utils\Headers;
use Application\Core\Utils\Cookies;

class authorizationController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (Cookies::existCookie('access_token')) {
            Headers::redirect('/');
        }
    }

    public function index()
    {
        $this->view->render('authorization', 'template', [
            'title' => 'Войти'
        ]);
    }
}
