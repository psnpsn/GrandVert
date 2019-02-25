<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 12/02/2019
 * Time: 18:24
 */

namespace PlanteBundle\Controller;
use PlanteBundle\Entity\plante;
use AppBundle\Entity\User;
use PlanteBundle\Form\planteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class PlanteController extends Controller
{
    public function addAction(Request $request )
    {$user = $this->container->get('security.token_storage')->getToken()->getUser();
        $plante=new plante();
        $form=$this->createForm(planteType::class,$plante);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $plante->setProposition(true);
            $em->persist($plante);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'notice',
                'ajouter avec succes!'
            );
            return $this->redirectToRoute('planteadd');}
        return $this->render('@Plante/plante/plante.html.twig',array(

            "Form"=>$form->createView(),"user"=>$user
        ));
    }
    public function propAction(Request $request )
    {$user = $this->container->get('security.token_storage')->getToken()->getUser();
        $plante=new plante();
        $form=$this->createForm(planteType::class,$plante);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $plante->setUser($user);
            $plante->setProposition(false);
            $em->persist($plante);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'notice',
                'proposition envoyer avec succes '
            );
            return $this->redirectToRoute('proposer');}
        return $this->render('@Plante/plante/prop.html.twig',array(

            "Form"=>$form->createView()
        ));
    }
    public function affAction()
    {
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->findAll();
        return $this->render('@Plante/plante/affiche.html.twig',array(
            'plantes'=>$plante
        ));
    }
    public function filAction(Request $request)
    {     $authChecker = $this->container->get('security.authorization_checker');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $cat=$request->get('cat');
        $em=$this->getDoctrine()->getManager();
        if($cat=="ALL")
            $plante=$em->getRepository('PlanteBundle:plante')->findAll();
        else
            $plante=$em->getRepository('PlanteBundle:plante')->findBy(array('categorie' => $cat));
        if ($authChecker->isGranted('ROLE_ADMIN'))
        return $this->render('@Plante/plante/consulter.html.twig',array(
            'plantes'=>$plante,'user'=>$user
        ));
        else
            return $this->render('@Plante/plante/affiche.html.twig',array(
                'plantes'=>$plante
            ));
    }
    public function propadAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->findAll();
        return $this->render('@Plante/plante/propad.html.twig',array(
            'plantes'=>$plante,"user"=>$user
        ));
    }
    public function consAction()
    {   $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->findAll();
        return $this->render('@Plante/plante/consulter.html.twig',array(
            'plantes'=>$plante,'user'=>$user
        ));
    }
    public function suppAction(Request $request)
    {  $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->find($id);
        $em->remove($plante);
        $em->flush();
        return $this->redirectToRoute('conulter');
    }
    public function modifAction(Request $request)
    {  $id=$request->get('id');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->find($id);
        $form=$this->createForm(planteType::class, $plante);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){//echo 'suie au clic';
            $em=$this->getDoctrine()->getManager();
            $em->persist($plante);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'notice',
                'modification avec succes!'
            );
            return $this->redirectToRoute('conulter');}
        return $this->render('@Plante/plante/modifier.html.twig',array(

            "Form"=>$form->createView(),'user'=>$user,'plante'=>$plante
        ));
    }
    public function accAction(Request $request)
    {  $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->find($id);
        $plante->setProposition(true);
            $em->persist($plante);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'notice',
                'modification avec succes!'
            );
            return $this->redirectToRoute('propad');

    }
    public function detAction(Request $request)
    {//recuperer les informations du  plante à consulter
        $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->find($id);
        //recuperer tous les sujets de plante à consulter
        $em= $this->getDoctrine()->getManager();
        $sujets=$em->getRepository("ForumBundle:Sujet")->findBy(['Plante'=> $plante ]);
        return $this->render('@Plante/plante/detaille.html.twig',array(
            'plante'=>$plante , "sujets" =>$sujets
        ));
    }
    public function panAction(Request $request)
    {$id=$request->get('id');$test=true;
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->find($id);
        $session = new Session();
        if(!$session->has('panier'))$session->set('panier',array());
        $panier=$session->get('panier');
        foreach ($panier as $plant){
            if($plante->getId()==$plant->getId())$test=false;
        }
       if($test){ array_push($panier,$plante);
           $session->set('panier',$panier);
           $this->get('session')->getFlashBag()->add(
               'notice',
               'ajouter au panier'
           );}else{$this->get('session')->getFlashBag()->add(
           'danger',
           'existe déja au panier'
       );}
        $em= $this->getDoctrine()->getManager();
        $sujets=$em->getRepository("ForumBundle:Sujet")->findBy(['Plante'=> $plante ]);
        return $this->render('@Plante/plante/detaille.html.twig',array(
            'plante'=>$plante , "sujets" =>$sujets
        ));
    }

    public function resAction(Request $request)
    {
        $nom=$request->get('nom');
        //recuperer les informations du  plante à consulter
        $em=$this->getDoctrine()->getManager();
        $plante=$em->getRepository('PlanteBundle:plante')->findOneBy(['nom'=>$nom]);
        //recuperer tous les sujets de plante à consulter
        $em= $this->getDoctrine()->getManager();
        $sujets=$em->getRepository("ForumBundle:Sujet")->findBy(['Plante'=> $plante ]);
        return $this->render('@Plante/plante/detaille.html.twig',array(
            'plante'=>$plante , "sujets" =>$sujets
        ));
    }
}