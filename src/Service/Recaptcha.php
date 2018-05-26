<?php

namespace App\Service;

/**
 * This class is used to provide protection against bots
 * more: https://developers.google.com/recaptcha/docs/display
 */
class Recaptcha
{
    private $publicKey;
    private $secretKey;
    private $apiUrl;

    /**
     * Recaptcha constructor.
     * @param string $secretKey
     * @param string $publicKey
     * @param string $apiUrl
     */
    public function __construct(string $secretKey, string $publicKey, string $apiUrl)
    {
        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
        $this->apiUrl = $apiUrl;
    }

    /**
     * Validates re-captcha using Google's API
     *
     * @param string $recaptchaResponse is g-recaptcha-response from the recaptcha form
     * @return boolean
     */
    public function validate(string $recaptchaResponse): bool
    {
        $googleResponseJson = file_get_contents($this->apiUrl . '?secret=' . $this->secretKey . '&response=' . $recaptchaResponse);
        $googleResponse = json_decode($googleResponseJson);

        return $googleResponse->success;
    }

    /**
     * Returns repcaptcha div provided by Google
     *
     * @return string
     */
    public function createView(): string
    {
        $html = '<div class="g-recaptcha" data-theme="dark" data-sitekey="' . $this->publicKey . '"></div>';

        return $html;
    }
}