<?php

namespace Moises\QueryBiulder;

trait ReturningTrait
{
    public string $returning = '';
    public function returning($field = null)
    {
        if (is_array($field)) {
            $fieldSanitized = implode(', ', $field);
            $this->returning = ' RETURNING ' . $fieldSanitized . ' ';

        } else {
            $this->returning = ' RETURNING ' . $field . ' ';
        }
    }
    public function returningString()
    {
        return $this->returning;
    }
}
