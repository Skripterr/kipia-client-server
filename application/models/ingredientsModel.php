<?php

namespace Application\Models;

use Application\Core\Model;

class IngredientsModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addIngredients($name, $weight, $bakingId)
    {
        return $this->database->sql(
            "INSERT INTO `ingredients`(`name`, `weight`, `baking_id`) 
            VALUES (:name, :weight, :baking_id)",
            ['name' => $name, 'weight' => $weight, 'baking_id' => $bakingId]
        )->rowCount();
    }

    public function updateIngredients($data, $conditions)
    {
        $sql = "UPDATE `ingredients` SET ";
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

    public function selectIngredients($conditions)
    {
        $sql = "SELECT * from `ingredients`";
        $formedConditions = [];

        if (!empty($conditions)) {
            [$sql, $formedConditions] = $this->database->formQuery($sql, $conditions);
        }

        return $this->database->getRows($sql, $formedConditions);
    }

    public function deleteIngredients($conditions)
    {
        $sql = "DELETE from `ingredients`";
        $formedConditions = [];

        if (!empty($conditions)) {
            [$sql, $formedConditions] = $this->database->formQuery($sql, $conditions);
        }

        return $this->database->sql($sql, $formedConditions)->rowCount();
    }
}
