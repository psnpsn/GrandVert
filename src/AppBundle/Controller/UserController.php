<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    public function listAction(Request $request)
    {  $user = $this->container->get('security.token_storage')->getToken()->getUser();
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

        return $this->render('membre.html.twig' , ["Membres" => $Membres,"user" => $user]);
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
    public function profileAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('User/profile.html.twig' , ["user" => $user]);
    }
    public function profileadAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('User/profilead.html.twig' , ["user" => $user]);
    }

    public function homeadminAction()
    {
        return $this->render('admin_dashboard.html.twig');
    }

    public function homemembreAction()
    {
        return $this->render('default/index.html.twig');
    }

    public function homemoderateurAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('moderateur_dashboard.html.twig' , ["user" => $user]);
    }

    public function rolemembreAction(Request $request)
    {
        //recuperer id de membre pour changer son role
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $membre = $em->getRepository("AppBundle:User")->find($id);

        $rolesArr = array('ROLE_USER');

        $membre->setRoles($rolesArr); //set role to role membre

        $em->persist($membre);
        $em->flush();

        return $this->redirectToRoute("list_user/admin");
    }

    public function rolemoderateurAction(Request $request)
    {
        //recuperer id de membre pour changer son role
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $membre = $em->getRepository("AppBundle:User")->find($id);

        $rolesArr = array('ROLE_ADMIN');

        $membre->setRoles($rolesArr); //set role to role mederateur

        $em->persist($membre);
        $em->flush();

        return $this->redirectToRoute("list_user/admin");
    }


}
