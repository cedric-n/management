<?php

namespace App\Controller;

use App\Entity\Budget;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\BudgetType;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class BudgetController
 * @package App\Controller
 * @Route("/budgets/", name="budget_")
 */
class BudgetController extends AbstractController
{

    /**
     * @return Response
     * @Route("", name="index")
     */
    public function index(): Response
    {

        $budget = $this->getDoctrine()
            ->getRepository(Budget::class)
            ->findAll();


        return $this->render(
            'budget/index.html.twig',
            ['budgets' => $budget]
        );
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("new/", name="new")
     */
    public function new(Request $request) : Response
    {
        $budget = new Budget();

        $form = $this->createForm(BudgetType::class, $budget );

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($budget);

            $entityManager->flush();

            return $this->redirectToRoute('budget_index');
        }

        return $this->render(
            'budget/new.html.twig',
            ["form" => $form->createView()]
        );
    }

    /**
     * @param string $budgetName
     * @return Response
     * @Route("show/{budgetName}", name="show")
     */
    public function show(string $budgetName): Response
    {

        $budget = $this->getDoctrine()
            ->getRepository(Budget::class)
            ->findBy(
                ['name' => $budgetName],
                ['id' => 'ASC']
            );

        if (!$budget) {
            throw $this->createNotFoundException(
                'Any budget exist'
            );
        }


        return $this->render(
            'budget/show.html.twig',
            ['budgets' => $budget]
        );
    }

}