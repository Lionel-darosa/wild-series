<?php

namespace App\Controller;

use App\Entity\Actor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Builder\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actor', name: 'actor_')]
class ActorController extends AbstractController
{
    #[Route('/{id<\d+>}', methods: ['GET'], name: 'show')]
    public function show(Actor $actor): Response
    {
        if (!$actor) {
            throw $this->createNotFoundException(
                'No actor with id : '.$actor->getId().' found in program\'s table.'
            );
        }
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }
}