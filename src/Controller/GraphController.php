<?php

namespace App\Controller;

use App\Entity\Graph;
use App\Form\GraphType;
use App\Repository\GraphRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/graph")
 */
class GraphController extends AbstractController
{
    /**
     * @Route("/", name="graph_index", methods={"GET"})
     */
    public function index(GraphRepository $graphRepository): Response
    {
        return $this->render('graph/index.html.twig', [
            'graphs' => $graphRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="graph_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $graph = new Graph();
        $form = $this->createForm(GraphType::class, $graph);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($graph);
            $entityManager->flush();

            return $this->redirectToRoute('graph_index');
        }

        return $this->render('graph/new.html.twig', [
            'graph' => $graph,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="graph_show", methods={"GET"})
     */
    public function show(Graph $graph): Response
    {
        return $this->render('graph/show.html.twig', [
            'graph' => $graph,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="graph_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Graph $graph): Response
    {
        $form = $this->createForm(GraphType::class, $graph);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('graph_index');
        }

        return $this->render('graph/edit.html.twig', [
            'graph' => $graph,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="graph_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Graph $graph): Response
    {
        if ($this->isCsrfTokenValid('delete'.$graph->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($graph);
            $entityManager->flush();
        }

        return $this->redirectToRoute('graph_index');
    }
}
