<?php
namespace Ipeweb\QueryBiulder;


class UpdateQuery extends Query{
    public ?array $requiremnt;
    public function update($tableName, $condition,array $clauses,array $operator, ?array $operations = null)
    {
        $this->validateIdentifier($tableName);
        if (!is_array($condition) || empty($condition)) {
            throw new \InvalidArgumentException('Update sem condição não é permitido. Passe um array de condição.');
        }
        $this->queries['update'] = 'UPDATE ' . $tableName;
        $this->requiremnt = $condition;
        return $this;
    }

    public function set(array $conditionals,array $condi, ?array $logicalConditions)
    {
        $querieFormat = [];
        foreach($conditionals as $conditional => $amount){

            $querieFormat = array_merge($querieFormat, $this->biulderPlaceholder($conditional, $amount, '=', "set"));
        }

        $this->queries['set'] = ' SET ' . implode(', ', $querieFormat);
        $this->where($condi, ['='], $logicalConditions);
        $this->requiremnt = null;
        return $this;
    }

    public function toSql()
    {
        $sqlQuery = $this->queries['update'] . $this->queries['set'];
        if (!empty($this->queries['where'])) {
            $sqlQuery .= ' ' . $this->queries['where'];
        }
        // $this->querieReset();
        return trim($sqlQuery) . ";";
    }



}