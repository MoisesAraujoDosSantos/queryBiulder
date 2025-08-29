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
    public function where(int $NumberCondiction, array $condiction, ?array $operator = null)
    {
        $formatedWhere = [];
        $formatedWhere[] = $condiction[0];
        if ($NumberCondiction != 1 and count($condiction) == $NumberCondiction and count($operator) == $NumberCondiction - 1) {

            for ($i = 1; $i < count($condiction); $i++) {
                if ($i < count($condiction)) {
                    $formatedWhere[] = $operator[($i - 1) % count($operator)];
                    $formatedWhere[] = $condiction[$i];
                }
            }
            $this->queries["where"] =  'WHERE ' . implode(" ", $formatedWhere) . " ";
            return $this;
        }
        $this->queries["where"] = 'WHERE ' . implode(" ", $condiction);
        return $this;
    }
    public function order(int $NumbeOrder, array $columnName, ?array $order = NULL)
    {
        $formatedWhere = [];
        $formatedWhere[] = $columnName[0];
        if ($NumbeOrder != 1 and count($columnName) == $NumbeOrder and count($order) == $NumbeOrder - 1) {

            for ($i = 1; $i < count($columnName); $i++) {
                if ($i < count($columnName)) {
                    $formatedWhere[] = $order[($i - 1) % count($order)];
                    $formatedWhere[] = $columnName[$i];
                }
            }
            $this->queries["order"] = 'ORDER BY ' . implode($formatedWhere) . " ";
            return $this;
        }
        $this->queries["order"] = 'ORDER BY ' . implode(" ",$columnName) . " ";
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
        return trim($sqlQuery).";";
    }
}
