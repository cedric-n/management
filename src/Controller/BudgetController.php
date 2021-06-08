<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\BudgetType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


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
    public function new(Request $request, Slugify $slugify) : Response
    {
        $budget = new Budget();

        $form = $this->createForm(BudgetType::class, $budget );

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $slug = $slugify->generate($budget->getName());
            $budget->setSlug($slug);

            $budget->setUserLink($this->getUser());

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
     * @Route("show/{budgetName}/", name="show")
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

    /**
     * @Route("edit/{budgetName}/", name="edit", methods={"GET","POST"})
     * @ParamConverter("budget", class="App\Entity\Budget", options={ "mapping": {"budgetName": "name"}})
     * @param Request $request
     * @param Budget $budget
     * @return Response
     */
    public function edit(Request $request, Budget $budget): Response
    {

        if (!($this->getUser() == $budget->getUserLink())) {
            throw new AccessDeniedException('Vous accédez à une donnée qui n\'est pas votre !');
        }

        $form = $this->createForm(BudgetType::class, $budget);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('budget_index');
        }

        return $this->render('budget/edit.html.twig',[
            'budget' => $budget,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/delete/{budgetName}/", name="delete", methods={"DELETE"})
     * @ParamConverter("budget", class="App\Entity\Budget", options={ "mapping": {"budgetName": "name"}})
     * @param Request $request
     * @param Budget $budget
     * @return Response
     */
    public function delete(Request $request, Budget $budget): Response
    {
        if ($this->isCsrfTokenValid('delete' . $budget->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($budget);
            $entityManager->flush();
            $this->addFlash('danger', 'Le budget a bien été supprimé');
        }

        return  $this->redirectToRoute('budget_index');
    }

}