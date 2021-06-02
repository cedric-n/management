<?php

namespace App\Controller;

use App\Entity\Income;
use App\Form\IncomeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


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
     * @Route("new/", name="new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request) : Response
    {
        $income = new Income();

        $form = $this->createForm(IncomeType::class, $income);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($income);

            $entityManager->flush();

            return $this->redirectToRoute('income_index');
        }

        return $this->render(
            'income/new.html.twig',
            ["form" => $form->createView()]
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

    /**
     * @Route("edit/{id<^[0-9]+$>}", name="edit")
     * @ParamConverter("income", class="App\Entity\Income", options={"mapping": {"id": "id"}})
     * @param Request $request
     * @param Income $income
     * @return Response
     */
    public function edit(Request $request, Income $income): Response
    {

        $form = $this->createForm(IncomeType::class, $income);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('income_index');
        }

        return $this->render('income/edit.html.twig',[
            'income' => $income,
            'form' => $form->createView(),
        ]);

    }


}