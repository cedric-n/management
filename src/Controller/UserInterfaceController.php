<?php


namespace App\Controller;


use App\Repository\IncomeRepository;
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
     * @param IncomeRepository $incomeRepository
     * @return Response
     * @Route("", name="index")
     */
    public function index(ChartBuilderInterface $chartBuilder, IncomeRepository $incomeRepository):Response
    {

        $labels = [];
        $datasets = [];
        $labels2 = [];
        $datasets2 = [];


        $data1 = $incomeRepository->dataSumByType1($this->getUser()->getUsername());
        $data2 = $incomeRepository->dataSumByType2($this->getUser()->getUsername());


        foreach ($data1 as $data) {

            $labels[] = $data["date"];
            $datasets[] = floatval($data["total"]);
        }

        foreach ($data2 as $data) {

            $labels2[] = $data["date"];
            $datasets2[] = floatval($data["total"]);
        }




        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels,$labels2,
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $datasets,
                ],
                [
                    'label' => 'My Second dataset',
                    'backgroundColor' => null,
                    'borderColor' => 'rgb(132, 255, 99)',
                    'data' => $datasets2
                ],
            ],
        ]);

        $chart->setOptions([/* ... */]);

        $infoType0 = $incomeRepository->totalSumByType0($this->getUser()->getUsername(),0);
        $infoType1 = $incomeRepository->totalSumByType0($this->getUser()->getUsername(),1);
        $wallet = floatval($infoType1[0]["total"]) - floatval($infoType0[0]["total"]);

        return $this->render('userInterface/index.html.twig',[
            'chart' => $chart,
            'infoType0' => $infoType0,
            'infoType1' => $infoType1,
            'wallet' => $wallet,
        ]);
    }

}