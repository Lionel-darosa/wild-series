<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/my-profile/{id}', name:'user_profile')]
    public function show(User $user): Response
    {
        if ($user == $this->getUser()) {
            return $this->render('user/profile.html.twig', [
                'user' => $user,
            ]);
        } else {
            return $this->redirectToRoute('program_index');
        }
    }
}