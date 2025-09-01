<?php
namespace Ipeweb\QueryBiulder;


class UpdateQuery extends Query{
    public ?array $requiremnt;
    public function update($tableName, $condition)
    {
        $this->validateIdentifier($tableName);
        if (!is_array($condition) || empty($condition)) {
            throw new \InvalidArgumentException('Update sem condição não é permitido. Passe um array de condição.');
        }
        $this->queries['update'] = 'UPDATE ' . $tableName;
        $this->requiremnt = $condition;
        return $this;
    }

    public function set(array $columns, array $values)
    {
        if (count($columns) != count($values)) {
            throw new \Exception('Columns and values must have the same length');
        }
        $querie = [];
        for ($i = 0; $i < count($columns); $i++) {
            $querie[] = $columns[$i] . " = " . $values[$i];
        }
        $querieFormat = $this->placeHolder($querie, "set");
        $this->queries['set'] = ' SET ' . implode(', ', $querieFormat);
        $this->where($this->requiremnt,null);
        $this->requiremnt = null;
        return $this;
    }

    public function toSql()
    {
        $sqlQuery = $this->queries['update'] . $this->queries['set'];
        if (!empty($this->queries['where'])) {
            $sqlQuery .= ' ' . $this->queries['where'];
        }
        // $this->querieReset();
        return trim($sqlQuery) . ";";
    }



}