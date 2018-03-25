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
     * @param                  $language
     * @return string
     */
    public function changeLanguage(LanguageModifier $modifier, $language)
    {
        return new Response($modifier->getUrl($language));
    }
}
