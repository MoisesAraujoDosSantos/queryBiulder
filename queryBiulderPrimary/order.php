<?php
class order
{
    public $bindings;
    public $i = 0;
    public function order(array $conditions)
    {
        foreach ($conditions as $column => $value) {
            $ordem[] = $column . " " . $value;
        };
        $query = implode(" ,", $ordem);

        return $query;
    }
    public function where(array $clauses, array|string $operator, ?array $operation = null) //adicionar validação pra valor nulo se tiver mais de um operador
    {
        $where = [];
        foreach ($clauses as $clause => $quantity) {
            if (is_string($operator)) {
                $operator = explode(" ", $operator);
            }
            echo "p";
            if (is_array($operator)) {

                foreach ($operator as $op) {
                    $where = array_merge(
                        $where,
                        $this->biulderPlaceholder($clause, $quantity, $op, 'where')
                    );
                }
            }
            $this->i++;
        };
        $tratedWhere = '';
        for ($i = 0; $i < count($where); $i++) {

            if ($i == 0) {
                $tratedWhere .= "{$where[$i]}";
            } else {
                $tratedWhere .= "{$operation[$i - 1]} {$where[$i]} ";
            }
        } $this->queries['where'] = 'WHERE' . $tratedWhere;
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
        
        $formatedQuery[] ="$field $operator :$fieldIncrement";
        return $formatedQuery;
    }
}

$ordem = new order();
$ordem->order(['idade' => 'DESC', 'nome' => 'ASC']);
print_r($ordem->where(['idade' => '1', 'nome' => 'moises','id'=> 1], ['='], ['and','or']));
