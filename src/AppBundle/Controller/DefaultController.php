<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /* replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
        */

        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        //verfier si l'utilisateur connecter est un admin pour acceder à admin dashboard
        if ($authChecker->isGranted('ROLE_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            return $this->render('admin_dashboard.html.twig' , ["user" => $user]);
            //return new RedirectResponse($router->generate('admin_home'), 307);
        }

        //verfier si l'utilisateur connecter est un membre  pour acceder à son interface
        if ($authChecker->isGranted('ROLE_USER')) {
            return new RedirectResponse($router->generate('user_home'), 307);
        }

        return new RedirectResponse($router->generate('user_home'), 307);
    }
}
