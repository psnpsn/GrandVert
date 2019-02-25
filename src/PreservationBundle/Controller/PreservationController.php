<?php
/**
 * Created by PhpStorm.
 * User: Ghaith
 * Date: 19/02/2019
 * Time: 09:55
 */

namespace PreservationBundle\Controller;


use PreservationBundle\Entity\Preservation;
use PreservationBundle\Entity\EspaceDePreservation;
use PreservationBundle\Form\PreservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PreservationController extends Controller
{

    public function listPreservationAction(Request $request)
    {
        //Crée une instance del'Entity Manager
        $user=$this->getUser();
        $em=$this->getDoctrine()->getManager();
        $reservartions=$em->getRepository('PreservationBundle:Preservation')->findBy([
            "user" => $user]);
        $Preservartions  = $this->get('knp_paginator')->paginate(
            $reservartions,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );
        return $this->render('@Preservation/Preservation/listPreservation.html.twig',array(
            'Preservartions'=>$Preservartions
        ));
    }

    public function exportPreservationAction()
    {
        //Crée une instance del'Entity Manager
        $em=$this->getDoctrine()->getManager();
        $Preservartions=$em->getRepository('PreservationBundle:Preservation')->findAll();
        return $this->render('@Preservation/Preservation/exportPreservation.html.twig',array(
            'Preservartions'=>$Preservartions
        ));
    }

    public function listPreservationForAdminAction(Request $request)
    {
        //Crée une instance del'Entity Manager
        $em=$this->getDoctrine()->getManager();
        $reservartions=$em->getRepository('PreservationBundle:Preservation')->findAll();
        $Preservartions  = $this->get('knp_paginator')->paginate(
            $reservartions,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );
        return $this->render('@Preservation/Preservation/listPreservationForAdmin.html.twig',array(
            'Preservartions'=>$Preservartions
        ));
    }


    public function AjoutPreservationAction(Request $request)
    {

        $user=$this->getUser();
        $Preservartion = new Preservation();
        $Preservartion->setUser($user);


        $form = $this->createForm(PreservationType::class, $Preservartion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {//echo 'suie au clic';
            $em = $this->getDoctrine()->getManager();
            $nbPlaces=$Preservartion->getNbPlaces();
            $EspaceDePreservation=$Preservartion->getEspaceDePreservation();
            $capacity=$EspaceDePreservation->getCapacity();
            $EspaceDePreservation->setCapacity($capacity-$nbPlaces);
            $em->persist($Preservartion);
            $em->flush();
            return $this->redirectToRoute('listPreservation');}
        return $this->render('@Preservation/Preservation/ajoutPreservation.html.twig', array(

            "Form" => $form->createView()
        ));
    }

    public function SupprimerPreservationAction(Request $request)
    {
        $user=$this->getUser();
       $a= $user->getRoles();

        $ghaith=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $Preservartion=$em->getRepository('PreservationBundle:Preservation')->find($ghaith);
        $em->remove($Preservartion);
        $em->flush();
        foreach ($a as $b) {
            if ($b=='ROLE_USER')
            {return $this->redirectToRoute('listPreservation');}
            elseif ($b=='ROLE_ADMIN')
            {return $this->redirectToRoute('listPreservationForAdmin');}
        }

    }

    public function modifierPreservationAction(Request $request )
    {
        $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $Preservartion=$em->getRepository('PreservationBundle:Preservation')->find($id);

        $form=$this->createForm(PreservationType::class,$Preservartion);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){//echo 'suie au clic';
            $em=$this->getDoctrine()->getManager();
            $em->persist($Preservartion);
            $em->flush();
            return $this->redirectToRoute('listPreservation');}
        return $this->render('@Preservation/Preservation/modifierPreservation.html.twig',array(

            "Form"=>$form->createView()
        ));
    }

    public function findPreservationDQLAction(Request $requete)
    {
        $dateDebut = $requete->get("dateDebut");
        $dateFin = $requete->get("dateFin");
        $em = $this->getDoctrine()->getManager();
        $Preservartions = $em->getRepository("PreservationBundle:Preservation")->findPreservationParametre($dateDebut,$dateFin);
        return $this->render('@Preservation/Preservation/recherchePreservation.html.twig',array("Preservartions" => $Preservartions));

    }


}