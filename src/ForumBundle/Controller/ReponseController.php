<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Reponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

            return new RedirectResponse($router->generate("consulter_sujet",['id' => $id]), 307);

        }else{
            // si il n'ya pas un utilisateur connecter , redigier vers page login
            return new RedirectResponse($router->generate('fos_user_security_login'), 307);
        }

    }

    public function supprimerAction(Request $request)
    {
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');


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

        return new RedirectResponse($router->generate("consulter_sujet",['id' => $id_sujet]), 307);

    }

    public function editAction(Request $request) {

        //recuperer l'id de reponse à modifier
        $id_reponse = $request->get("id_reponse");
        $em = $this->getDoctrine()->getManager();
        $reponse = $em->getRepository("ForumBundle:Reponse")->find($id_reponse);

        //recuperer l'id de sujet
        $id_sujet = $request->get("id_sujet");
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id_sujet);


        return $this->render('@Forum/Reponse/modifier.html.twig' , ["reponse" => $reponse ,"sujet" => $sujet ]);

    }


    public function modifierAction(Request $request) {

        //recuperer l'id de reponse à modifier
        $id = $request->get("id_reponse");
        $em = $this->getDoctrine()->getManager();
        $reponse = $em->getRepository("ForumBundle:Reponse")->find($id);

        //modifier leur données
        $reponse->setReponseOriginal($request->request->get('reponseoriginalM')); //recuperer le contenue du reponse sasie par l'utilisateur connecté
        $reponse->setReponseEdited("true");
        $reponse->setDateRedited(new \DateTime());

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($reponse);
        $em->flush();

        //recuperer l'id de sujet
        $id_sujet = $request->get("id_sujet");
        $router = $this->container->get('router');
        return new RedirectResponse($router->generate('consulter_sujet' ,['id' => $id_sujet]), 307);

    }

}
