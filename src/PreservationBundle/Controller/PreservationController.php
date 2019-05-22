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
use Symfony\Component\HttpFoundation\Response;

class PreservationController extends Controller
{


    public function pdfAction(Request $request)
    {
        // Bundle Externe <<KnpSnappyBundle>>
        // Fonction D'Export to PDF

        $user=$this->getUser();
        $em=$this->getDoctrine()->getManager();
        $Preservartions=$em->getRepository('PreservationBundle:Preservation')->findAll();
        $snappy = $this->get('knp_snappy.pdf');

        $html = $this->renderView('@Preservation/Preservation/exportPreservation.html.twig', array(
            'Preservartions'=>$Preservartions,'user'=>$user
        ));

        $filename = 'myFirstSnappyPDF';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );

    }


    public function listPreservationAction(Request $request)
    {
        // Fonction D'affichage des Réservations spécifique à un user contecté <<By Id>> + option de supprission et de modification
        // Partie FrontOffice

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
        //Fonction D'Affichage des préservation + API <<JavaScript>> d'export To Csv,Excel
        //Partie Back Office

        $user=$this->getUser();
        $em=$this->getDoctrine()->getManager();
        $Preservartions=$em->getRepository('PreservationBundle:Preservation')->findAll();
        return $this->render('@Preservation/Preservation/exportPreservation.html.twig',array(
            'Preservartions'=>$Preservartions,'user'=>$user
        ));
    }

    public function listPreservationForAdminAction(Request $request)
    {
        // Fonction D'affichage de toutes les préservations
        // Partie Back Office
        $user=$this->getUser();
        $em=$this->getDoctrine()->getManager();
        $reservartions=$em->getRepository('PreservationBundle:Preservation')->findAll();
        $Preservartions  = $this->get('knp_paginator')->paginate(
            $reservartions,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );
        return $this->render('@Preservation/Preservation/listPreservationForAdmin.html.twig',array(
            'Preservartions'=>$Preservartions,'user'=>$user
        ));
    }


    public function AjoutPreservationAction(Request $request)
    {
        // Fonction D'ajout D'une Réservation
        // Partie Front Office

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

        //Fonction de Supprission D'une Réservation By Id
        // Partie Front Office

        $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $Preservartion=$em->getRepository('PreservationBundle:Preservation')->find($id);
        $em->remove($Preservartion);
        $em->flush();
        return $this->redirectToRoute('listPreservation');


    }

    public function modifierPreservationAction(Request $request )
    {
        //Fonction de Modification D'une Réservation By Id
        //Partie Front Office
        $user=$this->getUser();
        $a= $user->getRoles();

        $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $Preservartion=$em->getRepository('PreservationBundle:Preservation')->find($id);

        $form=$this->createForm(PreservationType::class,$Preservartion);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){//echo 'suie au clic';
            $em=$this->getDoctrine()->getManager();
            $em->persist($Preservartion);
            $em->flush();

            foreach ($a as $b) {
                if ($b=='ROLE_USER')
                {return $this->redirectToRoute('listPreservation');}
                else
                {return $this->redirectToRoute('listPreservationForAdmin');}
            }

           }
        return $this->render('@Preservation/Preservation/modifierPreservation.html.twig',array(

            "Form"=>$form->createView()
        ));
    }

    public function findPreservationDQLAction(Request $requete)
    {

        $user=$this->getUser();
        $dateDebut = $requete->get("dateDebut");
        $dateFin = $requete->get("dateFin");
        $em = $this->getDoctrine()->getManager();
        $Preservartions = $em->getRepository("PreservationBundle:Preservation")->findPreservationParametre($dateDebut,$dateFin);
        return $this->render('@Preservation/Preservation/recherchePreservation.html.twig',array("Preservartions" => $Preservartions,'user'=>$user));

    }

    public function findPreservationDQL2Action(Request $requete)
    {
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $Preservartions = $em->getRepository("PreservationBundle:Preservation")->findPreservationParametre2();
        return $this->render('@Preservation/Preservation/reservationsExpire.html.twig',array("Preservartions" => $Preservartions,'user'=>$user));

    }

    public function findPreservationDQL3Action(Request $requete)
    {

        $user=$this->getUser();
        $lieu = $requete->get("lieu");
        $em = $this->getDoctrine()->getManager();
        $Preservartions = $em->getRepository("PreservationBundle:Preservation")->findPreservationParametre3($lieu);
        return $this->render('@Preservation/Preservation/recherchePresByEspace.html.twig',array("Preservartions" => $Preservartions,'user'=>$user));

    }
}