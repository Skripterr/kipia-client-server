<?php

namespace Application\Models;

use Application\Core\Model;

class UsersModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addUser($username, $password, $firstName, $middleName, $lastName, $role, $branch)
    {
        return $this->database->sql(
            "INSERT INTO `users` (`username`, `password`, `first_name`, `middle_name`, `last_name`, `role`, `branch`) 
            VALUES (:username, :password, :first_name, :middle_name, :last_name, :role, :branch)",
            ['username' => $username, 'password' => $password, 'first_name' => $firstName, 'middle_name' => $middleName, 'last_name' => $lastName, 'role' => $role, 'branch' => $branch]
        )->rowCount();
    }

    public function updateUser($data, $conditions)
    {
        $sql = "UPDATE `users` SET ";
        $updates = [];
        $formedConditions = [];

        foreach ($data as $column => $value) {
            $updates[] = "$column = :$column";
        }

        $sql .= implode(', ', $updates);

        if (!empty($conditions)) {
            [$sql, $formedConditions] = $this->database->formQuery($sql, $conditions);
        }

        return $this->database->sql($sql, array_merge($data, $formedConditions))->rowCount();
    }

    public function selectUsers($conditions)
    {
        $sql = "SELECT * from `users`";
        $formedConditions = [];

        if (!empty($conditions)) {
            [$sql, $formedConditions] = $this->database->formQuery($sql, $conditions);
        }

        return $this->database->getRows($sql, $formedConditions);
    }

    public function deleteUser($conditions)
    {
        $sql = "DELETE from `users`";
        $formedConditions = [];

        if (!empty($conditions)) {
            [$sql, $formedConditions] = $this->database->formQuery($sql, $conditions);
        }

        return $this->database->sql($sql, $formedConditions)->rowCount();
    }

    public function getUsersTableFields()
    {
        return $this->database->getColumn("DESC users");
    }
}
