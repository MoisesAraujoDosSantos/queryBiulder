<?php

class SelectQuery
{
    public array $queries = [
        'select' => '',
        'from'   => '',
        'where'  => '',
        'order'  => '',
        'limit'  => '',
    ];

    public function select(array $columns)
    {
        $this->queries["select"] = 'SELECT ' . implode(",", $columns) . " ";
        return $this;
    }
    public function from(string $table, ?string $alias = null)
    {
        if (isset($alias)) {
            $this->queries["from"] = ' FROM ' . $table . ' as ' . $alias . " ";
            return $this;
        }
        $this->queries["from"] = ' FROM ' . $table . " ";
        return $this;
    }
    private function genericOrdemClause(int $Quantity, array $fieldExpression, ?array $modifiers = NULL)
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
    //o numbercondition vai ser opcional na mae, mas aqui obrigatorio, é so colocar um if e lançar exceção
    public function where(int $NumberCondiction, array $condiction, ?array $operator = null)
    {
        $formatedWhere = $this->genericOrdemClause($NumberCondiction, $condiction, $operator);
        if ($NumberCondiction != 1 and count($condiction) == $NumberCondiction and count($operator) == $NumberCondiction - 1) {
            $this->queries["where"] =  'WHERE ' . implode(" ", $formatedWhere) . " ";
            return $this;
        }
        $this->queries["where"] = 'WHERE ' . implode(" ", $condiction);
        return $this;
    }
    public function order(int $NumbeOrder, array $columnName, ?array $order = NULL)
    {
        $formatedOrder = $this->genericOrdemClause($NumbeOrder, $columnName, $order);
        if ($NumbeOrder != 1 and count($columnName) == $NumbeOrder and count($order) == $NumbeOrder - 1) {
            $this->queries["order"] = 'ORDER BY ' . implode($formatedOrder) . " ";
            return $this;
        }
        $this->queries["order"] = 'ORDER BY ' . implode(" ", $columnName) . " ";
        return $this;
    }
    public function limit($limitNumber)
    {
        $this->queries["limit"] = 'LIMIT ' . $limitNumber . " ";
        return $this;
    }
    public function toSql()
    {
        $sqlQuery = $this->queries['select'] . " " . $this->queries['from'] . $this->queries['where']
            . $this->queries['order'] . " " . $this->queries['limit'];
        $this->queries = [];
        return trim($sqlQuery) . ";";
    }
}
