<?php

namespace App\Controller;

use App\Entity\Income;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class IncomeController
 * @package App\Controller
 * @Route("incomes/", name="income_")
 */
class IncomeController extends AbstractController
{

    /**
     * @return Response
     * @Route("", name="index")
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
     * @param Income $income
     * @return Response
     * @Route("show/{id<^[0-9]+$>}", name="show")
     */
    public function show(Income $income): Response
    {

        if(!$income) {
            throw $this->createNotFoundException(
                'No income with id : ' . $income->getId() . ' found in income table'
            );
        }

        return $this->render(
            'income/show.html.twig',
            ['income' => $income]
        );
    }


}