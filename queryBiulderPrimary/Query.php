<?php
namespace Ipeweb\QueryBiulder;

class Query {

        public array $queries = [
        'select' => '',
        'from'   => '',
        'where'  => '',
        'order'  => '',
        'limit'  => '',
    ];
        public array $bindings = [
        'select' => [],
        'from'   => [],
        'where'  => [],
        'order'  => [],
        'limit'  => [],
        ];

        public function from(string $table, ?string $alias = null)
    {
        $this->validateIdentifier($table);
        $this->validateIdentifier($alias);
        if ($alias !== null) {
            $this->queries["from"] = ' FROM ' . $table . ' as ' . $alias . " ";
            return $this;
        }
        $this->queries["from"] = ' FROM ' . $table . " ";
        return $this;
    }

    protected function genericOrdemClause( array $fieldExpression,?int $Quantity = null, ?array $modifiers = NULL)
    {
        $formated = [];
        $formated[] = $fieldExpression[0];
        if ($Quantity != 1 and count($fieldExpression) == $Quantity and count($modifiers) == $Quantity - 1) {

            for ($i = 1; $i < count($fieldExpression); $i++) {
                if ($i < count($fieldExpression)) {
                    $formated[] = $modifiers[($i - 1) % count($modifiers)];
                    $formated[] = $fieldExpression[$i];
                }
            }
        }
        return $formated;
    }
        public function where(array $condiction, ?int $NumberCondiction = null, ?array $operator = null)
    {
        $tratament = $this->genericOrdemClause( $condiction,$NumberCondiction, $operator);
        $formatedWhere = [];
        if ($NumberCondiction != 1 and count($condiction) == $NumberCondiction and count($operator) == $NumberCondiction - 1) {
            $formatedWhere = $this->placeHolder($condiction,"where"); 
            
            $this->queries["where"] =  'WHERE ' . implode(" ", $formatedWhere) . " ";
            return $this;
        }
        $this->queries["where"] = 'WHERE ' . implode(" ", $this->placeHolder($condiction,"where")) . " ";
        return $this;
    }

    public function placeHolder(array|string $textValue, string $clauseName)
    {   $formatedQuery = [];
        if(is_array($textValue)) {
            for ($i = 0; $i < count($textValue); $i++) {
                [$field,$value] = explode('=',$textValue[$i],2);
                $field = trim($field);
                $value = trim($value);
                $this->bindings[$clauseName][$field] = $value;
                $formatedQuery[] = $field.' = :'.$field;
            }
            return $formatedQuery;
        }
        [$field,$value] = explode('=',$textValue,2);
        $field = trim($field);
        $value = trim($value);
        $this->bindings[$clauseName][$field] = $value;
        $formatedQuery[] = $field.' = :'.$field;
        return $formatedQuery;
    }

    protected function validateIdentifier(string|array $name){
        if(is_array($name)) {
            foreach ($name as $item) {
                if(!preg_match('/^[a-zA-Z0-9_]*$/', $item)) {
                    throw new \InvalidArgumentException("Invalid identifier: $item");
                }
            }
        return $name;
        }
        if(!preg_match('/^[a-zA-Z0-9_]*$/', $name)) {
                throw new \InvalidArgumentException("Invalid identifier: $name");
        }
        
        return $name;
    }

    public function querieReset()
    {
        $this->queries = [
        'select' => '',
        'from'   => '',
        'where'  => '',
        'order'  => '',
        'limit'  => '',
    ];
    }

    public function bindReset()
    {
        $this->bindings = [
            'select' => [],
            'from'   => [],
            'where'  => [],
            'order'  => [],
            'limit'  => [],
        ];
    }

    public function prepare(\PDO $pdo, string $sqlQuery, array $bindings)
    {
        $stmt = $pdo->prepare($sqlQuery);
        foreach ($bindings as $clauseValues) {
            foreach ($clauseValues as $placeholder => $value) {
                $stmt->bindValue(':'.$placeholder, $value);
            }
        }
        return $stmt;
    }
    public function execute(\PDO $pdo)
    { 
        $sqlQuery = $this->toSql();
        $bindings = $this->bindings;
        $stmt = $this->prepare($sqlQuery,$bindings);

        $this->bindReset();
        $stmt->execute();
        return $stmt;
    }

}