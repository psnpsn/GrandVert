<?php

namespace JardinBundle\Controller;

use JardinBundle\Entity\Plantation;
use JardinBundle\Form\PlantationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlantationController extends Controller
{
    public function addAction(Request $request)
    {
        $plantation = new Plantation();
        $form = $this->createForm(PlantationType::class, $plantation);
        $jardinId = $request->get('jardinId');
        $date = $request->get('date');

        $plants = $this->getDoctrine()->getRepository('PlanteBundle:plante')->findAll();

        if ($request->isXmlHttpRequest())
        {
            $jardin = $this->getDoctrine()->getRepository('JardinBundle:Jardin')->find($jardinId);
            $plantation->setJardin($jardin);
            $newDate = date_create_from_format("Y-n-d", $date);
            $plantation->setDateP($newDate);

            //handling request and saving entity
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($plantation);
                $em->flush();

                return new Response("Success");
            }

        }

        return $this->render('@Jardin/Plantation/add.html.twig', array(
            "Form"=>$form->createView(),
            "plants"=>$plants
        ));
    }

    public function updateAction()
    {
        return $this->render('JardinBundle:Plantation:update.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('JardinBundle:Plantation:delete.html.twig', array(
            // ...
        ));
    }

    public function listAction()
    {
        return $this->render('JardinBundle:Plantation:list.html.twig', array(
            // ...
        ));
    }

    public function viewAction()
    {
        return $this->render('JardinBundle:Plantation:view.html.twig', array(
            // ...
        ));
    }

}
