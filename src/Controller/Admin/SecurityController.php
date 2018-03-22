<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class RegistrationController
 */
class SecurityController extends Controller
{
    /**
     * @Route("/register", name="register")
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('event_index');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'Security/registration.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authUtils)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('event_index');
        }

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/change_password", name="change_password")
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if (!$passwordEncoder->isPasswordValid($this->getUser(), $request->get('old-password'))) {
            $this->addFlash('danger', 'Old username does not match!');
        } elseif (empty($request->get('new-password'))) {
            $this->addFlash('danger', 'No new password entered!');
        } elseif ($request->get('new-password') != $request->get('new-password-2')) {
            $this->addFlash('danger', 'New password does not match!');
        } elseif ($request->get('new-password') == $request->get('old-password')) {
            $this->addFlash('danger', 'New password is same as old one!');
        } else {
            /** @var User $user */
            $user = $this->getUser();
            $user->setPassword($passwordEncoder->encodePassword($user, $request->get('new-password')));
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Password changed!');
        }

        return $this->redirectToRoute('user_profile');
    }
}
