<?php

namespace Moises\QueryBiulder;

use Moises\QueryBiulder\Query;

class PrepareTest
{
    protected array $types = [
        'string' => \PDO::PARAM_STR,
        'integer' => \PDO::PARAM_INT,
        'boolean' => \PDO::PARAM_BOOL,
        'NULL'   => \PDO::PARAM_NULL
    ];
    public function prepare(\PDO $pdo, string $sqlQuery, array $bindings)
    {
        $stmt = $pdo->prepare($sqlQuery);
        foreach ($bindings as $clauseValues) {

            foreach ($clauseValues as $placeholder => $value) {

                $paramType = $this->types[gettype($value)] ?? \PDO::PARAM_STR;

                    $stmt->bindValue(":{$placeholder}", $value, $paramType);
            }
        }

        return $stmt;
    }
    public function execute(\PDO $pdo)
    {



        $sqlQuery = 'SELECT * FROM student WHERE id_student = :id_student_0;';
        $stmt = $this->prepare($pdo, $sqlQuery, ["where" => ["id_student_0" => 1]]);

        $stmt->execute();
        // if ($stmt->rowCount() == 0) {
        //     throw new RuntimeException('Nenhum registro encontrado para esta condição');
        // }

        return $stmt;
    }
}

$teste = new PrepareTest();
$pdo = new \PDO("pgsql:host=localhost;port=5432;dbname=alura_pdo", 'postgres', '@postgres', [
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_STRINGIFY_FETCHES => false,
    \PDO::ATTR_EMULATE_PREPARES => false
]);
var_dump($teste->execute($pdo)->fetchAll(\PDO::FETCH_ASSOC));
echo PHP_EOL;
