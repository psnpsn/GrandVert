<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Sujet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SujetController extends Controller
{
    public function indexAction()
    {
        return $this->render('ForumBundle:Default:index.html.twig');
    }

    public function ajouterAction(Request $request) {

        //verifier si il ya un utilisateur connecter ou non pour faire l'ajout
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');


        if (($authChecker->isGranted('ROLE_ADMIN')) || ($authChecker->isGranted('ROLE_USER')) ) {
            //recuperer l'id de sujet pour affecter au reponse
            $id = $request->get("id");
            $em = $this->getDoctrine()->getManager();
            $plante = $em->getRepository("PlanteBundle:plante")->find($id);

            $sujet = new Sujet(); //creer un nouveau instance de l'entity sujet

            //saisir leur données
            $sujet->setSujetOriginal($request->request->get('postoriginal')); //recuperer le contenue du sujet sasie par l'utilisateur connecté
            $sujet->setSujetEdited("");
            $sujet->setDateoriginal(new \DateTime());
            $sujet->setDateedited(new \DateTime());
            $sujet->setEtat('open');
            $sujet->setPlante($plante);

            //recuperer l'uilisateur connecter qui va ajouter un sujet
            $user = $this->getUser();
            $sujet->setUser($user);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($sujet);
            $em->flush();

            return new RedirectResponse($router->generate('afficher_sujet' ,['id' => $plante->getId()]), 307);

        }else{
            // si il n'ya pas un utilisateur connecter , redigier vers page login
            return new RedirectResponse($router->generate('fos_user_security_login'), 307);
        }

    }



    public function afficherAction(Request $request)
    {
        $authChecker = $this->container->get('security.authorization_checker');

        $id_plante = $request->get("id");

        //recuperer le plante pour trouver leur sujets
        $em= $this->getDoctrine()->getManager();
        $plante = $em->getRepository("PlanteBundle:plante")->find($id_plante);

        //recuperer tous les sujets de plante à consulter
        $sujets=$em->getRepository("ForumBundle:Sujet")->findBy(['Plante'=> $plante ]);

        //pagination data
        $paginationsujets  = $this->get('knp_paginator')->paginate(
            $sujets,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            3/*nbre d'éléments par page*/
        );

        $NbReponses = array(
            array('sujet' => "" , 'NbReponseC' => "")
        );
        //parcourir la liste de sujet
        for($i = 0; $i < count($sujets); ++$i) {
            //recuperer tous les reponses de sujet
            $reponses=$em->getRepository("ForumBundle:Reponse")->findBy(['Sujet'=> $sujets[$i] ]);

            array_push($NbReponses, array('sujet' => $sujets[$i] , 'NbReponseC' => count($reponses)));
        }

        if (($authChecker->isGranted('ROLE_ADMIN')) || ($authChecker->isGranted('ROLE_USER')) ) {
            //recuperer l'uilisateur connecter
            $id = $this->getUser();
            $user = $em->getRepository("AppBundle:User")->find($id);
        }else {
            $user = null;
        }

        return $this->render('@Forum/Sujet/afficher.html.twig' , ["sujets" => $paginationsujets , "plante" => $plante ,"NbReponses" => $NbReponses ,"User" => $user]);
    }

    public function consulterAction(Request $request)
    {
        $id = $request->get("id"); // get id de sujet consulter

        $authChecker = $this->container->get('security.authorization_checker');


        //recuperer le sujet à consulter
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        //incrementer le nb de view de sujet
        $nbviews= $sujet->getNbviews();
        $sujet->setNbviews($nbviews+1);
        $em->persist($sujet);
        $em->flush();

        //recuperer les reponses de sujet à consulter
        $reponses=$em->getRepository("ForumBundle:Reponse")->findBy(['Sujet'=> $sujet ]);

        //pagination data
        $paginationreponses  = $this->get('knp_paginator')->paginate(
            $reponses,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );

        if (($authChecker->isGranted('ROLE_ADMIN')) || ($authChecker->isGranted('ROLE_USER')) ) {
            //recuperer l'uilisateur connecter
            $id = $this->getUser();
            $user = $em->getRepository("AppBundle:User")->find($id);
        }else {
            $user = null;
        }

        return $this->render('@Forum/Sujet/consulter.html.twig', ["sujet" => $sujet , "reponses" => $paginationreponses ,"User" => $user]);
    }

    public function supprimerAction(Request $request)
    {

        //recuperer id du sujet à supprimer
        $id = $request->get('id');

        //recuperer le sujet à supprimer à partir de leur id
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        //recuperer le plante de sujet a supprimer pour trouver leur sujet aprés la supression
        $em= $this->getDoctrine()->getManager();
        $plante = $em->getRepository("PlanteBundle:plante")->find($sujet->getPlante());

        //remove sujet
        $em->remove($sujet);
        $em->flush();

        return $this->generateUrl("afficher_sujet",['id' => $plante]);

    }

    public function sujetsuserAction(Request $request)
    {
        $authChecker = $this->container->get('security.authorization_checker');
        //recuperer l'uilisateur connecter pour trouver leur sujet
        $id = $this->getUser();
        $em= $this->getDoctrine()->getManager();
        $user = $em->getRepository("AppBundle:User")->find($id);

        //recuperer tous les sujets de l'utilisateru connecter
        $sujets=$em->getRepository("ForumBundle:Sujet")->findBy(['User'=> $user ]);

        //pagination data
        $paginationsujets  = $this->get('knp_paginator')->paginate(
            $sujets,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            3/*nbre d'éléments par page*/
        );

        $NbReponses = array(
            array('sujet' => "" , 'NbReponseC' => "")
        );
        //parcourir la liste de sujet
        for($i = 0; $i < count($sujets); ++$i) {
            //recuperer tous les reponses de sujet
            $reponses=$em->getRepository("ForumBundle:Reponse")->findBy(['Sujet'=> $sujets[$i] ]);

            array_push($NbReponses, array('sujet' => $sujets[$i] , 'NbReponseC' => count($reponses)));
        }

        $plante = null;


        return $this->render('@Forum/Sujet/afficher.html.twig' , ["sujets" => $paginationsujets , "plante" => $plante ,"NbReponses" => $NbReponses ,"User" => $user]);
    }

    public function editAction(Request $request) {

        //recuperer l'id de sujet à modifier
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        return $this->render('@Forum/Sujet/modifier.html.twig' , ["sujet" => $sujet]);

    }


    public function modifierAction(Request $request) {

         //recuperer l'id de sujet à modifier
            $id = $request->get("id");
            $em = $this->getDoctrine()->getManager();
            $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

            //modifier leur données
            $sujet->setSujetOriginal($request->request->get('postoriginalM')); //recuperer le contenue du sujet sasie par l'utilisateur connecté
            $sujet->setSujetEdited("true");
            $sujet->setDateedited(new \DateTime());

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($sujet);
            $em->flush();

            $router = $this->container->get('router');
            return new RedirectResponse($router->generate('consulter_sujet' ,['id' => $id]), 307);

    }



}
