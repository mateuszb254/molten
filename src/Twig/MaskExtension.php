<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Filter Mask
 *
 * Masks the given value based on type
 */
class MaskExtension extends AbstractExtension
{
    const SIMPLE_STRING = 'MASK_STRING';
    const EMAIL = 'MASK_EMAIL';

    /**
     * The weaker ratio, more letter will be masked
     */
    const RATIO = 1.5;

    public function mask($string, $type = self::SIMPLE_STRING)
    {
        switch ($type) {
            case self::SIMPLE_STRING: {
                return $this->maskString($string);
                break;
            }
            case self::EMAIL: {
                return $this->maskEmail($string);
                break;
            }
            default: {
                throw new \InvalidArgumentException('Invalid type.');
            }
        }
    }

    /**
     * Masks the given string if MASK_STRING flag is set
     */
    protected function maskString(string $string)
    {
        return $this->maskSingleString($string);
    }

    /**
     * Masks the given email if MASK_EMAIL flag is set
     *
     * @throws \InvalidArgumentException if given string is not an e-mail address
     */
    protected function maskEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Given string isn\'t an email.');
        }

        preg_match('/(.*)[@](.*)[.](.*)/', $email, $matches);
        $splitEmail = array_slice($matches, 1, 3);

        $maskedElements = [];
        foreach ($splitEmail as $key => $value) {
            if (end($splitEmail) === $value) continue;

            $maskedElements[] = $this->maskSingleString($value);
        }

        return $maskedElements[0] . '@' . $maskedElements[1] . '.' . $splitEmail[2];
    }

    protected function maskSingleString(string $string)
    {
        $stringLength = strlen($string);
        $countLettersToMask = $stringLength / self::RATIO;
        $startPos = (int)ceil(($stringLength / 2) - ($countLettersToMask / 2));

        for ($i = $startPos; $i <= $countLettersToMask; $i++) {
            $string[$i] = '*';
        }

        return $string;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('mask', [
                $this, 'mask'
            ])
        ];
    }
}