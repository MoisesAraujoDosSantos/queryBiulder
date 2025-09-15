<?php
namespace Ipeweb\QueryBiulder;

use Exception;

class SelectQuery extends Query
{

    public function select(array $columns)
    {
        $this->validateIdentifier($columns);
        $this->queries["select"] = 'SELECT ' . implode(",", $columns) . " ";
        return $this;
    }

     public function order(array $conditions)
    {
        foreach ($conditions as $column => $value) {

            if(strtoupper($value)!= 'ASC' && strtoupper($value)!= 'DESC'  ){
                throw new \InvalidArgumentException('Wrong sort name, Valid Names: ASC or DESC');
            }
            $ordem[] = $column . " " . $value;
        }
 
        $query = ' ORDER BY ' . implode(" ,", $ordem);
        $this->queries['order'] = $query;

        return $this;
    }

    public function limit(int $limitNumber)
    {
        if ($limitNumber <= 0) {
            throw new \InvalidArgumentException("Limit must be a positive integer.");
        }
        $this->queries["limit"] = 'LIMIT ' . $limitNumber . " ";
        return $this;
    }

    private function validateIdentifierGeneric(string $name)
    {
        if (!preg_match('/^[A-Za-z0-9_.]+$/', $name)) {
            throw new Exception("Identificador inválido: {$name}");
        }
        return $name;
    }

    private function createExtraCondition(array $logicOperator, array $extraConditions)
    {
        $filtedExtraCondition = " ";
        for($i = 0; $i < count($logicOperator); $i ++){
            $filtedExtraCondition .= $this->validateIdentifierGeneric($logicOperator[$i]) 
            ." ". $this->validateIdentifierGeneric($extraConditions[$i]) . " ";
        }
        return $filtedExtraCondition;
    }

    public function join(string $typeOfJoin, string $table, array $joinCondition,string $operator, ?string $alias = null, ?array $logicOperator = null,?array $extraConditions = null)
    {
        // join(inner join, table, ['id', 2]usar placeholderdps,'=')
        $querie = '';
        $newCondition = '';


        $querie .= "{$typeOfJoin} {$this->validateIdentifierGeneric($table)}";

        if($alias !== null){
            $querie .= " {$alias}";
        }

        if (count($joinCondition) > 2) {
            throw new Exception("so pode ter duas condições de junção neste momento");
        }

        $joinCondition = $this->biulderPlaceholder($joinCondition[0],$joinCondition[1],$operator,'join');

        $newCondition = implode(" ", $joinCondition);
     
        $querie .= ' ON ' . $newCondition;

        if ($logicOperator !== null && $extraConditions !== null){
            if (count($logicOperator) !== count($extraConditions)){ 
            throw new Exception("nao pode colocar uma quantidade de condições extras a mais do que uma quantidade de operadores logicos");
            }
            $extraCondition = $this->createExtraCondition($logicOperator, $extraConditions);
            
            $querie .= rtrim($extraCondition);
        
        }
        $this->queries['join']  .= $querie;
        return $this;
    }


    public function toSql()
    {
        $sqlQuery = $this->queries['select'] . " " . $this->queries['from']. $this->queries['join'] . $this->queries['where']
            . $this->queries['order'] . " " . $this->queries['limit'];
        $this->querieReset();
        return trim($sqlQuery) . ";";
    }
}
