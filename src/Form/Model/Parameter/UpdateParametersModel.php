<?php

namespace App\Form\Model\Parameter;

class UpdateParametersModel
{
    private array $parameters = [];

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }
}