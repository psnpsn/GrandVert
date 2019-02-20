<?php

namespace JardinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $req)
    {

        $range = 0;

        return $this->render('@Jardin/index.html.twig', array(
            'range' => $range+1
        ));
    }

    public function loadDaysAction(Request $req)
    {
        $range = $req->request->get('range');
        $range = "ref";
        return new Response($range);
    }
}
