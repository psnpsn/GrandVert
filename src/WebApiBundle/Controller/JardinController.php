<?php

namespace WebApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JardinController extends Controller
{
    public function getJardinAction($id)
    {

        $jardin = $this->getDoctrine()->getManager()
            ->getRepository('JardinBundle:Jardin')
            ->find($id);

        $myJardin = [
            'id'=>$jardin->getId(),
            'nom'=>$jardin->getNom(),
            'date_creation'=>$jardin->getDateC()->format('Y-m-d'),
            'user_id'=>$jardin->getUser()->getId()
        ];

        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($myJardin);

        return new JsonResponse($formatted);
    }

}
