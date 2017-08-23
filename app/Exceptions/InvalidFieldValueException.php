<?php

namespace App\Exceptions;

class InvalidFieldValueException extends InvalidImportFileException
{
    /**
     * The invalid field.
     *
     * @var string
     */
    protected $field;

    /**
     * The invalid value.
     *
     * @var int|string
     */
    protected $value;

    /**
     * Get the field name of this exception.
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Get the field value of this exception.
     *
     * @return int|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the field name and value of this exception.
     *
     * @param string     $field
     * @param int|string $value
     * @param string     $message
     *
     * @return $this
     */
    public function setField($field, $value, $message = null)
    {
        $this->field = $field;
        $this->value = $value;
        if ($message === null) {
            $message = "The file contains an invalid value [{$value}] for the field [{$field}].";
        }
        $this->message = $message;

        return $this;
    }
}
