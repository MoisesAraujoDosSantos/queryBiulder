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



     public function order(array $conditions)
    {
        foreach ($conditions as $column => $value) {
            try {
            if(strtoupper($value)!= 'ASC' && strtoupper($value)!= 'DESC'  ){
                throw new \Exception('Nome de ordenação errado, Nomes Válidos: ASC ou DESC');
            }
            $ordem[] = $column . " " . $value;
        }
        catch(\Exception $e){
            print_r($e->getMessage());
        }
        ;} 
        $query = ' ORDER BY ' . implode(" ,", $ordem);
        $this->queries['order'] = $query;

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
