<?php

namespace JardinBundle\Controller;

use JardinBundle\Entity\Note;
use JardinBundle\Form\NoteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NoteController extends Controller
{
    public function addAction(Request $request)
    {
        $note = new Note();
        $note->getJardinId();
        //build form
        $form = $this->createForm(NoteType::class, $note);

        //handling request and saving entity
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // echo 'suite au clic sur le bouton submit ';
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();
            return $this->redirectToRoute('profile_user');
        }
        return $this->render('JardinBundle:Note:add.html.twig', array(

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
