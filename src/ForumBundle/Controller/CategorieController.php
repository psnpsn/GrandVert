<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends Controller
{
    public function afficherAction()
    {
        //afficher tous les categories
        $categories = array("Fruit" , "Herbe" , "Fleur" , "Légume");

        $em = $this->getDoctrine()->getManager();
        $notifications = $em->getRepository("ForumBundle:Notification")->findBy(["User" => $this->getUser() , "seen" => false]);


        return $this->render('@Forum\Categorie\list.html.twig' , ["categories" => $categories ,"notifications" => $notifications , "count" => count($notifications) ]);
    }

    public function consulterAction(Request $request)
    {
        //recuperer le categorie
        $categorie = $request->get("categorie");

        //recuperer tous les plante qui apartient à la categorie selectionner
        $em = $this->getDoctrine()->getManager();
        $plantes = $em->getRepository("PlanteBundle:plante")->findBy(['categorie'=> $categorie ]);

        //pagination data
        $paginationplantes  = $this->get('knp_paginator')->paginate(
            $plantes,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            7/*nbre d'éléments par page*/
        );

        $nbsujets = array(
            array('plante' => "" , 'nbsujetp' => "")
        );
        //parcourir la liste de plante
        for($i = 0; $i < count($plantes); ++$i) {
            //recuperer tous les sujets de plante
            $sujets=$em->getRepository("ForumBundle:Sujet")->findBy(['Plante'=> $plantes[$i] ]);

            array_push($nbsujets, array('plante' => $plantes[$i] , 'nbsujetp' => count($sujets)));
        }

        return $this->render('@Forum\Categorie\consulter.html.twig', ["plantes" => $paginationplantes ,"nbsujets" => $nbsujets ]);
    }
}
