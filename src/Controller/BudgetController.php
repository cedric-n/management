<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BudgetController extends AbstractController
{

    /**
     * @return Response
     * @Route("budget", name="home_budget")
     */
    public function index(): Response
    {

        return $this->render('main/index.html.twig');
    }

}