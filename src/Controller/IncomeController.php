<?php

namespace App\Controller;

use App\Entity\Income;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IncomeController extends AbstractController
{

    /**
     * @return Response
     * @Route("income", name="income_home")
     */
    public function index(): Response
    {

        $incomes = $this->getDoctrine()
            ->getRepository(Income::class)
            ->findAll();


        return $this->render(
            'income/index.html.twig',
            ['incomes' => $incomes]
        );
    }

    /**
     *  Getting an income by his id
     *
     * @param int $id
     * @return Response
     * @Route("/show/{id<^[0-9]+$>}", name="show_income")
     */
    public function show(int $id): Response
    {

        $income = $this->getDoctrine()
            ->getRepository(Income::class)
            ->findOneBy(['id' => $id]);

        if(!$income) {
            throw $this->createNotFoundException(
                'No income with id : ' . $id . ' found in income table'
            );
        }

        return $this->render(
            'income/show.html.twig',
            ['income' => $income]
        );
    }


}