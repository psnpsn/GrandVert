<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Notification;
use ForumBundle\Entity\ReactionSujet;
use ForumBundle\Entity\Reponse;
use ForumBundle\Entity\Sujet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class SujetController extends Controller
{

    public function ajouterAction(Request $request) {

        //verifier si il ya un utilisateur connecter ou non pour faire l'ajout
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');


        if (($authChecker->isGranted('ROLE_ADMIN')) || ($authChecker->isGranted('ROLE_USER')) ) {
            //recuperer l'id de plante pour affecter au sujet
            $id = $request->get("id");
            $em = $this->getDoctrine()->getManager();
            $plante = $em->getRepository("PlanteBundle:plante")->find($id);

            $sujet = new Sujet(); //creer un nouveau instance de l'entity sujet

            //saisir leur données
            $sujet->setSujetOriginal($request->request->get('postoriginal')); //recuperer le contenue du sujet sasie par l'utilisateur connecté
            $sujet->setSujetEdited("");
            $sujet->setDateoriginal(new \DateTime());
            $sujet->setDateedited(new \DateTime());
            $sujet->setOpen('true');
            $sujet->setResolu('false');
            $sujet->setPlante($plante);

            //recuperer l'uilisateur connecter qui va ajouter un sujet
            $user = $this->getUser();
            $sujet->setUser($user);

            $validator = $this->get('validator');
            $errors = $validator->validate($sujet);

            $errorsString = null;

            if (count($errors) > 0) {
                /*
                 * Uses a __toString method on the $errors variable which is a
                 * ConstraintViolationList object. This gives us a nice string
                 * for debugging.
                 */
                $errorsString = (string) $errors;

                return new RedirectResponse($router->generate('afficher_sujet' ,['id' => $plante->getId() , "message" => "" , "messageError" => "Sujet ne doit pas etre vide !!"]), 307);
            }

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($sujet);
            $em->flush();


            //recuperer tous les moderateurs
            $query = $this->getDoctrine()->getEntityManager()
                ->createQuery(
                    'SELECT u FROM AppBundle:User u WHERE u.roles LIKE :role'
                )->setParameter('role', 'a:1:{i:0;s:10:"ROLE_ADMIN";}');

            $moderateurs = $query->getResult();

            //parcourir lalist de moderateur , et envoyer à chacun une notification
            for($i = 0; $i < count($moderateurs); ++$i) {
                $notification = new Notification();

                $notification->setUser($moderateurs[$i]);
                $notification->setDate(new \DateTime());
                $notification->setTitle("Sujet");
                $notification->setSeen(false);
                $notification->setDescription("un nouveau sujet a été ajouter !!");
                $notification->setRoute("consulter_sujet");
                //$notification->setParameters(["id" => $this->getId()]);

                $em->persist($notification);
                $em->flush();
            }

            return new RedirectResponse($router->generate('afficher_sujet' ,['id' => $plante->getId() , "message" => "show" , "errorsString" => ""]), 307);

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
        $sujets=$em->getRepository("ForumBundle:Sujet")->findBy(['Plante'=> $plante , 'archive'=> false  ]);

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
            $reponses=$em->getRepository("ForumBundle:Reponse")->findBy(['Sujet'=> $sujets[$i] , "archive" => false ]);

            array_push($NbReponses, array('sujet' => $sujets[$i] , 'NbReponseC' => count($reponses)));
        }

        if (($authChecker->isGranted('SUPER_ROLE_ADMIN')) ||($authChecker->isGranted('ROLE_ADMIN')) || ($authChecker->isGranted('ROLE_USER')) ) {
            //recuperer l'uilisateur connecter
            $id = $this->getUser();
            $user = $em->getRepository("AppBundle:User")->find($id);
        }else {
            $user = null;
        }

        //verifier si il le message de sucées va afficher ou non
        $operation = $request->get("message");

        $errorsString = $request->get("messageError");


        return $this->render('@Forum/Sujet/afficher.html.twig' , ["sujets" => $paginationsujets , "plante" => $plante ,"NbReponses" => $NbReponses ,"User" => $user , "message" => $operation , "errorsString" => $errorsString]);
        }

    public function consulterAction(Request $request)
    {
        $id = $request->get("id"); // get id de sujet consulter

        $authChecker = $this->container->get('security.authorization_checker');


        //recuperer le sujet à consulter
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        $AllReactionSujet = $em->getRepository("ForumBundle:ReactionSujet")->findBy(["Sujet"=>$sujet]);

        $nbLike = 0;
        $nbDislike = 0;

        //calculer le nb de like et dislike sur le sujet
        for($i = 0; $i < count($AllReactionSujet); ++$i) {
            if($AllReactionSujet[$i]->getReaction() == 'like')
                $nbLike++;
            else
                $nbDislike++;
        }

        //incrementer le nb de view de sujet
        $nbviews= $sujet->getNbviews();
        $sujet->setNbviews($nbviews+1);
        $em->persist($sujet);
        $em->flush();

        //calculer nb de like et dislike pour chaque reponse du sujet
        $NbReactionreponses = array(
            array('reponse' => new Reponse() , 'nbLike' => 0 , 'nbDisLike' => 0)
        );

        //recuperer les reponses de sujet à consulter
        $reponses=$em->getRepository("ForumBundle:Reponse")->findBy(['Sujet'=> $sujet , 'archive'=> false ]);

        //parcourir la liste de reponse de sujet
        for($i = 0; $i < count($reponses); ++$i) {

            //recuperer tous les reactions du reponse pour like et dislike
            $ListLike = $em->getRepository("ForumBundle:ReactionReponse")->findBy(["Reponse" => $reponses[$i] , "reaction" => "like"]);
            $ListDisLike = $em->getRepository("ForumBundle:ReactionReponse")->findBy(["Reponse" => $reponses[$i] , "reaction" => "dislike"]);

             //inserer le nb de like et disklike dans le tableau de nb reaction de tous les reponses
             array_push($NbReactionreponses, array('reponse' => $reponses[$i] , 'nbLike' => count($ListLike) , 'nbDisLike' => count($ListDisLike)));

        }
        //pagination data
        $paginationreponses  = $this->get('knp_paginator')->paginate(
            $reponses,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );

        if ( ($authChecker->isGranted('SUPER_ROLE_ADMIN')) ||($authChecker->isGranted('ROLE_ADMIN')) || ($authChecker->isGranted('ROLE_USER')) ) {
            //recuperer l'uilisateur connecter
            $id = $this->getUser();
            $user = $em->getRepository("AppBundle:User")->find($id);
        }else {
            $user = null;
        }

        //verifier si il le message de sucées va afficher ou non
        $operation = $request->get("message");

        //verifier si le message d'erreur aprés l'ajout d'un réponse va afficher ou non
        $errorsString = $request->get("messageError");

        return $this->render('@Forum/Sujet/consulter.html.twig', ["sujet" => $sujet , "reponses" => $paginationreponses ,"User" => $user ,"nblikeSujet" => $nbLike ,"nbDislikeSujet" => $nbDislike , "NbReactionreponses" => $NbReactionreponses , "message" => $operation , "errorsString" => $errorsString ]);
    }

    public function supprimerAction(Request $request)
    {

        //recuperer id du sujet à supprimer
        $id = $request->get('id');

        //recuperer le sujet à supprimer à partir de leur id
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        //recuperer le plante de sujet a supprimer pour trouver leur sujet aprés la supression
        $plante = $em->getRepository("PlanteBundle:plante")->find($sujet->getPlante());

        //remove sujet
        $em->remove($sujet);
        $em->flush();

        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        //verfier si l'utilisateur connecter est un admin pour acceder à admin dashboard aprés la supression de sujet
        if ($authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            return $this->render('admin_dashboard.html.twig' , ["user" => $user]);
        }

        //verfier si l'utilisateur connecter est un moderateur pour acceder à son dashboard aprés la supression de sujet
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return $this->generateUrl("moderateur_forum"); //user connecter est un moderateur
        }

        //user connecter est un membre
        return new RedirectResponse($router->generate("afficher_sujet",['id' => $plante->getId() , "message" => "show"]), 307);

    }

    public function deleteAction(Request $request)
    {

        //recuperer id du sujet à supprimer par le moderateur ou bien par l'admin
        $id = $request->get('id');

        //recuperer le sujet à supprimer à partir de leur id
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        //remove sujet
        $em->remove($sujet);
        $em->flush();

        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        //verfier si l'utilisateur connecter est un admin pour acceder à admin dashboard aprés la supression de sujet
        if ($authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            return new RedirectResponse($router->generate('admin_forum'), 307);
        }

        //verfier si l'utilisateur connecter est un moderateur pour acceder à son dashboard aprés la suppression de sujet
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            //user connecter est un moderateur
            return new RedirectResponse($router->generate('moderateur_forum'), 307);
        }
    }

    public function sujetsuserAction(Request $request)
    {
        $authChecker = $this->container->get('security.authorization_checker');
        //recuperer l'uilisateur connecter pour trouver leur sujet
        $id = $this->getUser();
        $em= $this->getDoctrine()->getManager();
        $user = $em->getRepository("AppBundle:User")->find($id);

        //recuperer tous les sujets de l'utilisateru connecter
        $sujets=$em->getRepository("ForumBundle:Sujet")->findBy(['User'=> $user , 'archive'=> false]);

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


        return $this->render('@Forum/Sujet/afficher.html.twig' , ["sujets" => $paginationsujets , "plante" => $plante ,"NbReponses" => $NbReponses ,"User" => $user , "message" => "hide"]);
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

    public function nonresoluAction(Request $request) {

        //recuperer l'id de sujet à modifier
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        //modifier leur etat à non resolut
        $sujet->setResolu("false");

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($sujet);
        $em->flush();

        $router = $this->container->get('router');
        return new RedirectResponse($router->generate('consulter_sujet' ,['id' => $id]), 307);

    }

    public function resoluAction(Request $request) {

        //recuperer l'id de sujet à modifier
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        //modifier leur etat à resolut
        $sujet->setResolu("true");

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($sujet);
        $em->flush();

        $router = $this->container->get('router');
        return new RedirectResponse($router->generate('consulter_sujet' ,['id' => $id]), 307);

    }

    public function fermerAction(Request $request) {

        //recuperer l'id de sujet à modifier
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);


        //modifier leur etat à fermer
        $sujet->setOpen("false");

        $em->persist($sujet);
        $em->flush();


        $user = $em->getRepository("AppBundle:User")->find($sujet->getUser());

        //notifier user si le moderateur a fermer son sujet
        if($this->getUser() != $user){
            $notification = new Notification();

            $notification->setUser($user);
            $notification->setDate(new \DateTime());
            $notification->setTitle("Sujet");
            $notification->setSeen(false);
            $notification->setDescription("votre sujet à été fermer par le Moderateur/Admin");
            $notification->setRoute("consulter_sujet");
            $notification->setParameters(["id" => $sujet->getId()]);

            $em->persist($notification);
            $em->flush();
        }


        $router = $this->container->get('router');
        return new RedirectResponse($router->generate('consulter_sujet' ,['id' => $id]), 307);

    }

    public function ouvertAction(Request $request) {

        //recuperer l'id de sujet à modifier
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        //modifier leur etat à ouver
        $sujet->setOpen("true");

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($sujet);
        $em->flush();

        $user = $em->getRepository("AppBundle:User")->find($sujet->getUser());

        //notifier user si le moderateur a fermer son sujet
        if($this->getUser() != $user){
            $notification = new Notification();

            $notification->setUser($user);
            $notification->setDate(new \DateTime());
            $notification->setTitle("Sujet");
            $notification->setSeen(false);
            $notification->setDescription("votre sujet à été ouvrer par le Moderateur/Admin");
            $notification->setRoute("consulter_sujet");
            $notification->setParameters(["id" => $sujet->getId()]);

            $em->persist($notification);
            $em->flush();
        }


        $router = $this->container->get('router');
        return new RedirectResponse($router->generate('consulter_sujet' ,['id' => $id]), 307);

    }

    public function forumAction(Request $request) {

        //recuperer tous les sujets
        $em = $this->getDoctrine()->getManager();
        $sujets = $em->getRepository("ForumBundle:Sujet")->findBy(['archive'=> false]);

        //pagination data
        $paginationsujets  = $this->get('knp_paginator')->paginate(
            $sujets,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            3/*nbre d'éléments par page*/
        );

        //recuperer l'uilisateur connecter
        $id = $this->getUser();
        $user = $em->getRepository("AppBundle:User")->find($id);

        $authChecker = $this->container->get('security.authorization_checker');

        //verfier si l'utilisateur connecter est un admin pour acceder à admin dashboard aprés la supression de sujet
        if ($authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->render('@Forum/Sujet/sujet_admin.html.twig' , ["sujets" => $paginationsujets , "user" => $user  ]);

        }

        //verfier si l'utilisateur connecter est un moderateur pour acceder à son dashboard aprés la suppression de sujet
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            //user connecter est un moderateur
            return $this->render('@Forum/Sujet/sujet_moderat.html.twig' , ["sujets" => $paginationsujets , "user" => $user  ]);

        }
    }

    public function archiverAction(Request $request) {

        //recuperer l'id de sujet à archiver
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        //modifier leur etat à archiver
        $sujet->setArchive(true);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($sujet);
        $em->flush();

        $user = $em->getRepository("AppBundle:User")->find($sujet->getUser());

        //notifier user si le moderateur a fermer son sujet
        if($this->getUser() != $user){
            $notification = new Notification();

            $notification->setUser($user);
            $notification->setDate(new \DateTime());
            $notification->setTitle("Sujet");
            $notification->setSeen(false);
            $notification->setDescription("votre sujet à été supprimer par le Moderateur/Admin");
            $notification->setRoute("consulter_sujet");
            $notification->setParameters(["id" => $sujet->getId()]);

            $em->persist($notification);
            $em->flush();
        }


        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        //verfier si l'utilisateur connecter est un admin pour acceder à admin dashboard aprés la supression de sujet
        if ($authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            return new RedirectResponse($router->generate('admin_dashboard.html.twig' , ["user" => $user]));
        }

        //verfier si l'utilisateur connecter est un moderateur pour acceder à son dashboard aprés la supression de sujet
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($router->generate("moderateur_forum")); //user connecter est un moderateur
        }

        //user connecter est un membre
        return new RedirectResponse($router->generate("afficher_sujet",['id' => $sujet->getPlante()->getId() , "message" => "show"]), 307);

    }

    public function signalerAction(Request $request) {

        //recuperer l'id de sujet à signaler
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository("ForumBundle:Sujet")->find($id);

        //modifier le nb de signaler du sujet
        $sujet->setNbsignal($sujet->getNbsignal()+1);

        //verifier le nb de signaler si il atteint 10 signaler , changer letat de sujet à archiver !
        if($sujet->getNbsignal() == 10)
        {
            //modifier leur etat à archiver
            $sujet->setArchive(true);

            //modifier le nb de signaler du sujet à 0
            $sujet->setNbsignal(0);
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($sujet);
        $em->flush();

        $user = $em->getRepository("AppBundle:User")->find($sujet->getUser());


        //notifier le reponsable de sujet
            $notification = new Notification();

            $notification->setUser($user);
            $notification->setDate(new \DateTime());
            $notification->setTitle("Sujet");
            $notification->setSeen(false);
            $notification->setDescription("votre sujet à été supprimer à cause de signale");
            $notification->setRoute("consulter_sujet");
            $notification->setParameters(["id" => $sujet->getId()]);

            $em->persist($notification);
            $em->flush();


        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        //verfier si l'utilisateur connecter est un admin pour acceder à admin dashboard aprés la supression de sujet
        if ($authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            return $this->render('admin_dashboard.html.twig' , ["user" => $user]);
        }

        //verfier si l'utilisateur connecter est un moderateur pour acceder à son dashboard aprés la supression de sujet
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return $this->generateUrl("moderateur_forum"); //user connecter est un moderateur
        }

        //user connecter est un membre
        return new RedirectResponse($router->generate("afficher_sujet",['id' => $sujet->getPlante()->getId()]), 307);

    }








}
