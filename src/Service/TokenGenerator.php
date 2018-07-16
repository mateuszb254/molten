<?php

namespace App\Service;

class TokenGenerator
{
    /**
     * Generates random token
     *
     * @return string
     */
    public function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}