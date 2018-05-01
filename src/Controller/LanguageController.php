<?php

namespace App\Controller;

use App\Service\Language\LanguageModifier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LanguageController
 */
class LanguageController extends Controller
{
    /**
     * @param LanguageModifier $modifier
     * @param string           $language
     * @return Response
     */
    public function changeLanguage(LanguageModifier $modifier, $language): Response
    {
        return new Response($modifier->getUrl($language));
    }
}
