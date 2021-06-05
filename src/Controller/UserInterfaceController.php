<?php


namespace App\Controller;


use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserInterfaceController
 * @package App\Controller
 * @Route("userinterface/", name="userinterface_")
 */
class UserInterfaceController extends AbstractController
{
    /**
     * @param ChartBuilderInterface $chartBuilder
     * @return Response
     * @Route("", name="index")
     */
    public function index(ChartBuilderInterface $chartBuilder):Response
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([/* ... */]);

        return $this->render('userInterface/index.html.twig',[
            'chart' => $chart,
        ]);
    }

}