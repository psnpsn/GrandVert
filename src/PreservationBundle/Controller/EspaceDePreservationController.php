<?php
/**
 * Created by PhpStorm.
 * User: Ghaith
 * Date: 18/02/2019
 * Time: 21:14
 */

namespace PreservationBundle\Controller;


use PreservationBundle\Entity\EspaceDePreservation;
use PreservationBundle\Form\EspaceDePreservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EspaceDePreservationController extends Controller
{

    public function listAction(Request $request)
    {
        //Affichage des espaces de preservation + option de supprission et de modification

        $em=$this->getDoctrine()->getManager();
        $Preservartions=$em->getRepository('PreservationBundle:EspaceDePreservation')->findAll();

        $EspaceDePreservartions  = $this->get('knp_paginator')->paginate(
            $Preservartions,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );

        return $this->render('@Preservation/EspaceDePreservation/list.html.twig',array(
            'EspaceDePreservartions'=>$EspaceDePreservartions
        ));
    }

    public function exportAction(Request $request)
    {
        //affichage des listes des espaces de préservation + API d'export To Json,Csv,Excel,Txt,XML

        $em=$this->getDoctrine()->getManager();
        $Preservartions=$em->getRepository('PreservationBundle:EspaceDePreservation')->findAll();
        $EspaceDePreservartions  = $this->get('knp_paginator')->paginate(
            $Preservartions,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );


        return $this->render('@Preservation/EspaceDePreservation/export.html.twig',array(
            'EspaceDePreservartions'=>$EspaceDePreservartions
        ));
    }



    public function AjoutAction(Request $request)
    {

        $user=$this->getUser();
        $EspaceDePreservartion = new EspaceDePreservation();
        $EspaceDePreservartion->setUser($user);
        $form = $this->createForm(EspaceDePreservationType::class, $EspaceDePreservartion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {//echo 'suie au clic';
            $em = $this->getDoctrine()->getManager();
            $em->persist($EspaceDePreservartion);
            $em->flush();
             return $this->redirectToRoute('list');}
            return $this->render('@Preservation/EspaceDePreservation/ajout.html.twig', array(

                "Form" => $form->createView(),"user"=>$user
            ));
        }

    public function DeleteAction(Request $request)
    {
        $ghaith=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $EspaceDePreservartion=$em->getRepository('PreservationBundle:EspaceDePreservation')->find($ghaith);
        $em->remove($EspaceDePreservartion);
        $em->flush();
        return $this->redirectToRoute('list');
    }

    public function ModifierAction(Request $request )
    {
        $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $EspaceDePreservartion=$em->getRepository('PreservationBundle:EspaceDePreservation')->find($id);

        $form=$this->createForm(EspaceDePreservationType::class,$EspaceDePreservartion);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){//echo 'suie au clic';
            $em=$this->getDoctrine()->getManager();
            $em->persist($EspaceDePreservartion);
            $em->flush();
            return $this->redirectToRoute('list');}
        return $this->render('@Preservation/EspaceDePreservation/modifier.html.twig',array(

            "Form"=>$form->createView()
        ));
    }

}