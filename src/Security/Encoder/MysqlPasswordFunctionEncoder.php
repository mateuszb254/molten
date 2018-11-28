<?php

namespace App\Security\Encoder;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Encodes a password the same as mysql's PASSWORD() function (>=4.1)
 */
class MysqlPasswordFunctionEncoder implements PasswordEncoderInterface
{
    public function encodePassword($raw, $salt)
    {
        $password = strtoupper(
            sha1(
                sha1($raw, true)
            )
        );
        $password = '*' . $password;

        return $password;
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $encoded === $this->encodePassword($raw, $salt);
    }
}