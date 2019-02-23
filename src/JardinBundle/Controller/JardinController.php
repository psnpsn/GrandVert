<?php

namespace JardinBundle\Controller;

use JardinBundle\Entity\Jardin;
use JardinBundle\Form\JardinType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class JardinController extends Controller
{
    public function addAction(Request $request)
    {
        //init jardin
        $jardin = new Jardin();
        $date = new \DateTime();
        $jardin->setDateC($date);
        $jardin->setUserId($this->getUser());
        //build form
        $form = $this->createForm(JardinType::class, $jardin);

        //handling request and saving entity
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // echo 'suite au clic sur le bouton submit ';
            $em = $this->getDoctrine()->getManager();
            $em->persist($jardin);
            $em->flush();
            return $this->redirectToRoute('profile_user');
        }
        return $this->render('@Jardin/Jardin/add.html.twig', array(
            "Form"=>$form->createView()
        ));
    }

    public function updateAction()
    {
        return $this->render('@Jardin/Jardin/update.html.twig', array(
            // ...
        ));
    }

}
