<?php

namespace Application\Models;

use Application\Core\Model;

class BakingModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addBaking($name, $weight, $pricing, $branch)
    {
        return $this->database->sql(
            "INSERT INTO `baking`(`name`, `weight`, `pricing`, `branch_id`) 
            VALUES (:name, :weight, :pricing, :branch_id)",
            ['name' => $name, 'weight' => $weight, 'pricing' => $pricing, 'branch_id' => $branch]
        )->rowCount();
    }

    public function updateBaking($data, $conditions)
    {
        $sql = "UPDATE `baking` SET ";
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

    public function selectBaking($conditions)
    {
        $sql = "SELECT * from `baking`";
        $formedConditions = [];

        if (!empty($conditions)) {
            [$sql, $formedConditions] = $this->database->formQuery($sql, $conditions);
        }

        return $this->database->getRows($sql, $formedConditions);
    }

    public function deleteBaking($conditions)
    {
        $sql = "DELETE from `baking`";
        $formedConditions = [];

        if (!empty($conditions)) {
            [$sql, $formedConditions] = $this->database->formQuery($sql, $conditions);
        }

        return $this->database->sql($sql, $formedConditions)->rowCount();
    }
}
