<?php

namespace App\Service;

class Slugger
{
    public static function slugify(string $string)
    {
        $string = trim(mb_strtolower($string));
        $string = preg_replace(['/\s+/', '/[-]{2,}+/'], '-', $string);

        return $string;
    }
}