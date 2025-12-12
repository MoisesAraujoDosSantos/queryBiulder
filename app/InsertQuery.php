<?php
namespace Moises\QueryBiulder;

class InsertQuery extends Query
{
    private int $times;

    use ReturningTrait;

    public function __construct()
    {
        $this->times = 0;
    }
    public array $columns = [];
    public function insert(string $tableName, array $columns) {
        $this->validateIdentifier($tableName);
        $this->validateIdentifier($columns);
        $filtered = [];
        foreach ($columns as $col) {
            $filtered[] = trim($col);
        }
        $columnName = implode(", ", $filtered);
        $this->queries['insert'] = "INSERT INTO {$tableName} ({$columnName})";
        $this->columns = $filtered;
        return $this;
    }

    public function values(array $values)
    {
        $this->times += 1;

        $placeholders = [];
        foreach ($this->columns as $i => $col) {
            $val = $values[$i] ?? null;
            // Criar placeholders únicos para cada conjunto de valores
            $placeholder = "{$col}_{$this->times}";
            $this->bindings['values'][$placeholder] = $val;
            $placeholders[] = ":{$placeholder}";
        }
        $valuesName = implode(", ", $placeholders);
    // para um values
        if ($this->times === 1) {
            $this->queries['values'] = " VALUES ({$valuesName})";
        } else {
            // se tiver mais de um values encadeado, colocar encadeado
            $this->queries['values'] .= ", ({$valuesName})";
        }
        return $this;
    }

    public function toSql()
    {
        $sqlQuery = "{$this->queries['insert']}{$this->queries['values']} {$this->returningString()}";
        $this->querieReset();
        $this->times = 0;
        return "{$sqlQuery};";
    }
} 


