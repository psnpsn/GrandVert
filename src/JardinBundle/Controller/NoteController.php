<?php

namespace JardinBundle\Controller;

use JardinBundle\Entity\Jardin;
use JardinBundle\Entity\Note;
use JardinBundle\Form\NoteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    public function addAction($jardinId,$date,Request $request)
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        if ($request->isXmlHttpRequest()) {
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
        }
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

    public function listAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();


        if(isset($request->request))
        {
            $jardin_id = $request->request->get('jardin_id');
            $days = $request->request->get('days_list');

            $jardin_id = intval($jardin_id);

            if ($jardin_id == 0)
            {
                return new JsonResponse(array(
                    'status' => 'Error',
                    'message' => 'Error'),
                    400);
            }

            $jardin = $this->getDoctrine()->getRepository('JardinBundle:Jardin')->find($jardin_id);

            if ($jardin === null)
            {
                return new JsonResponse(array(
                    'status' => 'Error',
                    'message' => 'Error'),
                    400);
            }

            $notes = $jardin->getNotes();
            $notes_array = array();
            foreach ($notes as $note)
            {
                foreach ($days as $day)
                {
                    $date = date_create_from_format("Y-n-d", $day);
                    $noteDate = $note->getDateN();
                    if ($date->format('Y-n-d') == $noteDate->format('Y-n-d'))
                    {
                        array_push($notes_array, array(
                            'contenu' => $note->getContenu(),
                            'date' => $day,
                            'id' => $note->getId()
                        ));
                    }
                }
            }
            return new JsonResponse(array(
                'status' => 'OK',
                'message' => $notes_array),
                200);
        }
        return new JsonResponse(array(
            'status' => 'Error',
            'message' => 'Error'),
            400);
    }

}
