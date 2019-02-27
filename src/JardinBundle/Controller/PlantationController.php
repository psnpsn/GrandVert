<?php

namespace JardinBundle\Controller;

use JardinBundle\Entity\Plantation;
use JardinBundle\Form\PlantationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $plantt = $em->getRepository('JardinBundle:Plantation')->find($id);
        $em->remove($plantt);
        $em->flush();

        return new Response("Plantation supprimée");
    }

    public function listAction(Request $request)
    {
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

            $plantts = $jardin->getPlantations();
            $plantts_array = array();
            foreach ($plantts as $plantt)
            {
                $plantt->getPlante();
                foreach ($days as $day)
                {
                    $date = date_create_from_format("Y-n-d", $day);
                    $planttDate = $plantt->getDateP();
                    if ($date->format('Y-n-d') == $planttDate->format('Y-n-d'))
                    {
                        array_push($plantts_array, array(
                            'plante' => $plantt->getPlante()->getNom(),
                            'date' => $day,
                            'typeP' => $plantt->getTypeP(),
                            'qt' => $plantt->getQuantite(),
                            'id' => $plantt->getId()
                        ));
                    }
                }
            }
            return new JsonResponse(array(
                'status' => 'OK',
                'message' => $plantts_array),
                200);
        }
        return new JsonResponse(array(
            'status' => 'Error',
            'message' => 'Error'),
            400);
    }

    public function viewAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $plantt = $em->getRepository('JardinBundle:Plantation')->find($id);
        $plantt->getPlante();

        return $this->render('@Jardin/Plantation/view.html.twig', array(
            "plantt" => $plantt
        ));
    }

}
