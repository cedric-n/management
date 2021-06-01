<?php

namespace App\Controller;

use App\Entity\Budget;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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