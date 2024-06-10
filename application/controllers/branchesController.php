<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Core\Utils\Headers;
use Application\Core\Utils\Cookies;

class branchesController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!Cookies::existCookie('access_token')) {
            Headers::redirect('/authorization');
        }
    }

    public function index()
    {
        self::protectedMethod(5);
        $this->view->render('branches', 'template', [
            'title' => 'Филиалы',
        ]);
    }
}
