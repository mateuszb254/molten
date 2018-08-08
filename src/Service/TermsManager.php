<?php

namespace App\Service;

/**
 * This class is used for fetching and editing terms on site
 *
 * Class TermsManager
 * @package App\Service
 */
class TermsManager
{
    /**
     * Path of terms file. Configured in service.yaml
     *
     * @var string
     */
    protected $termsPath;

    /**
     * If term templates does not exist it creates
     *
     * TermsManager constructor.
     * @param string $termsPath
     */
    public function __construct(string $termsPath)
    {
        if (!file_exists($termsPath)) {
            file_put_contents($termsPath, '');
        }

        $this->termsPath = $termsPath;
    }

    /**
     * Returns existing html of terms
     *
     * @return string
     */
    public function getTerms(): string
    {
        return file_get_contents($this->termsPath);
    }

    /**
     * Set given string as terms content
     *
     * @param string $terms
     * @return int
     */
    public function setTerms(string $terms)
    {
        return file_put_contents($this->termsPath, $terms);
    }
}