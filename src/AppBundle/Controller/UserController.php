<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    public function listAction(Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        //$Membres=$em->getRepository("AppBundle:User")->findBy(['roles'=> 'ROLE_ADMIN' ]);
        $Membres=$em->getRepository("AppBundle:User")->findAll();
        if($request->isMethod("post")) {
            $username = $request->get("username");
            $email = $request->get("email");
            $em = $this->getDoctrine()->getManager();
            $Membres = $em->getRepository("AppBundle:User")->findBy(array("username"=>$username , "email"=>$email));
            return $this->render('User/listuser.html.twig' , ["Membres" => $Membres]);
        }

        return $this->render('User/listuser.html.twig' , ["Membres" => $Membres]);
    }

    public function etatAction(Request $request)
    {
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $membre = $em->getRepository("AppBundle:User")->find($id);

        $etat= $membre->isEnabled();

        if($etat == 1)
            $membre->setEnabled(0);
        else
            $membre->setEnabled(1);
        $em->persist($membre);
        $em->flush();

        return $this->redirectToRoute("list_user/admin");
    }

    public function consulterAction(Request $request)
    {
        $id = $request->get("id");
        $em= $this->getDoctrine()->getManager();
        $user=$em->getRepository("AppBundle:User")->find($id);

        return $this->render('User/consulter_membre.html.twig' , ["user" => $user]);
    }


}
