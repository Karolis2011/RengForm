<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 */
class UserController extends Controller
{
    /**
     * @Route("/profile", name="user_profile")
     * @return Response
     */
    public function profile()
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render(
            'Admin/User/profile.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * @Route("/change_email", name="change_email")
     * @param Request $request
     * @return RedirectResponse
     */
    public function changeEmail(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        if (empty($request->get('email'))) {
            $this->addFlash('danger', 'No new emal entered!');
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
