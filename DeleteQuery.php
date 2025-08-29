<?php

class DeleteQuery extends Query{


    public function delete($tableName,$condition)
    {
       $this->queries['delete'] = 'DELETE' . $this->from($tableName)->where(null,$condition); 
    }
}