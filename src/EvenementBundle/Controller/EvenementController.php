<?php
/**
 * Created by PhpStorm.
 * User: Bouazza Med
 * Date: 19/02/2019
 * Time: 15:45
 */

namespace EvenementBundle\Controller;

use EvenementBundle\Entity\Evenement;
use EvenementBundle\Entity\Participants;
use EvenementBundle\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EvenementController extends Controller
{

    public function pageevAction()
    {
        $em=$this->getDoctrine()->getManager();
        $Evenement=$em->getRepository('EvenementBundle:Evenement')->findAll();
        return $this->render('@Evenement/Evenement/affiche.html.twig',array(
            'Evenements'=>$Evenement
        ));
    }
    public function ajouterevAction(Request $request )
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $Evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $Evenement);
        $form->handleRequest($request);
        $date = new \DateTime('now +1 day');
        if ($form->isSubmitted() && $form->isValid() && $form->get('dated')->getData()>=$date ) {
            $Evenement->setConfa(0);
            $user = $this->getUser();
            $Evenement->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($Evenement);
            $em->flush();
            $this->addFlash(
                'notice',
                'Evénement proposé avec succès !'
            );
            return $this->redirectToRoute('pageev');
        }
        elseif ($form->get('dated')->getData()<=$date)
        {
            $this->addFlash(
                'notice1',
                'La date de début doit etre inferieure à la date d\'aujourd\'hui -24 heures  !'
            );
            return $this->render('@Evenement/Evenement/ajouterev.html.twig', array(

                "Form" => $form->createView(), "user" => $user
            ));
        }
        return $this->render('@Evenement/Evenement/ajouterev.html.twig', array(

            "Form" => $form->createView(), "user" => $user
        ));
    }
    public function modifevAction(Request $request)
    {  $id=$request->get('id');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $evenement=$em->getRepository('EvenementBundle:Evenement')->find($id);
        $form=$this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getEntityManager();
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute('pageev');
        }
        return $this->render('@Evenement/Evenement/modifev.html.twig',array(

            "Form"=>$form->createView(),'user'=>$user,'Evenement'=>$evenement
        ));
    }
    public function evuserAction(Request $request)
    {
        $authChecker = $this->container->get('security.authorization_checker');
        $id = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("AppBundle:User")->find($id);
        $Evenement = $em->getRepository("EvenementBundle:Evenement")->findBy(['User' => $id]);
        return $this->render('@Evenement/Evenement/userev.html.twig', array(
            'Evenements' => $Evenement
        ));
    }
    public function consulterAction(Request $request)
    {
        $participant = new Participants();
        $id = $request->get('id');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $evenement = $em->getRepository('EvenementBundle:Evenement')->find($id);
        $participant = $em->getRepository("EvenementBundle:Participants")->findBy(["event" => $evenement, "user" => $user);
        if ($participant != null) {
            if ($participant[0]->getStatut() == 1) {
                return $this->render('@Evenement/Evenement/consulter1.html.twig',array(
                    'evenement'=>$evenement, "user" => $user
                ));
            }
         else {
             return $this->render('@Evenement/Evenement/consulter.html.twig',array(
                 'evenement'=>$evenement, "user" => $user
             ));
        }
    }else{
            return $this->render('@Evenement/Evenement/consulter.html.twig',array(
                'evenement'=>$evenement, "user" => $user
            ));
        }


    }
    public function participerAction(Request $request)
    {
        //recuperer l'id de l'événement
        $id_ev = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("EvenementBundle:Evenement")->find($id_ev);
        //recuperer l'id de l'uilisateur
        $user = $this->getUser();
        $Participant = new Participants();
        $Participant->setEvent($event);
        $Participant->setUser($user);
        $Participant->setStatut(1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($Participant);
        $em->flush();


        $router = $this->container->get('router');
        return new RedirectResponse($router->generate('consulter' ,['id' => $id_ev]), 307);

    }
    public function participerpAction(Request $request)
    {
        //recuperer l'id de l'événement
        $id_ev = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("EvenementBundle:Evenement")->find($id_ev);
        //recuperer l'id de l'uilisateur
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        //supprimer participation
        $participation = $em->getRepository("EvenementBundle:Participants")->findBy(["event" => $event, "user" => $user, "statut" => 1]);

        $em->remove($participation[0]);
        $em->flush();


        $router = $this->container->get('router');
        return new RedirectResponse($router->generate('consulter' ,['id' => $id_ev]), 307);

    }

    }