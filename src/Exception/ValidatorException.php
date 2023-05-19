<?php

namespace App\Exception;


class ValidatorException extends \Exception
{
    private $validationList;

    /**
     * @return mixed
     */
    public function getValidationList()
    {
        return $this->validationList;
    }

    /**
     * @param mixed $validationList
     */
    public function setValidationList($validationList): self
    {
        $this->validationList = $validationList;
        return $this;
    }

}