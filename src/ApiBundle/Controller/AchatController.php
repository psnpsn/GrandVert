<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 29/04/2019
 * Time: 14:43
 */

namespace ApiBundle\Controller;


use AchatBundle\Entity\Commande;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
class AchatController extends Controller
{
    public function allAction(Request $request)
    { $em = $this->getDoctrine()->getManager();
        $id=$request->get('id');
        $membre = $em->getRepository("AppBundle:User")->find($id);
        $commande = $this->getDoctrine()->getManager()
            ->getRepository('AchatBundle:Commande')->findBy(['user'=> $membre ]);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($commande);
        return new JsonResponse($formatted);
    }
    public function newAction(Request $request)
    {    $commande = new Commande();
        $idu=$request->get('idu');
        $idp=$request->get('idp');
        $em = $this->getDoctrine()->getManager();
        $plante=$this->getDoctrine()->getManager()
            ->getRepository('PlanteBundle:plante')->find($idp);
        $user = $em->getRepository("AppBundle:User")->find($idu);
        $commande->setUser($user);
        $commande->setPlante($plante);
        $commande->setDate(new DateTime());
        $commande->setEtat("en cours...");
        $commande->setContite($request->get('cont'));
        $em->persist($commande);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($commande);
        return new JsonResponse($formatted);

    }
    public function annAction(Request $request)
    {   $id=$request->get('id');
        $em = $this->getDoctrine()->getManager();
    $commande=$this->getDoctrine()->getManager()
        ->getRepository('AchatBundle:Commande')->find($id);
        $commande->setEtat("Annuler");
        $em->persist($commande);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($commande);
        return new JsonResponse($formatted);

    }
}