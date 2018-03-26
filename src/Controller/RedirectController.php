<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Class RedirectController
 */
class RedirectController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function redirectAction(Request $request): Response
    {
        $url = $request->getRequestUri();
        $languages = $this->getParameter('locales');

        if (in_array(substr($url, 1, 2), $languages)) {
            throw new RouteNotFoundException(
                sprintf(
                    'Route not found: %s',
                    $url
                )
            );
        }

        $language = $this->getParameter('locale');
        $url = '/' . $language . $url;

        return $this->redirect($url);
    }
}
