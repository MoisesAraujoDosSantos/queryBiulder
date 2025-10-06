<?php

namespace Moises\QueryBiulder;


use RuntimeException;

class Query
{
    public $i = 0;

    public int $a = 0;
    public array $queries = [
        'select' => '',
        'from'   => '',
        'where'  => '',
        'order'  => '',
        'join'   => '',
        'limit'  => '',
    ];
    public array $bindings = [
        'select' => [],
        'from'   => [],
        'where'  => [],
        'order'  => [],
        'join'   => [],
        'limit'  => [],
    ];

    public function from(string $table, ?string $alias = null)
    {
        $this->validateIdentifier($table);
        if ($alias !== null) {
            $this->validateIdentifier($alias);
        }
        if ($alias !== null) {
            $this->queries["from"] = ' FROM ' . $table . ' as ' . $alias . " ";
            return $this;
        }
        $this->queries["from"] = ' FROM ' . $table . " ";
        return $this;
    }


    public function resetIncrement(): void
    {
        $this->i = 0;
    }
    public function where(array $clauses, array|string $operator, ?array $operation = null) //adicionar validação pra valor nulo se tiver mais de um operador
    {
        $where = [];
        $this->resetIncrement();
        if (is_string($operator)) {
            $operator = explode(" ", $operator);
        }
        foreach ($clauses as $key => $value) {
            if (str_contains($value, ",")) {
                $clauses[$key] = explode(",", $value);
            }
        }
        foreach ($clauses as $col => $values) {
            $values = is_array($values) ? $values : [$values];

            foreach ($values as $key => $val) {
                if (is_array($operator)) {
                    foreach ($operator as $op) {
                        $where = array_merge(
                            $where,
                            $this->biulderPlaceholder($col, $val, $op, 'where')
                        );
                    }
                }
                $this->i++;
            }
        }

        $tratedWhere = '';
        // passar se nao for nulo
        if ($operation != null &&count($operation) !== 1) {

            for ($i = 0; $i < count($where); $i++) {

                if ($i == 0) {
                    $tratedWhere .= " {$where[$i]}";
                } else {
                    $tratedWhere .= " {$operation[$i - 1]} {$where[$i]} ";
                }
            }
        } 
        else {
            $tratedWhere = implode(" ", $where);
        }
        
        $this->queries['where'] = ' WHERE ' . $tratedWhere;

        return $this;
    }
    public function biulderPlaceholder(string $criterion, string $quantity, string $operator, string $clauseName)
    {
        $formatedQuery = [];

        $field = trim($criterion);
        $value = trim($quantity);
        $fieldIncrement = "{$field}_{$this->i}";
        // evitando sobrescrita caso o nome  do campo seja o mesmo
        $this->bindings[$clauseName][$fieldIncrement] = $value;

        $formatedQuery[] = "$field $operator :$fieldIncrement";
        return $formatedQuery;
    }
    protected function validateIdentifier(string|array $name)
    {
        if (is_array($name)) {
            foreach ($name as $item) {
                if (!preg_match('/^[\p{L}0-9_*]+$/u', $item)) {
                    throw new \InvalidArgumentException("Invalid identifier: $item");
                }
            }
            return $name;
        }
        if (!preg_match('/^[\p{L}0-9_*]+$/u', $name)) {
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
            'join'   => '',
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
            'join'   => [],
        ];
    }

    public function toSql()
    {
        return '';
    }

    public function prepare(\PDO $pdo, string $sqlQuery, array $bindings)
    {
        $stmt = $pdo->prepare($sqlQuery);
        foreach ($bindings as $clauseValues) {
            foreach ($clauseValues as $placeholder => $value) {

                $stmt->bindValue(":".$placeholder, $value);
            }
        }
        
        return $stmt;
    }
    public function execute(\PDO $pdo)
    {
        $sqlQuery = $this->toSql();
        $bindings = array_filter($this->bindings);  
         
        $MaybeSelect = substr($sqlQuery,0,7) === "SELECT" ? true : false;



        $stmt = $this->prepare($pdo, $sqlQuery, $bindings);

        $stmt->execute();
        if ($stmt->rowCount() == 0 && !$MaybeSelect) {
            throw new RuntimeException('Nenhum registro encontrado para esta condição');
        }

        return $stmt;
    }
}
