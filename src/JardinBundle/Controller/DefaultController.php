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

        //$openWeather = $this->get('dwr_open_weather');
        //$weather = $openWeather->setType('Weather')->getByCityName('Tunis');

        $user = $this->getUser();
        $jardin = new Jardin();
        $jardin->setUser($user);
        $em = $this->getDoctrine()->getManager();
        $jardin = $em->getRepository('JardinBundle:Jardin')->findByUser($user);
        if (null == $jardin)
        {
            return $this->redirectToRoute('jardin_add');
        }
        if($request->isXmlHttpRequest()){
            $i=$request->get('width');
            return new JsonResponse($i);
        }
        return $this->render('@Jardin/index.html.twig', array(
            "jardin" => $jardin[0],
            //"wth" => $weather
        ));
    }

    public function wthAction(Request $request)
    {

        $openWeather = $this->get('dwr_open_weather');
        $weather = $openWeather->setType('Weather')->getByCityName('Tunis');

        return new JsonResponse($weather);
    }
}
