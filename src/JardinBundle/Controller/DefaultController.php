<?php

namespace JardinBundle\Controller;

use JardinBundle\Entity\Jardin;
use JardinBundle\Repository\JardinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $jardin = new Jardin();
        $jardin->setUserId($user);
        $em = $this->getDoctrine()->getManager();
        $jardin = $em->getRepository('JardinBundle:Jardin')->findByUserId($user);
        if($request->isXmlHttpRequest()){
            $i=$request->get('width');
            return new JsonResponse($i);
        }
        return $this->render('@Jardin/index.html.twig', array(
            "jardin" => $jardin[0]
        ));
    }
}
