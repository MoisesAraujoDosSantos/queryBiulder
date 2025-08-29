<?php
namespace Ipeweb\QueryBiulder;


class UpdateQuery extends Query{

public function update($tableName,$condition)
{
    $this->queries['update'] = 'UPDATE' . $tableName;
}

public function set(array $columns, array $values)
{
    $querie = [];
    if(count($columns) != count($values)){
        throw new \Exception('colunas e valores devem ter o mesmo tamanho');
    }
    for ($i=0; $i < count($columns); $i++) { 
        $querie[] = $columns[$i] ." = ". $values[$i]; 
    }
    $this->queries['set'] = 'SET '. implode($querie);
}
    public function toSql()
    {
        $sqlQuery = $this->queries['update']. $this->queries['set'];
         if(!empty($this->queries['where'])){
        $sqlQuery .= $this->queries['update']. $this->queries['set'] ;
         } 
        $this->querieReset();
        return $sqlQuery. ";";
    }



}