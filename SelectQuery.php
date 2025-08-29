<?php
// select * from tabela where condicao order by colunaPrincipal, limit NumLimit
// select [*]from(name,?alias)  where(numCond,condição,?operador)
// order by(colunaPrincipal, ordemDeOrdenação) limit(valor)
// where cond1 or cond2 
// from batata as b -> assim é o alias
class SelectQuery
{

    public function select(array $columns)
    {
        return 'SELECT ' . implode(",", $columns) . " ";
    }
    public function from(string $table, ?string $alias = null)
    {
        if (isset($alias)) {
            return ' FROM ' . $table . ' as ' . $alias . " ";
        }
        return $table;
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
            print_r('WHERE ' . implode(" ", $formatedWhere));
            return 'WHERE ' . implode(" ", $formatedWhere);
        }
        print_r("WHERE " . implode(" ", $condiction));
    }
}

$arrayImag = ['p.id', 'p.name'];
$a = new SelectQuery();
$a->select($arrayImag);
$a->where(2, ['category = "eletronico"', 'category = "eletredomestico"'], ['OR']);
// print_r($a->from('pedido', 'p'));
