<?php

namespace App\Service\Payments\PayPal\Exception;

use Exception;

class ConfigNotSetException extends \Exception
{
    private $keys;

    public function __construct(array $keys, $message = "", $code = 0, Exception $previous = null)
    {
        $this->keys = $keys;

        parent::__construct($message, $code, $previous);

        $this->updateMessage();
    }

    public function updateMessage()
    {
        $this->keys = array_filter($this->keys);
        $this->message = 'You need to set '.implode(', ', $this->keys).' in .env file.';
    }
}