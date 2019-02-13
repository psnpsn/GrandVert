<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 12/02/2019
 * Time: 18:24
 */

namespace PlanteBundle\Controller;
use PlanteBundle\Entity\plante;
use PlanteBundle\Form\planteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlanteController extends Controller
{
    public function addAction(Request $request )
    {$user = $this->container->get('security.token_storage')->getToken()->getUser();
        $plante=new plante();
        $form=$this->createForm(planteType::class,$plante);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($plante);
            $em->flush();
            return $this->redirectToRoute('planteadd');}
        return $this->render('@Plante/plante/plante.html.twig',array(

            "Form"=>$form->createView(),"user"=>$user
        ));
    }
    public function affAction()
    {
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->findAll();
        return $this->render('@Plante/plante/affiche.html.twig',array(
            'plantes'=>$plante
        ));
    }
    public function consAction()
    {   $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->findAll();
        return $this->render('@Plante/plante/consulter.html.twig',array(
            'plantes'=>$plante,'user'=>$user
        ));
    }
    public function suppAction(Request $request)
    {  $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->find($id);
        $em->remove($plante);
        $em->flush();
        return $this->redirectToRoute('conulter');
    }
    public function modifAction(Request $request)
    {  $id=$request->get('id');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->find($id);
        $form=$this->createForm(planteType::class, $plante);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){//echo 'suie au clic';
            $em=$this->getDoctrine()->getManager();
            $em->persist($plante);
            $em->flush();
            return $this->redirectToRoute('conulter');}
        return $this->render('@Plante/plante/modifier.html.twig',array(

            "Form"=>$form->createView(),'user'=>$user,'plante'=>$plante
        ));
    }
    public function detAction(Request $request)
    {
        //recuperer les informations du  plante à consulter
        $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->find($id);

        //recuperer tous les sujets de plante à consulter
        $em= $this->getDoctrine()->getManager();
        $sujets=$em->getRepository("ForumBundle:Sujet")->findBy(['Plante'=> $plante ]);


        return $this->render('@Plante/plante/detaille.html.twig',array(
            'plante'=>$plante , "sujets" =>$sujets
        ));
    }
}