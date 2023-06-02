<?php

namespace App\Controller;

use App\Entity\Statement;
use App\Form\StatementType;
use App\Repository\StatementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/statement')]
class StatementController extends AbstractController
{
    #[Route('/', name: 'app_statement_index', methods: ['GET'])]
    public function index(StatementRepository $statementRepository): Response
    {
        return $this->render('statement/index.html.twig', [
            'statements' => $statementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_statement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StatementRepository $statementRepository): Response
    {
        $statement = new Statement();
        $form = $this->createForm(StatementType::class, $statement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statementRepository->save($statement, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('statement/new.html.twig', [
            'statement' => $statement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statement_show', methods: ['GET'])]
    public function show(Statement $statement): Response
    {
        return $this->render('statement/show.html.twig', [
            'statement' => $statement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_statement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Statement $statement, StatementRepository $statementRepository): Response
    {
        $form = $this->createForm(StatementType::class, $statement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statementRepository->save($statement, true);

            return $this->redirectToRoute('app_statement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('statement/edit.html.twig', [
            'statement' => $statement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statement_delete', methods: ['POST'])]
    public function delete(Request $request, Statement $statement, StatementRepository $statementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statement->getId(), $request->request->get('_token'))) {
            $statementRepository->remove($statement, true);
        }

        return $this->redirectToRoute('app_statement_index', [], Response::HTTP_SEE_OTHER);
    }
}
