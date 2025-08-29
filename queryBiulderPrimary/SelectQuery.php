<?php
namespace Ipeweb\QueryBiulder;



class SelectQuery extends Query
{


    public function select(array $columns)
    {
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
        $formatedOrder = $this->genericOrdemClause( $columnName,$NumbeOrder, $order);
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
        $this->querieReset();
        return trim($sqlQuery) . ";";
    }
}
