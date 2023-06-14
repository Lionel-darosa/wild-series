<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Repository\CommentRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Service\ProgramDuration;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request,MailerInterface $mailer, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $program->setOwner($this->getUser());

            $programRepository->save($program, true);

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to($this->getParameter('mailer_from'))
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);

            $this->addFlash('success', 'The new program has been created');

            return $this->redirectToRoute('program_index');
        }

        return $this->render(('program/new.html.twig'), [
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/edit', name:'edit')]
    public function edit(Program $program, Request $request,MailerInterface $mailer, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($this->getUser() !== $program->getOwner()) {
            throw $this->createAccessDeniedException('Only the owner can edit the program');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);

            $programRepository->save($program, true);

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to($this->getParameter('mailer_from'))
                ->subject('La série ' . $program->getTitle() . ' vient d\'être mise à jour !')
                ->html($this->renderView('program/updateProgramEmail.html.twig', ['program' => $program]));
            
            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }

        return $this->render(('program/edit.html.twig'), [
            'form' => $form,
        ]);
    }

    #[Route('/show/{slug}', methods: ['GET'], name: 'show')]
    public function show(Program $program, ProgramDuration $programDuration): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$program->getId().' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program),
        ]);
    }

    #[Route('/{slug}/season/{season}', name: 'season_show')]
    public function showSeason(Program $program, Season $season): Response
    {
        if (!$program || !$season) {
            throw $this->createNotFoundException(
                'No program with id : '.$program->getId().' or no season with id : '. $season->getId() .' found in program\'s table.'
            );
        }
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route('/{program_slug}/season/{season}/episode/{episode_slug}', name: 'episode_show')]
    #[Entity('program', options: ['mapping' => ['program_slug' => 'slug']])]
    #[Entity('episode', options: ['mapping' => ['episode_slug' => 'slug']])]
    public function showEpisode(Program $program, Season $season, Episode $episode, Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $user = $this->getUser();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setEpisode($episode);
            $comment->setAuthor($user);
            $commentRepository->save($comment, true);

            return $this->redirectToRoute('program_episode_show', [
                'program_slug' => $comment->getEpisode()->getSeason()->getProgram()->getSlug(),
                'season' => $comment->getEpisode()->getSeason()->getId(),
                'episode_slug' => $comment->getEpisode()->getSlug(),
            ]);
        }
        if (!$program || !$season || !$episode) {
            throw $this->createNotFoundException(
                'No program with id : '.$program->getId().' or no season with id : '. $season->getId() .' or no episode with id : '. $episode->getId() .' found in program\'s table.'
            );
        }
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'form' => $form,
        ]);
    }
}
