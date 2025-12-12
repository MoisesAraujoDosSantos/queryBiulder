<?php

namespace Moises\QueryBiulder;


class DeleteQuery extends Query{
    use ReturningTrait;

    public function delete($tableName,array $whereCriterion,array $operators,?array $logicalConditions = null)
    {
       $this->validateIdentifier($tableName);
       $this->queries['delete'] = 'DELETE';
       $this->from($tableName)->where($whereCriterion,$operators,$logicalConditions);
       return $this;
    }
    public function toSql()
    {
        $sqlQuery = "{$this->queries['delete']} {$this->queries['from']} {$this->queries['where']} {$this->returningString()}";
        $this->querieReset();
        return "{$sqlQuery};";
    }
}
