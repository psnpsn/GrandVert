<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Sujet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SujetController extends Controller
{
    public function indexAction()
    {
        return $this->render('ForumBundle:Default:index.html.twig');
    }

    public function ajouterAction(Request $request) {

        $sujet = new Sujet(); //creer un nouveau instance de l'entity sujet

        //saisir leur données
        $sujet->setSujetOriginal($request->request->get('postoriginal')); //recuperer le contenue du sujet sasie par l'utilisateur connecté
        $sujet->setSujetEdited("");
        $sujet->setDateoriginal(new \DateTime());
        $sujet->setDateedited(new \DateTime());
        $sujet->setEtat('open');
        $sujet->setIdPlante(1);

        //recuperer l'uilisateur connecter qui va ajouter un sujet
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $sujet->setUser($user);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($sujet);
        $em->flush();

        return $this->redirect($this->generateUrl('consulter_plante'));

    }



    public function afficherAction(Request $request)
    {
        $id_plante = $request->get("id");

        //recuperer le plante pour trouver leur sujets
        $em= $this->getDoctrine()->getManager();
        $plante = $em->getRepository("PlanteBundle:plante")->find($id_plante);

        //recuperer tous les sujets de plante à consulter
        $sujets=$em->getRepository("ForumBundle:Sujet")->findBy(['Plante'=> $plante ]);

        $NbReponses = array(
            array('sujet' => "" , 'NbReponseC' => "")
        );
        //parcourir la liste de sujet
        for($i = 0; $i < count($sujets); ++$i) {
            //recuperer tous les reponses de sujet
            $reponses=$em->getRepository("ForumBundle:Reponse")->findBy(['Sujet'=> $sujets[$i] ]);

            array_push($NbReponses, array('sujet' => $sujets[$i] , 'NbReponseC' => count($reponses)));
        }

        return $this->render('@Forum/Sujet/afficher.html.twig' , ["sujets" => $sujets , "plante"=> $plante ,"NbReponses" => $NbReponses]);
    }

    public function consulterAction(Request $request)
    {
        $id = $request->get("id"); // get id de sujet consulter

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

        return $this->render('@Forum/Sujet/consulter.html.twig', ["sujet" => $sujet , "reponses" => $reponses ]);
    }

    public function supprimerAction(Request $request)
    {

        //recuperer id du sujet à supprimer
        $id = $request->get('id');

        //recuperer le sujet à supprimer à partir de leur id
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        //remove sujet
        $em->remove($sujet);
        $em->flush();

        return $this->redirect($this->generateUrl('consulter_plante'));
    }



}
