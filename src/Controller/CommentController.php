<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment', name: 'comment_')]
class CommentController extends AbstractController
{
    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Comment $comment, Request $request, CommentRepository $commentRepository): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->save($comment, true);

            return $this->redirectToRoute('program_episode_show', [
                'program_slug' => $comment->getEpisode()->getSeason()->getProgram()->getSlug(),
                'season' => $comment->getEpisode()->getSeason()->getId(),
                'episode_slug' => $comment->getEpisode()->getSlug(),
            ]);
        }
        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Comment $comment, Request $request, CommentRepository $commentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);

            $this->addFlash('danger', 'The comment has been deleted');
        }

        return $this->redirectToRoute('program_episode_show', [
            'program_slug' => $comment->getEpisode()->getSeason()->getProgram()->getSlug(),
            'season' => $comment->getEpisode()->getSeason()->getId(),
            'episode_slug' => $comment->getEpisode()->getSlug(),
        ]);
    }
}