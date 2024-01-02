<?php

namespace App\Business;

class TokenBusiness
{
    public function generateToken(int $length = 16): string
    {
        return bin2hex(openssl_random_pseudo_bytes(round($length / 2)));
    }
}