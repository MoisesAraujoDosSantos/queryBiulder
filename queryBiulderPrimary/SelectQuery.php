<?php
namespace Ipeweb\QueryBiulder;



class SelectQuery extends Query
{


    public function select(array $columns)
    {
        $this->validateIdentifier($columns);
        $this->queries["select"] = 'SELECT ' . implode(",", $columns) . " ";
        return $this;
    }


    //o numbercondition vai ser opcional na mae, mas aqui obrigatorio, é so colocar um if e lançar exceção
    public function where(array $condiction,?int $NumberCondiction = null, ?array $operator = null)
    {
        if ($NumberCondiction == null) {
            throw new \Exception('Tem que indicar o numero de condições!!');
        }
        return parent::where($condiction,$NumberCondiction,  $operator);
    }
    public function order(int $NumbeOrder, array $columnName, ?array $order = NULL)
    {
        $this->validateIdentifier($columnName);
        $formatedOrder = $this->genericOrdemClause( $columnName,$NumberOrder, $order);
        if ($order !== null) {
            foreach ($order as $typeOrder) {
                if ($typeOrder !== "ASC" && $typeOrder !== "DESC") {
                    throw new \InvalidArgumentException("Invalid order: $typeOrder");
                }
            }
        }

        if ($NumberOrder != 1 and count($columnName) == $NumberOrder and count($order) == $NumberOrder - 1) {
            $this->queries["order"] = 'ORDER BY ' . implode($formatedOrder) . " ";
            return $this;
        }
        $this->queries["order"] = 'ORDER BY ' . implode(" ", $columnName) . " ";
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
    public function toSql()
    {
        $sqlQuery = $this->queries['select'] . " " . $this->queries['from'] . $this->queries['where']
            . $this->queries['order'] . " " . $this->queries['limit'];
        $this->querieReset();
        return trim($sqlQuery) . ";";
    }
}
