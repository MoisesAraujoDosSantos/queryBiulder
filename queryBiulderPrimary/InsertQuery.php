<?php
namespace Ipeweb\QueryBiulder;


class InsertQuery extends Query
{

    public function insert(string $tableName, array $columns) {
        $this->queries['insert'] = 'INSERT INTO '.$tableName. ' (' .implode(",",$columns).')';
        return $this;
    }

    public function values(array|string $values)
    {

        if (is_array($values)) {
            $this->queries['values'] = '(' . implode(',', $values) . ')';
        }
        if (is_string($values)) {
            $this->queries['values'] = '(' . $values . ')';
        }
        return $this;
    }
        public function toSql()
    {
        $sqlQuery = $this->queries['insert']. $this->queries['values'];
        $this->querieReset();
        return $sqlQuery. ";";
    }
}

