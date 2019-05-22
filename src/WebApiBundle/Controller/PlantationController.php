<?php

namespace WebApiBundle\Controller;

use JardinBundle\Entity\Plantation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PlantationController extends Controller
{
    public function addPlantationAction(Request $request)
    {
        $plante = $this->getDoctrine()->getManager()
            ->getRepository('JardinBundle:Plantation')
            ->find($request->get('plante'));

        $plant = new Plantation();
        $plant->setPlante($plante);
        $plant->setTypeP($request->get('type'));
        $plant->setDateP($request->get('date'));
        $plant->setJardin($request->get('jardin'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($plant);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($plant);

        return new JsonResponse($formatted);
    }

    public function getPlantationAction(Request $request)
    {
        $date = $request->get('date');
        $newDate = date_create_from_format("Y-n-d", $date);

        $plant = $this->getDoctrine()->getManager()
            ->getRepository('JardinBundle:Plantation')
            ->findOneBy(["dateN" => $newDate,"jardinId" => $request->get('jardin')]);

        $myplant = [
            'id'=>$plant->getId(),
            'plante'=>$plant->getPlante()->getId(),
            'type'=>$plant->getTypeP(),
            'date'=>$plant->getDateP()->format('Y-m-d'),
            'jardin_id'=>$plant->getJardinId()->getId()

        ];

        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($myplant);

        return new JsonResponse($formatted);
    }

    public function updatePlantationAction(Request $request)
    {
        $plant = $this->getDoctrine()->getManager()
            ->getRepository('JardinBundle:Plantation')
            ->find($request->get('id'));

        $plant->setContenu($request->get('contenu'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($plant);
        $em->flush();

        $myplant = [
            'id'=>$plant->getId(),
            'plante'=>$plant->getPlante()->getId(),
            'type'=>$plant->getTypeP(),
            'date'=>$plant->getDateP()->format('Y-m-d'),
            'jardin_id'=>$plant->getJardinId()->getId()

        ];

        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($myplant);

        return new JsonResponse($formatted);
    }

    public function deletePlantationAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $plant = $em->getRepository('JardinBundle:Plantation')->find($id);
        $em->remove($plant);
        $em->flush();

        $myplant = [
            'id'=>$plant->getId(),
            'plante'=>$plant->getPlante()->getId(),
            'type'=>$plant->getTypeP(),
            'date'=>$plant->getDateP()->format('Y-m-d'),
            'jardin_id'=>$plant->getJardinId()->getId()

        ];

        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($myplant);

        return new JsonResponse($formatted);
    }
}
