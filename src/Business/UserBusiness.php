<?php

namespace App\Business;

class UserBusiness
{
    public function __construct(
        private readonly ParameterBusiness $parameterBusiness,
    )
    {

    }

    public function isRegistrationEnabled(): bool
    {
        return $this->parameterBusiness->getParameterValue('enable_registration');
    }
}