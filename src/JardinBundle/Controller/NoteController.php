<?php

namespace JardinBundle\Controller;

use JardinBundle\Entity\Jardin;
use JardinBundle\Entity\Note;
use JardinBundle\Form\NoteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    public function addAction($jardinId,$date,Request $request)
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        //if ($request->isXmlHttpRequest()) {
            $jardin = $this->getDoctrine()->getRepository('JardinBundle:Jardin')->find($jardinId);
            $note->setJardinId($jardin);
            $newDate = date_create_from_format("Y-n-d", $date);
            $note->setDateN($newDate);
            //handling request and saving entity
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // echo 'suite au clic sur le bouton submit ';
                $em = $this->getDoctrine()->getManager();
                $em->persist($note);
                $em->flush();
                return new Response("Success");
            }
        //}
        return $this->render('@Jardin/Note/add.html.twig', array(
            "Form"=>$form->createView()
        ));
    }

    public function updateAction()
    {
        return $this->render('JardinBundle:Note:update.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('JardinBundle:Note:delete.html.twig', array(
            // ...
        ));
    }

    public function viewAction()
    {
        return $this->render('JardinBundle:Note:view.html.twig', array(
            // ...
        ));
    }

}
