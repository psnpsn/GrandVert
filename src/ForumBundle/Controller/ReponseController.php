<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Reponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReponseController extends Controller
{
    public function ajouterAction(Request $request) {

        //verifier si il ya un utilisateur connecter ou non pour faire l'ajout
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        if (($authChecker->isGranted('ROLE_ADMIN')) || ($authChecker->isGranted('ROLE_USER')) ) {

            $reponse = new Reponse();

            $reponse->setReponseOriginal($request->request->get('commentoriginal'));
            $reponse->setReponseEdited("");
            $reponse->setDateROriginal(new \DateTime());
            $reponse->setDateREdited(new \DateTime());

            //recuperer l'id de sujet pour affecter au reponse
            $id = $request->get("id");
            $em = $this->getDoctrine()->getManager();
            $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

            $reponse->setSujet($sujet);

            //recuperer l'uilisateur connecter qui va ajouter un sujet
            $user = $this->getUser();
            $sujet->setUser($user);

            $reponse->setUser($user);


            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($reponse);
            $em->flush();

            //recuperer les reponses de sujet à consulter pour actualiser les info aprés l'ajout
            $reponses=$em->getRepository("ForumBundle:Reponse")->findBy(['Sujet'=> $sujet ]);

            return $this->render('@Forum/Sujet/consulter.html.twig', ["sujet" => $sujet , "reponses" => $reponses ,"User" => $user]);

        }else{
            // si il n'ya pas un utilisateur connecter , redigier vers page login
            return new RedirectResponse($router->generate('fos_user_security_login'), 307);
        }

    }

    public function supprimerAction(Request $request)
    {
        $authChecker = $this->container->get('security.authorization_checker');

        //recuperer id du reponse à supprimer
        $id_reponse = $request->get('id_reponse');

        //recuperer le sujet à supprimer à partir de leur id
        $em = $this->getDoctrine()->getManager();
        $reponse = $em->getRepository("ForumBundle:Reponse")->find($id_reponse);

        //remove reponse
        $em->remove($reponse);
        $em->flush();

        //recuperer id du sujet
        $id_sujet = $request->get('id_sujet');
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id_sujet);

        //recuperer les reponses de sujet à consulter
        $reponses=$em->getRepository("ForumBundle:Reponse")->findBy(['Sujet'=> $sujet ]);

        if (($authChecker->isGranted('ROLE_ADMIN')) || ($authChecker->isGranted('ROLE_USER')) ) {
            //recuperer l'uilisateur connecter
            $id = $this->getUser();
            $user = $em->getRepository("AppBundle:User")->find($id);
        }else {
            $user = null;
        }

        return $this->render('@Forum/Sujet/consulter.html.twig', ["sujet" => $sujet , "reponses" => $reponses ,"User" => $user]);

    }

}
