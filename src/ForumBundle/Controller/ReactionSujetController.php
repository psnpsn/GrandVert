<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\ReactionSujet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ReactionSujetController extends Controller
{

    public function likeAction(Request $request) {

        //recuperer l'id de sujet à aimer
        $id_sujet = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id_sujet);

        //recuperer l'uilisateur qui va aimer ce sujet
        $id_user = $this->getUser();
        $user = $em->getRepository("AppBundle:User")->find($id_user);

        //chercher si user deja faire un reaction a ce sujet !!
        $reaction = $em->getRepository("ForumBundle:ReactionSujet")->findBy(["Sujet" =>$sujet , "User" => $user]);

        if($reaction != null) {
            if($reaction[0]->getReaction() == "dislike") {
                //remove reaction
                $em->remove($reaction[0]);
                $em->flush();

                //ajouter nouveau reaction de j'aime
                $reaction = new ReactionSujet();
                $reaction->setUser($user);
                $reaction->setSujet($sujet);
                $reaction->setReaction('like');
                $em->persist($reaction);
                $em->flush();
            }
            else
            {
                //remove reaction
                $em->remove($reaction[0]);
                $em->flush();
            }
        }else
        {
            //ajouter nouveau reaction de j'aime
            $reaction = new ReactionSujet();
            $reaction->setUser($user);
            $reaction->setSujet($sujet);
            $reaction->setReaction('like');
            $em->persist($reaction);
            $em->flush();
        }

        $router = $this->container->get('router');
        return new RedirectResponse($router->generate('consulter_sujet' ,['id' => $id_sujet]), 307);

    }


    public function dislikeAction(Request $request) {

        //recuperer l'id de sujet à faire à je n'aime plus
        $id_sujet = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id_sujet);

        //recuperer l'uilisateur qui va aimer ce sujet
        $id_user = $this->getUser();
        $user = $em->getRepository("AppBundle:User")->find($id_user);

        //chercher si user deja faire un reaction a ce sujet !!
        $reaction = $em->getRepository("ForumBundle:ReactionSujet")->findBy(["Sujet" =>$sujet , "User" => $user]);

        if($reaction != null) { //si user deaj ajouter son reaction au sujet
            if($reaction[0]->getReaction() == "like"){
                //remove reaction
                $em->remove($reaction[0]);
                $em->flush();

                //ajouter nouveau reaction de je n'aime plus
                $reaction = new ReactionSujet();
                $reaction->setUser($user);
                $reaction->setSujet($sujet);
                $reaction->setReaction('dislike');
                $em->persist($reaction);
                $em->flush();
            }else
            {
                //remove reaction
                $em->remove($reaction[0]);
                $em->flush();
            }
        }else
        {
            //ajouter nouveau reaction de je n'aime plus
            $reaction = new ReactionSujet();
            $reaction->setUser($user);
            $reaction->setSujet($sujet);
            $reaction->setReaction('dislike');
            $em->persist($reaction);
            $em->flush();
        }




        $router = $this->container->get('router');
        return new RedirectResponse($router->generate('consulter_sujet' ,['id' => $id_sujet]), 307);

    }
}
