<?php

namespace App\Validator\Constraints;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * This class verify recaptcha
 *
 * More https://developers.google.com/recaptcha/docs/verify
 */
class RecaptchaValidator extends ConstraintValidator
{
    /**
     * Google's API url
     *
     * @var $apiUrl
     */
    private $apiUrl;

    /**
     * Public key provided by Google
     * Set in service.yaml as 'repcatcha.public_key'
     *
     * @var $secretKey
     */
    private $secretKey;

    /**
     * Specifies if recaptcha should be checked
     * Set in service.yaml as 'recaptcha.enabled'
     *
     * @var $enabled
     */
    private $enabled;

    /**
     * @var RequestStack $requestStack
     */
    private $requestStack;

    /**
     * RecaptchaValidator constructor.
     * @param string $apiUrl
     * @param string $secretKey
     * @param bool $enabled
     * @param RequestStack $requestStack
     */
    public function __construct(string $apiUrl, string $secretKey, bool $enabled, RequestStack $requestStack)
    {
        $this->apiUrl = $apiUrl;
        $this->secretKey = $secretKey;
        $this->enabled = $enabled;
        $this->requestStack = $requestStack;
    }

    /**
     * Takes g-recaptcha-response and sends it to google's api for verifying
     *
     * @param mixed $value
     * @param Constraint $constraint
     * @return bool
     */
    public function validate($value, Constraint $constraint)
    {
        $request = $this->requestStack->getMasterRequest();

        /** Do not validate if recatpcha is disabled */
        if (!$this->enabled) return true;

        $gRecaptchaResponse = $request->get('g-recaptcha-response');

        if (!$this->getGoogleResponse($gRecaptchaResponse)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }

    /**
     * Takes response from google's api
     *
     * @param string $gRecaptchaResponse
     * @return bool
     */
    private function getGoogleResponse(string $gRecaptchaResponse): bool
    {
        if (!$gRecaptchaResponse) return false;

        $googleResponseJson = file_get_contents($this->apiUrl . '?secret=' . $this->secretKey . '&response=' . $gRecaptchaResponse);
        $googleResponse = json_decode($googleResponseJson);

        return $googleResponse->success;
    }
}