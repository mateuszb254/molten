<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    /**
     * default_locale defined in translation.yaml
     *
     * @var string $defaultLocale
     */
    private $defaultLocale;

    /**
     * possible_locales defined in services.yaml
     *
     * @var array $possibleLocales
     */
    private $possibleLocales;

    /**
     * @var UrlGeneratorInterface $urlGenerator
     */
    private $urlGenerator;

    /**
     * LocaleSubscriber constructor.
     * @param string $defaultLocale
     * @param array $possibleLocales
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(string $defaultLocale, array $possibleLocales, UrlGeneratorInterface $urlGenerator)
    {
        $this->defaultLocale = $defaultLocale;
        $this->urlGenerator = $urlGenerator;
        $this->possibleLocales = $possibleLocales;
    }

    /**
     * Method set locale
     *
     * If preferred locale is not set, it takes information from Http Header "Accept-Language". In case, we cannot match
     * any supported language it will be set as 'kernel.default_locale'.
     *
     * If there is an _locale get parameter, it will be validated. If _locale is not found in the list of supported
     * languages, it will be set as 'kernel.default_locale'.
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest()) {
            return false;
        }

        if (!$request->getSession()->get('_locale')) {
            $request->getSession()->set('_locale', $this->findSupportedLocaleFromPreferred($request->getLanguages()) ?? $this->defaultLocale);
        }

        if ($locale = $request->get('_locale')) {
            $request->getSession()->set('_locale', $this->isLocaleSupported($locale) ? $locale : $this->findSupportedLocaleFromPreferred($request->getLanguages()) ?? $this->defaultLocale);

            return $event->setResponse(new RedirectResponse($this->urlGenerator->generate($request->attributes->get('_route'))));
        }

        $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
    }

    /**
     * It checks given string is supported locale
     *
     * @param string $locale
     * @return bool
     */
    private function isLocaleSupported(string $locale): bool
    {
        foreach ($this->possibleLocales as $possibleLocale) {
            if ($possibleLocale === $locale) return true;
        }

        return false;
    }

    /**
     * It tries to find first supported language from Accept-Language http header
     *
     * @param array $preferredLanguages
     * @return null|string
     */
    private function findSupportedLocaleFromPreferred(array $preferredLanguages): ?string
    {
        foreach ($preferredLanguages as $preferredLanguage) {
            foreach ($this->possibleLocales as $possibleLocale) {
                if ($preferredLanguage === $possibleLocale) return $preferredLanguage;
            }
        }

        return null;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                'onKernelRequest', 18
            ]
        ];
    }
}