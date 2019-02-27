<?php

namespace AppBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    public function listAction(Request $request)
    {  $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em= $this->getDoctrine()->getManager();
        $Membres=$em->getRepository("AppBundle:User")->findAll();

        return $this->render('membre.html.twig' , ["Membres" => $Membres,"user" => $user]);
    }

    public function etatAction(Request $request)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $membre = $em->getRepository("AppBundle:User")->find($id);

        $etat= $membre->isEnabled();

        if($etat == 1)
            $membre->setEnabled(0);
        else
            $membre->setEnabled(1);
        $em->persist($membre);
        $em->flush();

        //rediger l'utilisateur vers leur interface apres le changement de l'etat
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        //verfier si l'utilisateur connecter est un admin pour acceder à admin dashboard
        if ($authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            return new RedirectResponse($router->generate('list_user/admin'), 307);

        }

        //verfier si l'utilisateur connecter est un moderateur pour acceder à son dashboard
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            return new RedirectResponse($router->generate('list_user/moderateur'), 307);
        }

    }

    public function consulterAction(Request $request)
    {
        $id = $request->get("id");
        $em= $this->getDoctrine()->getManager();
        $user=$em->getRepository("AppBundle:User")->find($id);

        return $this->render('User/consulter_membre.html.twig' , ["user" => $user]);
    }

    public function accountAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('User/account.html.twig' , ["user" => $user]);
    }

    public function settingsAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('User/account.html.twig' , ["user" => $user]);
    }


    public function preferencesAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('User/account.html.twig' , ["user" => $user]);
    }

    public function profileadAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('User/profilead.html.twig' , ["user" => $user]);
    }

    public function homeadminAction()
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
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('admin_dashboard.html.twig',array('user'=>$user,'bar' =>  $bar,'pie'=>$pieChart));
    }

    public function homemembreAction()
    {
        $em = $this->getDoctrine()->getManager();
        $notifications = $em->getRepository("ForumBundle:Notification")->findBy(["User" => $this->getUser() , "seen" => false]);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('default/index.html.twig' , ["notifications" => $notifications , "count" => count($notifications),"user"=>$user]);
    }

    public function homemoderateurAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('moderateur_dashboard.html.twig' , ["user" => $user]);
    }

    public function rolemembreAction(Request $request)
    {
        //recuperer id de membre pour changer son role
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $membre = $em->getRepository("AppBundle:User")->find($id);

        $rolesArr = array('ROLE_USER');

        $membre->setRoles($rolesArr); //set role to role membre

        $em->persist($membre);
        $em->flush();

        return $this->redirectToRoute("list_user/admin");
    }

    public function rolemoderateurAction(Request $request)
    {
        //recuperer id de membre pour changer son role
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $membre = $em->getRepository("AppBundle:User")->find($id);

        $rolesArr = array('ROLE_ADMIN');

        $membre->setRoles($rolesArr); //set role to role mederateur

        $em->persist($membre);
        $em->flush();

        return $this->redirectToRoute("list_user/admin");
    }

    public function listmembreAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em= $this->getDoctrine()->getManager();

        $rolesArr = array('ROLE_USER');

        //recuperer tous les membres
        $query = $this->getDoctrine()->getEntityManager()
            ->createQuery(
                'SELECT u FROM AppBundle:User u WHERE u.roles LIKE :role'
            )->setParameter('role', 'a:0:{}');

        $Membres = $query->getResult();

        //pagination data
        $paginationsujets  = $this->get('knp_paginator')->paginate(
            $Membres,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            5/*nbre d'éléments par page*/
        );

        return $this->render('Listmembre.html.twig' , ["Membres" => $paginationsujets,"user" => $user]);
    }


}
