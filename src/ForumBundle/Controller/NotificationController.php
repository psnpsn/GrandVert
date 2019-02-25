<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends Controller
{
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $notifications = $em->getRepository("ForumBundle:Notification")->findBy(["User" => $this->getUser() , "seen" => false]);

        //return $this->render('notification.html.twig' , ["notifications" => $notifications]);

        return new JsonResponse(array( 'notifications'=> $notifications,'count'=> count($notifications) ));
        //return new JsonResponse(array("data" => json_encode($notifications)));
    }

}
