<?php
namespace Ipeweb\QueryBiulder;


class InsertQuery extends Query
{

    public function insert(string $tableName, array $columns) {
        $this->validateIdentifier($tableName);
        $this->validateIdentifier($columns);
        $filtered = [];
        foreach ($columns as $col) {
            $filtered[] = trim($col);
        }
        $this->queries['insert'] = 'INSERT INTO ' . $tableName . ' (' . implode(",", $filtered) . ')';
        $this->columns = $filtered;
        return $this;
    }

    public function values(array $values)
    {
        $placeholders = [];
        foreach ($this->columns as $i => $col) {
            $val = $values[$i] ?? null;
            $this->bindings['values'][$col] = $val;
            $placeholders[] = ':' . $col;
        }
        $this->queries['values'] = ' VALUES (' . implode(',', $placeholders) . ')';
        return $this;
    }

    public function toSql()
    {
        $sqlQuery = $this->queries['insert'] . $this->queries['values'];
        $this->querieReset();
        return $sqlQuery . ";";
    }
}

