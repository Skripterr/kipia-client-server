<?php

namespace Application\Models;

use Application\Core\Model;

class EquipmentModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addEquipment($name, $branchId, $sanitizingInterval, $lastSanitizingDate)
    {
        return $this->database->sql(
            "INSERT INTO `equipment`(`name`, `branch_id`, `sanitizing_interval`, `last_sanitizing_date`) 
            VALUES (:name, :branch_id, :sanitizing_interval, :last_sanitizing_date)",
            ['name' => $name, 'branch_id' => $branchId, 'sanitizing_interval' => $sanitizingInterval, 'last_sanitizing_date' => $lastSanitizingDate]
        )->rowCount();
    }

    public function updateEquipment($data, $conditions)
    {
        $sql = "UPDATE `equipment` SET ";
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

    public function selectEquipment($conditions)
    {
        $sql = "SELECT * from `equipment`";
        $formedConditions = [];

        if (!empty($conditions)) {
            [$sql, $formedConditions] = $this->database->formQuery($sql, $conditions);
        }

        var_dump($sql, $formedConditions);

        return $this->database->getRows($sql, $formedConditions);
    }

    public function deleteEquipment($conditions)
    {
        $sql = "DELETE from `equipment`";
        $formedConditions = [];

        if (!empty($conditions)) {
            [$sql, $formedConditions] = $this->database->formQuery($sql, $conditions);
        }

        return $this->database->sql($sql, $formedConditions)->rowCount();
    }
}
