<?php
namespace Ipeweb\QueryBiulder;


class InsertQuery extends Query
{

    public function insert(string $tableName, array $columns) {
        $this->validateIdentifier($tableName);
        $this->validateIdentifier($columns);
        $filtered = $this->placeHolder($columns, "insert");
        $this->queries['insert'] = 'INSERT INTO '.$tableName. ' (' .implode(",",$filtered).')';
        return $this;
    }

    public function values(array|string $values)
    {
        $valuesFiltered = $this->placeHolder($values, "values");

        $this->queries['values'] = '(' . implode(',', $valuesFiltered) . ')';
        
        return $this;
    }
        public function toSql()
    {
        $sqlQuery = $this->queries['insert']. $this->queries['values'];
        $this->querieReset();
        return $sqlQuery. ";";
    }
}

