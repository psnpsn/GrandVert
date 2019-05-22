<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 27/04/2019
 * Time: 17:51
 */

namespace ApiBundle\Controller;

use PlanteBundle\Entity\plante;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PlanteController extends Controller
{
    public function allAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('PlanteBundle:plante')->findBy(['proposition'=>0]);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }
    public function findAction($id)
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('PlanteBundle:plante')->findby(["id"=>$id]);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }
    public function newAction(Request $request)
    {    $plante = new plante();
    $id=$request->get('id');
    $em = $this->getDoctrine()->getManager();
        $membre = $em->getRepository("AppBundle:User")->find($id);
        $plante->setNom($request->get('nom'));
        $plante->setPrix($request->get('prix'));
        $plante->setStock($request->get('stock'));
        $plante->setHauteur($request->get('hauteur'));
        $plante->setFertiliseur($request->get('fert'));
        $plante->setCategorie($request->get('cat'));
        $plante->setSeason($request->get('seas'));
        $plante->setDescription($request->get('desc'));
        $plante->setProposition(1);
        $plante->setUser($membre);
        $plante->setPhoto($request->get('photo'));
        $em->persist($plante);
        $em->flush();
      $serializer = new Serializer([new ObjectNormalizer()]);
       $formatted = $serializer->normalize($plante);
       return new JsonResponse($formatted);

    }
}