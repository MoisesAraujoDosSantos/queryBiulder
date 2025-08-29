<?php

namespace Ipeweb\QueryBiulder;


class DeleteQuery extends Query{


    public function delete($tableName,$condition)
    {
       $this->queries['delete'] = 'DELETE';
       $this->from($tableName)->where($condition,null); 
       return $this;
    }
    public function toSql()
    {
        $sqlQuery = $this->queries['delete']. $this->queries['from'] . $this->queries['where'] ;
        $this->querieReset();
        return $sqlQuery. ";";
    }
}
