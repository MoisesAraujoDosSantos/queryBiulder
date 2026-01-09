<?php

namespace Moises\QueryBiulder;

require_once __DIR__ . '/../vendor/autoload.php';

class TesteWhereQuery extends Query
{
    public function whereFixado(array $clauses, array|string $operator, ?array $operation = null)
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
        
        $clauseIndex = 0;
        
        foreach ($clauses as $col => $values) {
            $values = is_array($values) ? $values : [$values];

            foreach ($values as $key => $val) {
                if (is_array($operator)) {
                    $op = $operator[$clauseIndex] ?? $operator[0];
                    
                    $where = array_merge(
                        $where,
                        $this->biulderPlaceholder($col, $val, $op, 'where')
                    );
                }
                $this->i++;
            }
            
            $clauseIndex++;
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
        } else {
            $operation[0] = $operation[0] ?? '';
            $tratedWhere = implode(" {$operation[0]} ", $where);
            
        }

        $this->queries['where'] = " WHERE {$tratedWhere}";
        
        return $this;
    }
    
    public function getWhereClause()
    {
        return $this->queries['where'];
    }
    
    public function getBindings()
    {
        return $this->bindings;
    }
}

$carros = [
    ['id' => 1, 'name' => 'Ferrari F8', 'esportivo' => 1],
    ['id' => 2, 'name' => 'Honda Civic', 'esportivo' => 0],
    ['id' => 3, 'name' => 'Lamborghini Huracán', 'esportivo' => 1],
    ['id' => 4, 'name' => 'Toyota Corolla', 'esportivo' => 0],
    ['id' => 5, 'name' => 'Porsche 911', 'esportivo' => 1],
    ['id' => 6, 'name' => 'Volkswagen Golf', 'esportivo' => 0],
    ['id' => 7, 'name' => 'McLaren 720S', 'esportivo' => 1],
    ['id' => 8, 'name' => 'Ford Focus', 'esportivo' => 0],
];

echo "=== DADOS DA TABELA CARROS ===\n";
echo json_encode($carros, JSON_PRETTY_PRINT) . "\n\n";

echo "=== TESTE 1: BUSCA REAL - Carros que NÃO são esportivos ===\n";
$queryCarros = new TesteWhereQuery();
$queryCarros->where(
    ['esportivo' => 0],
    '!=',
    ['AND']
);
echo "Query SQL: " . $queryCarros->getWhereClause() . "\n";
echo "Bindings: " . json_encode($queryCarros->getBindings(), JSON_PRETTY_PRINT) . "\n\n";

$carrosNaoEsportivos = array_filter($carros, function($carro) {
    return $carro['esportivo'] != 1;
});

echo "Carros encontrados (não esportivos):\n";
echo json_encode(array_values($carrosNaoEsportivos), JSON_PRETTY_PRINT) . "\n\n";

echo "=== TESTE 2: 6 Cláusulas com 6 Operadores diferentes ===\n";
$query6 = new TesteWhereQuery();
$query6->where(
    ['id' => 1, 'name' => 'Ferrari', 'esportivo' => 1, 'color' => 'red', 'year' => 2020, 'price' => 500000],
    ['=', '!=', '>', '<', '>=', '<='],
    ['AND', 'AND', 'OR', 'AND', 'OR']
);
echo "Query: " . $query6->getWhereClause() . "\n";
echo "Bindings: " . json_encode($query6->getBindings(), JSON_PRETTY_PRINT) . "\n\n";

echo "=== TESTE 3: 10 Cláusulas com 10 Operadores (alguns repetidos) ===\n";
$query10 = new TesteWhereQuery();
$query10->where(
    [
        'id' => 1,
        'name' => 'Ferrari',
        'esportivo' => 1,
        'color' => 'red',
        'year' => 2020,
        'price' => 500000,
        'brand' => 'Ferrari',
        'horsepower' => 800,
        'acceleration' => 2.9,
        'topSpeed' => 340
    ],
    ['=', '!=', '=', '<', '>=', '<=', '!=', '>', '>=', '<'],
    ['AND', 'OR', 'AND', 'AND', 'OR', 'AND', 'OR', 'AND', 'OR']
);
echo "Query: " . $query10->getWhereClause() . "\n";
echo "Bindings: " . json_encode($query10->getBindings(), JSON_PRETTY_PRINT) . "\n";
?>
