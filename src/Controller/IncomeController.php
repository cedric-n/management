<?php

namespace App\Controller;

use App\Entity\Income;
use App\Form\IncomeType;
use App\Repository\UserRepository;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


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
    public function index(UserRepository $userRepository): Response
    {
        $incomes = $this->getDoctrine()
            ->getRepository(Income::class)
            ->findAll();

        return $this->render(
            'income/index.html.twig',[
                'incomes' => $incomes,
            ]);
    }


    /**
     * @Route("new/", name="new")
     * @param Request $request
     * @param Slugify $slugify
     * @return Response
     */
    public function new(Request $request, Slugify $slugify) : Response
    {
        $income = new Income();

        $form = $this->createForm(IncomeType::class, $income);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugify->generate($income->getName());
            $income->setSlug($slug);
            $income->setOwner($this->getUser());

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
     * @Route("edit/{id<^[0-9]+$>}", name="edit", methods={"GET","POST"})
     * @ParamConverter("income", class="App\Entity\Income", options={"mapping": {"id": "id"}})
     * @param Request $request
     * @param Income $income
     * @return Response
     */
    public function edit(Request $request, Income $income): Response
    {

        if (!($this->getUser() == $income->getOwner())) {

            throw new AccessDeniedException('Vous n\'avez pas accès à ces données' );
        }

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

    /**
     * @Route("delete/{id<^[0-9]+$>}", name="delete", methods={"DELETE"})
     * @ParamConverter("income", class="App\Entity\Income", options={"mapping": {"id": "id"}})
     * @param Request $request
     * @param Income $income
     * @return Response
     */
    public function delete(Request $request, Income $income): Response
    {
        if ($this->isCsrfTokenValid('delete' . $income->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($income);
            $entityManager->flush();
            $this->addFlash('danger', 'Le budget a bien été supprimé');
        }

        return  $this->redirectToRoute('income_index');
    }


}