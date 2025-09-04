<?php
namespace Ipeweb\QueryBiulder;


class UpdateQuery extends Query{
    
    public function update($tableName)
    {
        $this->validateIdentifier($tableName);
        // if (!is_array($condition) || empty($condition)) {
        //     throw new \InvalidArgumentException("Update without condition is not allowed. Pass a condition array.");
        // }
        $this->queries['update'] = 'UPDATE ' . $tableName;
        
        return $this;
    }

    public function set(array $conditionals,array $whereCriterion,array $condition ,?array $logicalConditions = null)
    {
        $querieFormat = [];
        foreach($conditionals as $conditional => $amount){

            $querieFormat = array_merge($querieFormat, $this->biulderPlaceholder($conditional, $amount, '=', "set"));
        }

        $this->queries['set'] = ' SET ' . implode(', ', $querieFormat);
        $this->where($whereCriterion, $condition, $logicalConditions);

        return $this;
    }

    public function toSql()
    {
        $sqlQuery = $this->queries['update'] . $this->queries['set'];
        if (!empty($this->queries['where'])) {
            $sqlQuery .= ' ' . $this->queries['where'];
        }
        $this->querieReset();
        return trim($sqlQuery) . ";";
    }



}