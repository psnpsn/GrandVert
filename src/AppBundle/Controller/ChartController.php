<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 26/02/2019
 * Time: 21:47
 */

namespace AppBundle\Controller;


use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ChartController extends Controller
{
    public function chartAction()
     {
         $date=new \DateTime();
         $dat=$date->format('Y');
         $em=$this->getDoctrine()->getManager();
         $commandes=$em->getRepository('AchatBundle:Commande')->findAll();$a=0;$b=0;$c=0;$d=0;$f=0;
         foreach ( $commandes as  $commande){
             if($commande->getDate()->format('Y')==$dat)$a=$a+1;
            elseif ($commande->getDate()->format('Y')==$dat-1)$b=$b+1;
             elseif ($commande->getDate()->format('Y')==$dat-2)$c=$c+1;
             elseif ($commande->getDate()->format('Y')==$dat-3)$d=$d+1;
             else $f=$f+1;

         }
         $bar = new BarChart();
         $bar->getData()->setArrayToDataTable([
             ['Year', 'nombre des commande'],
             [$dat, $a],
             [$dat-1, $b],
             [$dat-2,$c],
             [$dat-3, $d],
             ['autre annnée', $f]
         ]);
         $bar->getOptions()->setTitle('nombre des commande par rapport au 4 années précédent');
         $bar->getOptions()->getTitleTextStyle()->setBold(true);
         $bar->getOptions()->getTitleTextStyle()->setColor('#0000ff');
         $bar->getOptions()->getTitleTextStyle()->setItalic(true);
         $bar->getOptions()->getTitleTextStyle()->setFontName('Arial');
         $bar->getOptions()->getTitleTextStyle()->setFontSize(20);
         $bar->getOptions()->getHAxis()->setMinValue(0);
         $bar->getOptions()->getVAxis()->setTitle('Année');
         $bar->getOptions()->getHAxis()->setTitle('nombre de commande');
         $bar->getOptions()->setWidth(900);
         $bar->getOptions()->setHeight(500);
         $plantes=$em->getRepository('PlanteBundle:plante')->findAll();$e=0;$g=0;$h=0;$r=0;
         foreach ( $plantes as  $plante){
             if($plante->getProposition()==1){
             if($plante->getCategorie()=="Legume")$e=$e+1;
             elseif ($plante->getCategorie()=="Fruit")$g=$g+1;
             elseif ($plante->getCategorie()=="Herbe")$h=$h+1;
             elseif ($plante->getCategorie()=="Fleur")$r=$r+1;
             }
         }
         $total=count($plantes);
         $pieChart = new PieChart();
         $pieChart->getData()->setArrayToDataTable(
             [['Task', 'Hours per Day'],
                 ['Legume',    $e*100/$total],
                 ['Fruit',      $g*100/$total],
                 ['Herbe',  $h*100/$total],
                 ['Fleur', $r*100/$total]
             ]
         );
         $pieChart->getOptions()->setTitle('pourcentage des plantes par catégorie');
         $pieChart->getOptions()->setHeight(500);
         $pieChart->getOptions()->setWidth(900);
         $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
         $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
         $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
         $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
         $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
    return $this->render('User/chart.html.twig' ,array('col' =>  $bar,'pie'=>$pieChart));
}

}