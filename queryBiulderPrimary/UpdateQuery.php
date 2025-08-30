<?php
namespace Ipeweb\QueryBiulder;


class UpdateQuery extends Query{

public function update($tableName,$condition)
{   $this->validateIdentifier($tableName);
    $this->queries['update'] = 'UPDATE ' . $tableName;
}

public function set(array $columns, array $values)
{
    $querie = [];
    $querieFormat = [];
    if(count($columns) != count($values)){
        throw new \Exception('colunas e valores devem ter o mesmo tamanho');
    }
    for ($i=0; $i < count($columns); $i++) { 
        $querie[] = $columns[$i] ." = ". $values[$i]; 
    }
    $querieFormat = $this->placeHolder($querie,"set");
    $this->queries['set'] = 'SET '. implode($querieFormat);
    return $this;
}
    public function toSql()
    {
        $sqlQuery = $this->queries['update']. $this->queries['set'];
         if(!empty($this->queries['where'])){
        $sqlQuery .= ' ' . $this->queries['where'];
         } 
        $this->querieReset();
        return $sqlQuery. ";";
    }



}