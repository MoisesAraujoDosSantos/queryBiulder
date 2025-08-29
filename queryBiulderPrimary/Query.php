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


        public function from(string $table, ?string $alias = null)
    {
        if (isset($alias)) {
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
        $formatedWhere = $this->genericOrdemClause( $condiction,$NumberCondiction, $operator);
        if ($NumberCondiction != 1 and count($condiction) == $NumberCondiction and count($operator) == $NumberCondiction - 1) {
            $this->queries["where"] =  'WHERE ' . implode(" ", $formatedWhere) . " ";
            return $this;
        }
        $this->queries["where"] = 'WHERE ' . implode(" ", $condiction);
        return $this;
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
}