<?php

namespace Moises\QueryBiulder;

trait ReturnninTrait
{
    public string $returning = '';
    public function retturning($field = null)
    {
        if (is_array($field)) {
            $fieldSanitized = implode(', ', $field);
            $this->returning = ' RETURNING ' . $fieldSanitized . ' ';

        } else {
            $this->returning = ' RETURNING ' . $field . ' ';
        }
    }
    public function retturningString()
    {
        return $this->returning;
    }
}
