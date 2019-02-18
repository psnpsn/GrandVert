<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\ReactionReponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ReactionReponseController extends Controller
{
    public function likeAction(Request $request) {

        //recuperer l'id de reponse à aimer
        $id_reponse = $request->get("id_reponse");

        $em = $this->getDoctrine()->getManager();
        $reponse = $em->getRepository("ForumBundle:Reponse")->find($id_reponse);

        //recuperer l'uilisateur qui va aimer ce reponse
        $id_user = $this->getUser();
        $user = $em->getRepository("AppBundle:User")->find($id_user);

        //chercher si user deja faire un reaction a ce reponse !!
        $reaction = $em->getRepository("ForumBundle:ReactionReponse")->findBy(["Reponse" =>$reponse , "User" => $user]);

        if($reaction != null) {
            //remove reaction
            $em->remove($reaction[0]);
            $em->flush();
        }

        //ajouter nouveau reaction de j'aime
        $reaction = new ReactionReponse();
        $reaction->setUser($user);
        $reaction->setReponse($reponse);
        $reaction->setReaction('like');
        $em->persist($reaction);
        $em->flush();


        //recuperer l'id de sujet à consulter aprés l'ajout de reaction sujr le reponse
        $id_sujet = $request->get("id_sujet");

        $router = $this->container->get('router');
        return new RedirectResponse($router->generate('consulter_sujet' ,['id' => $id_sujet]), 307);

    }

    public function dislikeAction(Request $request) {

        //recuperer l'id de reponse à mettre je n'aime plus
        $id_reponse = $request->get("id_reponse");

        $em = $this->getDoctrine()->getManager();
        $reponse = $em->getRepository("ForumBundle:Reponse")->find($id_reponse);

        //recuperer l'uilisateur qui va mettre un je n'aime plus sur le reponse
        $id_user = $this->getUser();
        $user = $em->getRepository("AppBundle:User")->find($id_user);

        //chercher si user deja faire un reaction a ce reponse !!
        $reaction = $em->getRepository("ForumBundle:ReactionReponse")->findBy(["Reponse" =>$reponse , "User" => $user]);

        if($reaction != null) {
            //remove reaction
            $em->remove($reaction[0]);
            $em->flush();
        }

        //ajouter nouveau reaction de j'aime
        $reaction = new ReactionReponse();
        $reaction->setUser($user);
        $reaction->setReponse($reponse);
        $reaction->setReaction('dislike');
        $em->persist($reaction);
        $em->flush();


        //recuperer l'id de sujet à consulter aprés l'ajout de reaction sujr le reponse
        $id_sujet = $request->get("id_sujet");

        $router = $this->container->get('router');
        return new RedirectResponse($router->generate('consulter_sujet' ,['id' => $id_sujet]), 307);

    }
}
