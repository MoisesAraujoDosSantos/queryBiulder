<?php

class DeleteQuery extends Query {


    public function delete($tableName,$condition)
    {
       return $this->from($tableName)->where($condition); 
    }
}