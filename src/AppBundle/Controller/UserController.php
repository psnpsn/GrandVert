<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

        //rediger l'utilisateur vers leur interface apres le changement de l'etat
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        //verfier si l'utilisateur connecter est un admin pour acceder à admin dashboard
        if ($authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            return $this->render('admin_dashboard.html.twig' , ["user" => $user]);
        }

        //verfier si l'utilisateur connecter est un moderateur pour acceder à son dashboard
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            return new RedirectResponse($router->generate('list_user/moderateur'), 307);
        }

    }

    public function consulterAction(Request $request)
    {
        $id = $request->get("id");
        $em= $this->getDoctrine()->getManager();
        $user=$em->getRepository("AppBundle:User")->find($id);

        return $this->render('User/consulter_membre.html.twig' , ["user" => $user]);
    }

    public function accountAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('User/account.html.twig' , ["user" => $user]);
    }

    public function settingsAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('User/account.html.twig' , ["user" => $user]);
    }


    public function preferencesAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('User/account.html.twig' , ["user" => $user]);
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
        $em = $this->getDoctrine()->getManager();
        $notifications = $em->getRepository("ForumBundle:Notification")->findBy(["User" => $this->getUser() , "seen" => false]);

        return $this->render('default/index.html.twig' , ["notifications" => $notifications , "count" => count($notifications)]);
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

    public function listmembreAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em= $this->getDoctrine()->getManager();

        $rolesArr = array('ROLE_USER');

        //recuperer tous les membres
        $query = $this->getDoctrine()->getEntityManager()
            ->createQuery(
                'SELECT u FROM AppBundle:User u WHERE u.roles LIKE :role'
            )->setParameter('role', 'a:0:{}');

        $Membres = $query->getResult();

        return $this->render('Listmembre.html.twig' , ["Membres" => $Membres,"user" => $user]);
    }


    public function searchAction(Request $request)
    {  $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em= $this->getDoctrine()->getManager();

        //recuperer tous les moderateurs
        $query = $this->getDoctrine()->getEntityManager()
            ->createQuery(
                'SELECT u FROM AppBundle:User u WHERE u.roles LIKE :role or u.roles LIKE :role '
            )->setParameter('role', 'a:1:{i:0;s:10:"ROLE_ADMIN";}')->setParameter('role', 'a:0:{}');

        $Membres = $query->getResult();

        if($request->isMethod("post")) {
            $email = $request->get("email");
            $query = $this->getDoctrine()->getEntityManager()
                ->createQuery(
                    'SELECT u FROM AppBundle:User u WHERE (u.email = :email) and (u.roles LIKE :rolemod or u.roles LIKE :rolemem) '
                )->setParameter('email', $email)->setParameter('rolemod', 'a:1:{i:0;s:10:"ROLE_ADMIN";}')->setParameter('rolemem', 'a:0:{}');

            $Membres = $query->getResult();

            return new JsonResponse(array( "Membres" , $Membres));
        }

        return new JsonResponse(array( "Membres" , $Membres));
    }

}
