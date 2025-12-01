<?php
namespace Moises\QueryBiulder;

class InsertQuery extends Query
{

    use ReturnninTrait;

    public array $columns = [];
    public function insert(string $tableName, array $columns) {
        $this->validateIdentifier($tableName);
        $this->validateIdentifier($columns);
        $filtered = [];
        foreach ($columns as $col) {
            $filtered[] = trim($col);
        }
        $columnName = implode(", ", $filtered);
        $this->queries['insert'] = "INSERT INTO {$tableName} ({$columnName})";
        $this->columns = $filtered;
        return $this;
    }

    public function values(array $values)
    {

        $placeholders = [];
        foreach ($this->columns as $i => $col) {
            $val = $values[$i] ?? null;
            $this->bindings['values'][$col] = $val;
            $placeholders[] = ":{$col}";
        }
        $valuesName = implode(", ", $placeholders);
        $this->queries['values'] = " VALUES ({$valuesName})";
        return $this;
    }

    public function toSql()
    {
        $sqlQuery = "{$this->queries['insert']}{$this->queries['values']} {$this->retturningString()}";
        $this->querieReset();
        return "{$sqlQuery};";
    }
} 


