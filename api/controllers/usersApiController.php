<?php

namespace Api\Controllers;

use Api\Controllers\ApiController;
use Application\Core\Utils\Request;
use Application\Core\Utils\InputFilter;
use Application\Models\UsersModel;

class usersApiController extends ApiController
{
    public $users;

    public function __construct()
    {
        parent::__construct();

        if (!Request::current()->isPOST()) {
            (new ErrorApiController)->abort(405);
        }

        self::validateJWT();
        $this->users = new UsersModel;
    }

    public function me()
    {
        $this->view->render(
            'ajax',
            'ajax.template',
            [
                'httpResponseCode' => 200,
                'response' => ['error' => false, 'data' => [
                    'username' => $this->user->username,
                    'first_name' => $this->user->firstName,
                    'middle_name' => $this->user->middleName,
                    'last_name' => $this->user->lastName,
                    'role' => $this->user->role,
                    'branch' => $this->user->branch
                ]],
            ]
        );
    }

    public function add()
    {
        self::protectedMethod(6);
        $username = InputFilter::filterString(Request::current()->post()->value('username'));
        $firstName = InputFilter::filterString(Request::current()->post()->value('first_name'));
        $middleName = InputFilter::filterString(Request::current()->post()->value('middle_name'));
        $lastName = InputFilter::filterString(Request::current()->post()->value('last_name'));
        $role = InputFilter::filterString(Request::current()->post()->value('role'));
        $branch = InputFilter::filterString(Request::current()->post()->value('branch'));
        $password = InputFilter::filterString(Request::current()->post()->value('password'));

        if (
            !InputFilter::checkNotEmpty($username) || !InputFilter::checkNotEmpty($firstName) || !InputFilter::checkNotEmpty($middleName) || !InputFilter::checkNotEmpty($lastName)
            || !InputFilter::checkNotEmpty($role) || !InputFilter::checkNotEmpty($branch) || !InputFilter::checkNotEmpty($password)
        ) {
            (new ErrorApiController)->abort(1005);
        }

        if ($this->users->selectUsers(['username' => $username])) {
            (new ErrorApiController)->abort(1006);
        }

        if ($this->users->addUser($username, md5($password), $firstName, $middleName, $lastName, $role, $branch) == 0) {
            (new ErrorApiController)->abort(511);
        }

        $this->view->render(
            'ajax',
            'ajax.template',
            [
                'httpResponseCode' => 200,
                'response' => ['error' => false],
            ]
        );
    }

    public function delete()
    {
        self::protectedMethod(6);
        $id = InputFilter::filterString(Request::current()->post()->value('id'));

        if (!InputFilter::checkNotEmpty($id)) {
            (new ErrorApiController)->abort(1005);
        }

        if (!$this->users->deleteUser(['id' => $id])) {
            (new ErrorApiController)->abort(1007);
        }

        $this->view->render(
            'ajax',
            'ajax.template',
            [
                'httpResponseCode' => 200,
                'response' => ['error' => false]
            ]
        );
    }

    public function edit()
    {
        self::protectedMethod(6);
        $id = InputFilter::filterString(Request::current()->post()->value('id'));
        $username = InputFilter::filterString(Request::current()->post()->value('username'));
        $firstName = InputFilter::filterString(Request::current()->post()->value('first_name'));
        $middleName = InputFilter::filterString(Request::current()->post()->value('middle_name'));
        $lastName = InputFilter::filterString(Request::current()->post()->value('last_name'));
        $role = InputFilter::filterString(Request::current()->post()->value('role'));
        $branch = InputFilter::filterString(Request::current()->post()->value('branch'));

        if (
            !InputFilter::checkNotEmpty($id) || !InputFilter::checkNotEmpty($username) || !InputFilter::checkNotEmpty($firstName) ||
            !InputFilter::checkNotEmpty($middleName) || !InputFilter::checkNotEmpty($lastName) || !InputFilter::checkNotEmpty($role) || !InputFilter::checkNotEmpty($branch)
        ) {
            (new ErrorApiController)->abort(1005);
        }

        if (!$this->users->selectUsers(['username' => $username])) {
            (new ErrorApiController)->abort(1007);
        }

        if ($this->users->updateUser(
            ['username' => $username, 'first_name' => $firstName, 'middle_name' => $middleName, 'last_name' => $lastName, 'role' => $role, 'branch' => $branch],
            ['id' => $id]
        ) == 0) {
            (new ErrorApiController)->abort(511);
        }

        $this->view->render(
            'ajax',
            'ajax.template',
            [
                'httpResponseCode' => 200,
                'response' => ['error' => false],
            ]
        );
    }

    public function get()
    {
        self::protectedMethod(4);
        $databaseRequest = [];

        $branch = InputFilter::filterString(Request::current()->post()->value('branch'));

        if (InputFilter::checkNotEmpty($branch)) {
            $databaseRequest['branch'] = $branch;
        }

        $record = $this->users->selectUsers($databaseRequest);

        if (!$record) {
            (new ErrorApiController)->abort(1007);
        }

        foreach ($record as &$row) {
            unset($row['password']);
        }

        $this->view->render(
            'ajax',
            'ajax.template',
            [
                'httpResponseCode' => 200,
                'response' => ['error' => false, 'data' => $record]
            ]
        );
    }
}
