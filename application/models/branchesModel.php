<?php

namespace Application\Models;

use Application\Core\Model;

class BranchesModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addBranch($address, $status)
    {
        return $this->database->sql(
            "INSERT INTO `branches` (`address`, `status`) 
            VALUES (:address, :status)",
            ['address' => $address, 'status' => $status]
        )->rowCount();
    }

    public function updateBranch($data, $conditions)
    {
        $sql = "UPDATE `branches` SET ";
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

    public function selectBranches($conditions)
    {
        $sql = "SELECT * from `branches`";
        $formedConditions = [];

        if (!empty($conditions)) {
            [$sql, $formedConditions] = $this->database->formQuery($sql, $conditions);
        }

        return $this->database->getRows($sql, $formedConditions);
    }

    public function deleteBranch($conditions)
    {
        $sql = "DELETE from `branches`";
        $formedConditions = [];

        if (!empty($conditions)) {
            [$sql, $formedConditions] = $this->database->formQuery($sql, $conditions);
        }

        return $this->database->sql($sql, $formedConditions)->rowCount();
    }
}
