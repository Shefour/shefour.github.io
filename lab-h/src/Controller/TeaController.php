<?php

namespace App\Controller;

use App\Entity\Tea;
use App\Form\TeaType;
use App\Repository\TeaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tea')]
final class TeaController extends AbstractController
{
    #[Route(name: 'app_tea_index', methods: ['GET'])]
    public function index(TeaRepository $teaRepository): Response
    {
        return $this->render('tea/index.html.twig', [
            'teas' => $teaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tea_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tea = new Tea();
        $form = $this->createForm(TeaType::class, $tea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tea);
            $entityManager->flush();

            return $this->redirectToRoute('app_tea_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tea/new.html.twig', [
            'tea' => $tea,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tea_show', methods: ['GET'])]
    public function show(Tea $tea): Response
    {
        return $this->render('tea/show.html.twig', [
            'tea' => $tea,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tea_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tea $tea, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeaType::class, $tea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tea_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tea/edit.html.twig', [
            'tea' => $tea,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tea_delete', methods: ['POST'])]
    public function delete(Request $request, Tea $tea, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tea->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tea);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tea_index', [], Response::HTTP_SEE_OTHER);
    }
}
