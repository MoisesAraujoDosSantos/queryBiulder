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
            return ' FROM '. $table . ' as ' . $alias . " ";
        }
        return $table;
    }
    public function where(int $NumberCondiction,array $condiction,?array $operator = null)
    {
        $ar = ['category = "eletronico"','category = "eletredomestico"'];
        $operator = ["OR"];
        $result = '';
        if ($NumberCondiction != 1 AND count($ar) == $NumberCondiction AND count($operator) == $NumberCondiction -1) {
            //verificar isso aqui
            for ($i = 0; $i < count($ar); $i++) {
                $result .= $ar[$i];
                if($i < count($ar) - 1 AND $i > 0) {
                echo $ar[$i] . PHP_EOL;
                $result .= " " . $operator[($i - 1) % count($operator)] . " ";
            }
            echo $result;
          }
        print_r($ar);
        
    }
}
}
$arrayImag = ['p.id', 'p.name'];
$a = new SelectQuery();
$a->select($arrayImag);
$a->where(2,['category = "eletronico"','category = "eletredomestico"'],['OR', 'AND']);
// print_r($a->from('pedido', 'p'));
