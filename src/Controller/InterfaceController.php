<?php


namespace App\Controller;


use App\Repository\IncomeRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InterfaceController
 * @package App\Controller
 * @Route("userinterface/", name="userinterface_")
 */
class InterfaceController extends AbstractController
{
    /**
     * @param Request $request
     * @param ChartBuilderInterface $chartBuilder
     * @param IncomeRepository $incomeRepository
     * @return Response
     * @Route("", name="index")
     */
    public function index(Request $request,ChartBuilderInterface $chartBuilder, IncomeRepository $incomeRepository):Response
    {



        $labels = [];
        $datePeriod = null;

        $datasets3 = [];
        $datasets4 = [];

        $data1 = $incomeRepository->dataSumByType12($this->getUser()->getUsername());
        $data2 = $incomeRepository->dataSumByType02($this->getUser()->getUsername());


        /* Put date and total sum in two subdata */
        foreach ($data1 as $data) {
            $dataSubSetsType1[$data['date']] = floatval($data["total"]);
        }

        foreach ($data2 as $data) {
            $dataSubSetsType2[$data['date']] = floatval($data["total"]);
        }

        $defaultData = ['message' => 'Sélectionner un intervalle de date :'];

        $form = $this->createFormBuilder($defaultData)
            ->add('date_start', DateType::class,[
                'label' => 'Date de début',
                'widget' => 'single_text',
                'html5' => false,
                'input' => 'string',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('date_end', DateType::class,[
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'html5' => false,
                'input' => 'string',
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('refresh', SubmitType::class, [
                'label' => 'Actualiser'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dataGetInterval = $form->getData();

            try {
                $datePeriod = new DatePeriod(
                    new DateTime($dataGetInterval['date_start']),
                    new DateInterval('P1D'),
                    new DateTime($dataGetInterval['date_end'])
                );
            } catch (Exception $e) {
                echo 'Une exception à été lancé l\'erreur est la suivante' . $e->getMessage();
            }

            $i = 0;
            foreach ($datePeriod as $key => $dates) {
                $labels[$i++] = $dates->format('Y-m-d');
            }
        }


        foreach ($labels as $key => $dates) {

            if( array_key_exists($dates,$dataSubSetsType1) ) {
                $datasets3[] = $dataSubSetsType1[$dates];
            } else {
                $datasets3[] = 0;
            }

            if( array_key_exists($dates,$dataSubSetsType2) ) {
                $datasets4[] = $dataSubSetsType2[$dates];
            } else {
                $datasets4[] = 0;
            }

        }



        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $datasets4,
                ],
                [
                    'label' => 'My Second dataset',
                    'backgroundColor' => null,
                    'borderColor' => 'rgb(132, 255, 99)',
                    'data' => $datasets3
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
            "form" => $form->createView(),
        ]);
    }

}