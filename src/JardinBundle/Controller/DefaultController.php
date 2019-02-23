<?php

namespace JardinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $i=5;
        if($request->isXmlHttpRequest()){
            $i=$request->get('width');
            return new JsonResponse($i);
        }
        return $this->render('@Jardin/index.html.twig', array(
            "i" => $i
        ));
    }
}
