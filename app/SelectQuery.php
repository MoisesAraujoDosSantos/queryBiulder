<?php
namespace Moises\QueryBiulder;

use Exception;

class SelectQuery extends Query
{

    public function select(array $columns)
    {
        $this->validateIdentifier($columns);
        $columnsName = implode(",", $columns);
        $this->queries["select"] = "SELECT {$columnsName} ";
        return $this;
    }

     public function order(array $conditions)
    {
        foreach ($conditions as $column => $value) {

            if(strtoupper($value)!= 'ASC' && strtoupper($value)!= 'DESC'  ){
                throw new \InvalidArgumentException('Wrong sort name, Valid Names: ASC or DESC');
            }
            $ordem[] = "{$column} {$value}";
        }
 
        $queryBody = implode(" ,", $ordem);
        $this->queries['order'] = " ORDER BY {$queryBody}";

        return $this;
    }

    public function limit(int $limitNumber)
    {
        if ($limitNumber <= 0) {
            throw new \InvalidArgumentException("Limit must be a positive integer.");
        }
        $this->queries["limit"] = "LIMIT {$limitNumber} ";
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

        $querie = '';
        $newCondition = '';

        $querie .= "{$typeOfJoin} {$this->validateIdentifierGeneric($table)}";

        if($alias !== null){
            $querie .= " AS {$alias}";
        }

        if (count($joinCondition) > 2) {
            throw new Exception("so pode ter duas condições de junção neste momento");
        }

        $this->validateIdentifierGeneric($joinCondition[0]);
        $this->validateIdentifierGeneric($joinCondition[1]);

        $newCondition = implode(" {$operator}", $joinCondition);
     
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
        $sqlQuery = "{$this->queries['select']} {$this->queries['from']}{$this->queries['join']}{$this->queries['where']}
            {$this->queries['order']} {$this->queries['limit']}";
        $this->querieReset();
        return trim($sqlQuery) . ";";
    }
}
