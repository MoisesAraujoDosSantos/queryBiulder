<?php
class order
{
    public  $bindings;
    public array $queries;
    public $i = 0;
    public ?array $requiremnt;
    public function order(array $conditions)
    {
        foreach ($conditions as $column => $value) {
            try {
                if (strtoupper($value) != 'ASC' && strtoupper($value) != 'DESC') {
                    throw new Exception('Nome de ordenação errado, Nomes Válidos: ASC ou DESC');
                }
                $ordem[] = $column . " " . $value;
            } catch (\Exception $e) {
                print_r($e->getMessage());
            };
        }
        $query = ' ORDER BY ' . implode(" ,", $ordem);
        $this->queries['order'] = $query;

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
        if ($operation != null && count($operation) !== 1) {

            for ($i = 0; $i < count($where); $i++) {

                if ($i == 0) {
                    $tratedWhere .= " {$where[$i]}";
                } else {
                    $tratedWhere .= " {$operation[$i - 1]} {$where[$i]} ";
                }
            }
            $this->queries['where'] = ' WHERE ' . $tratedWhere;
            return $this;
        }
        if ($operation != null && count($operation) == 1) {
            $tratedWhere = implode(" $operation[0] ", $where);
            $this->queries['where'] = ' WHERE ' . $tratedWhere;
            return $this;
        }
        $tratedWhere = implode(" ", $where);
        $this->queries['where'] = ' WHERE ' . $tratedWhere;


        return $this;
    }

    public function biulderPlaceholder(string $clause, string $quantity, string $operator, string $clauseName)
    {
        $formatedQuery = [];

        $field = trim($clause);
        $value = trim($quantity);
        $fieldIncrement = "{$field}_{$this->i}";
        // evitando sobrescrita caso o nome  do campo seja o mesmo
        $this->bindings[$clauseName][$fieldIncrement] = $value;

        $formatedQuery[] = "$field $operator :$fieldIncrement";
        return $formatedQuery;
    }
    public function toSql()
    {
        $sqlQuery = $this->queries['set'] . $this->queries['where']
            . $this->queries['order'];

        return trim($sqlQuery) . ";";
    }

    public function set(array $conditionals, array $condi, ?array $logicalConditions = null)
    {
        $querieFormat = [];
        foreach ($conditionals as $conditional => $amount) {

            $querieFormat = array_merge($querieFormat, $this->biulderPlaceholder($conditional, $amount, '=', "set"));
        }

        $this->queries['set'] = ' SET ' . implode(', ', $querieFormat);
        $this->where($condi, ['='], $logicalConditions);
        $this->requiremnt = null;
        return $this;
    }
}

$ordem = new order();
$pdo = new \PDO("pgsql:host=localhost;port=5432;dbname= 'alura_pdo'", 'postgres', '@postgres');
$query = 'SELECT * FROM clients ';
print_r($ordem = $query . $ordem->set(['name' => 'Jeanne', 'idade' => '13'], ['id' => '4'])->order(['nome' => 'DESC'])->toSql());
// print_r($ordem->order(['idade' => 'DESC', 'nome' => 'ASC'])
// ->where(['idade' => '12', 'nome' => 'moises,ana', 'id' => 1], ['='], ['and']));
// ->update()->set(eiew,qwoi,)
// set(['id'=>'1'])
