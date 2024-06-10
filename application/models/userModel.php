<?php

namespace Application\Models;

use Application\Core\Model;

class UserModel extends Model
{
    public $id;
    public $username;
    public $passwordHashedSalt;
    public $firstName;
    public $middleName;
    public $lastName;
    public $role;
    public $branch;

    public function __construct()
    {
        parent::__construct();
    }

    public function selectUserRecord($username)
    {
        $userRecord = $this->database->getRow("SELECT * FROM `users` WHERE `username` =  ?", [$username]);

        if ($userRecord) {
            $this->id = $userRecord['id'];
            $this->username = $userRecord['username'];
            $this->passwordHashedSalt = $userRecord['password'];

            $this->firstName = $userRecord['first_name'];
            $this->middleName = $userRecord['middle_name'];
            $this->lastName = $userRecord['last_name'];
            $this->role = $userRecord['role'];
            $this->branch = $userRecord['branch'];
        }

        return $userRecord;
    }
}
