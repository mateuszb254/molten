<?php

namespace App\Service;

class Slugger
{
    public static function slugify(string $string)
    {
        $string = trim(mb_strtolower($string));
        $string = str_replace(' ', '-', $string);

        return $string;
    }
}