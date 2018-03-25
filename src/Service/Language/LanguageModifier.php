<?php

namespace App\Service\Language;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class LanguageModifier
 */
class LanguageModifier
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * LanguageModifier constructor.
     * @param RequestStack          $requestStack
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $generator)
    {
        $this->requestStack = $requestStack;
        $this->generator = $generator;
    }

    /**
     * @param string $language
     * @return string
     */
    public function getUrl(string $language): string
    {
        $route = $this->requestStack->getMasterRequest()->get('_route');
        $routeParams = $this->requestStack->getMasterRequest()->get('_route_params');
        $routeParams['_locale'] = $language;
        $url = $this->generator->generate($route, $routeParams);

        return $url;
    }
}
