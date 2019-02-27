<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 19/02/2019
 * Time: 14:25
 */

namespace AchatBundle\Controller;

use AchatBundle\Entity\Commande;
use ForumBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class PanierController extends Controller
{
    public function conAction(Request $request)
    {
        $session = new Session();
        $panier=$session->get('panier');
        return $this->render('@Achat/panier/panier.html.twig',array("panier"=>$panier));
    }
    public function delAction(Request $request)
    {
        $id=$request->get('id');
        $session = new Session();
        $panier=$session->get('panier');
        $i=0;
        foreach ($panier as $plant){
            if($id==$plant->getId())array_splice($panier, $i, 1);
            $i=$i+1;
        }
        $session->set('panier',$panier);
        return $this->render('@Achat/panier/panier.html.twig',array("panier"=>$panier));
    }
    public function achAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id=$request->get('id');
        $cont=$request->get('amp;cont');
        $this->get('session')->getFlashBag()->add(
            'notice',
            'commande ajouter avec succes '
        );
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $plante=$em->getRepository('PlanteBundle:plante')->find($id);
        $commande=new Commande();
        $commande->setUser($user);
        $commande->setContite($cont);
        $commande->setPlante($plante);
        $commande->setDate(new \DateTime());
        $commande->setEtat("en cour...");
        $em->persist($commande);
        $em->flush();
        $session = new Session();
        $panier=$session->get('panier');
        $i=0;
        foreach ($panier as $plant){
            if($id==$plant->getId())array_splice($panier, $i, 1);
            $i=$i+1;
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute('panier');
    }
    public function comAction()
    {   $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $commande=$em->getRepository('AchatBundle:Commande')->findBy(['user'=> $user ]);
        return $this->render('@Achat/panier/commande.html.twig',array(
            'commandes'=>$commande
        ));
    }
    public function annAction(Request $request)
    {   $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $commande=$em->getRepository('AchatBundle:Commande')->find($id);
        $commande->setEtat("Annuler");
        $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute('commande');
    }
    public function consAction()
    {   $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $commande=$em->getRepository('AchatBundle:Commande')->findAll();
        return $this->render('@Achat/panier/cons.html.twig',array(
            'commandes'=>$commande,'user'=>$user
        ));
    }
    public function valAction(Request $request)
    {   $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $commande=$em->getRepository('AchatBundle:Commande')->find($id);
        $user = $em->getRepository("AppBundle:User")->find($commande->getUser());
        $commande->setEtat("Valider");
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setDate(new \DateTime());
        $notification->setTitle("validation commande");
        $notification->setSeen(false);
        $notification->setDescription("votre commande est valider");
        $em->persist($notification);
        $em->flush();
        $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute('conscom');
    }
    public function rejAction(Request $request)
    {   $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $commande=$em->getRepository('AchatBundle:Commande')->find($id);
        $user = $em->getRepository("AppBundle:User")->find($commande->getUser());
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setDate(new \DateTime());
        $notification->setTitle("refus de commande");
        $notification->setSeen(false);
        $notification->setDescription("votre commande est refuser");
        $em->persist($notification);
        $em->flush();
        $commande->setEtat("Rejeter");
        $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute('conscom');
    }
}