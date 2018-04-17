<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 */
class UserController extends Controller
{
    /**
     * @return Response
     */
    public function profile()
    {
        $user = $this->getUser();

        return $this->render(
            'Admin/User/profile.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function changeEmail(Request $request)
    {
        $user = $this->getUser();

        if (empty($request->get('email'))) {
            $this->addFlash('danger', 'No new email entered!');
        } elseif ($request->get('email') == $user->getEmail()) {
            $this->addFlash('danger', 'New email is same as old one!');
        } else {
            $user->setEmail($request->get('email'));
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Email changed!');
        }

        return $this->redirectToRoute('user_profile');
    }
}
