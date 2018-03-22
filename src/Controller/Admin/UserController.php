<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class UserController
 */
class UserController extends Controller
{

    /**
     * @Route("/profile", name="user_profile")
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
}
