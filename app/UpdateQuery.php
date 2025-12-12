<?php
namespace Moises\QueryBiulder;


class UpdateQuery extends Query{
    use ReturningTrait;
    
    public function update($tableName)
    {
        $this->validateIdentifier($tableName);

        $this->queries['update'] = "UPDATE {$tableName}";
        
        return $this;
    }

    public function set(array $conditionals,array $whereCriterion,array $condition ,?array $logicalConditions = null)
    {
        $querieFormat = [];
        foreach($conditionals as $conditional => $amount){

            $querieFormat = array_merge($querieFormat, $this->biulderPlaceholder($conditional, $amount, '=', "set"));
        }
        $querieFiltered = implode(', ', $querieFormat);
        $this->queries['set'] = " SET {$querieFiltered}";
        $this->where($whereCriterion, $condition, $logicalConditions);

        return $this;
    }

    public function toSql()
    {
        $sqlQuery = "{$this->queries['update']}{$this->queries['set']}";
        if (!empty($this->queries['where'])) {
            $sqlQuery .=  " {$this->queries['where']}";
        }
        $sqlQuery .= " {$this->returningString()}";
        $this->querieReset();
        return trim($sqlQuery) . ";";
    }



}